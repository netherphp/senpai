<?php

namespace Nether\Senpai;
use \Nether;
use \PhpParser;

use \SplFileInfo;
use \PhpParser\ParserFactory;
use \Nether\Senpai\Struct\NamespaceObject;
use \Nether\Senpai\Struct\ClassObject;
use \Nether\Senpai\Struct\FunctionObject;
use \Nether\Object\Datastore;

class Builder {

	protected
	$Config = NULL;

	public function
	GetConfig():
	Nether\Senpai\Config {

		if(!($this->Config instanceof Nether\Senpai\Config))
		$this->Config = new Nether\Senpai\Config;

		return $this->Config;
	}

	public function
	SetConfig(Nether\Senpai\Config $Config):
	self {
		$this->Config = $Config;
		return $this;
	}


	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected
	$Root = NULL;

	private
	$Parser = NULL;

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected
	$Files = [];

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Run():
	self {

		foreach($this->Config->GetPaths() as $Iter => $Path) {
			if(is_dir($Path))
			$this->ScanDirectory($Path);

			elseif(is_file($Path))
			$this->ScanFile($Path);
		}

		$this->Root = new Struct\NamespaceObject;
		$this->Root->SetName('\\');
		$this->Parser = (new ParserFactory)->Create(ParserFactory::PREFER_PHP7);

		foreach($this->Files as $Filename)
		$this->ParseFile($Filename);

		print_r($this->Root);

		return $this;
	}

	public function
	ScanDirectory(String $Path):
	self {

		$Iterator = Nether\Senpai\FileExtensionFilter::GetFromDirectory(
			$Path,
			$this->Config->GetExtensions()
		);

		foreach($Iterator as $Current)
		$this->Files[] = $Current->GetPathname();

		return $this;
	}

	public function
	ScanFile(String $Path):
	self {

		$File = new SplFileInfo($Path);

		if($this->Config->GetExtensions()->HasValue($File->GetExtension()) !== FALSE)
		$this->Files[] = $Path;

		return $this;
	}

	public function
	ParseFile(String $Filename):
	self {

		echo "parsing {$Filename}...", PHP_EOL;

		$Nodes = $this->Parser->Parse(file_get_contents($Filename));

		foreach($Nodes as $Node) {
			if($Node instanceof PhpParser\Node\Stmt\Namespace_)
			$this->ParseNamespace($Node);
		}

		return $this;
	}

	protected function
	ParseNamespace(PhpParser\Node\Stmt\Namespace_ $Namespace):
	self {

		$Struct = NamespaceObject::FromPhpParser($Namespace);
		echo "Found Namespace: {$Struct->GetName()}", PHP_EOL;

		// setup any missing namespaces and insert this one into the top
		// most of its stack.

		$Current = $this->BuildNamespaces($Struct->GetNamespaceChunked());
		$Spaces = $Current->GetNamespaces();

		if($Spaces->HasKey($Struct->GetNameShort())) {
			$Current->GetClasses()->MergeRight($Struct->GetClasses()->GetData());
			$Current->GetFunctions()->MergeRight($Struct->GetFunctions()->GetData());
		} else {
			$Spaces->Shove($Struct->GetNameShort(),$Struct);
		}

		return $this;
	}

	protected function
	BuildNamespaces(Array $Tree):
	NamespaceObject {

		$SpaceName = '\\';
		$Level = $this->Root;
		foreach($Tree as $StepName) {
			$SpaceName = trim("{$SpaceName}\\{$StepName}",'\\');

			if(!$Level->GetNamespaces()->HasKey($StepName)) {
				$New = new Nether\Senpai\Struct\NamespaceObject;
				$New->SetName($SpaceName);

				$Level->GetNamespaces()->Shove($StepName,$New);
				$Level = $New;
			}

			else {
				$Level = $Level->GetNamespaces()->Get($StepName);
			}
		}

		return $Level;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	static public function
	GetFromFile(String $Filename):
	self {

		$Builder = new static;
		$Builder->SetConfig(Nether\Senpai\Config::GetFromFile($Filename));

		return $Builder;
	}

}
