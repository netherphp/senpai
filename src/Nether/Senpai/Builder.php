<?php

namespace Nether\Senpai;
use \Nether;
use \PhpParser;

use \SplFileInfo                          as SplFileInfo;
use \PhpParser\ParserFactory              as PhpParserFactory;
use \PhpParser\Node\Stmt\Namespace_       as PhpParserNamespace;
use \PhpParser\Node\Stmt\Class_           as PhpParserClass;
use \Nether\Senpai\Struct\NamespaceObject as NamespaceObject;
use \Nether\Senpai\Struct\ClassObject     as ClassObject;
use \Nether\Senpai\Struct\FunctionObject  as FunctionObject;
use \Nether\Object\Datastore              as Datastore;

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
	$FileInfo = [];
	/*//
	@type Array[Nether\Senpai\Struct\File]
	this will hold the list of files with their parsed attributes like what
	they contain, what they use, etc.
	//*/

	protected
	$Root = NULL;
	/*//
	this is the root namespace object that will hold the code tree.
	//*/

	private
	$Parser = NULL;

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected
	$Files = [];
	/*//
	@type Array[String]
	just a list of all the files we are going to parse.
	//*/

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
		$this->Parser = (new PhpParserFactory)->Create(PhpParserFactory::PREFER_PHP7);

		foreach($this->Files as $Filename)
		$this->ParseFile($Filename);

		////////
		////////

		// just a silly printer for debugging.

		$Printer = NULL;
		$Printer = function(Nether\Senpai\Struct $Struct, Int $Level=0) use(&$Printer) {

			if($Level) {
				if($Level > 1)
				echo str_repeat("  ",($Level - 1));

				echo "└ ";
			}

			printf(
				'%s %s%s',
				array_pop(explode('\\',get_class($Struct))),
				$Struct->GetName(),
				PHP_EOL
			);

			if($Struct instanceof NamespaceObject) {
				foreach($Struct->GetClasses() as $Cla)
				$Printer($Cla,($Level + 1));

				foreach($Struct->GetNamespaces() as $Nam)
				$Printer($Nam,($Level + 1));
			}

			if($Struct instanceof ClassObject) {
				foreach($Struct->GetProperties() as $Mth)
				$Printer($Mth,($Level + 1));

				foreach($Struct->GetMethods() as $Mth)
				$Printer($Mth,($Level + 1));
			}

		};
		$Printer($this->Root);

		////////
		////////

		return $this;
	}

	public function
	ScanDirectory(String $Path):
	self {

		echo "Scanning Directory: {$Path}", PHP_EOL;

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
	/*//
	handle reading a file and doing things with things we find in it.
	//*/

		echo "Parsing File: {$Filename}", PHP_EOL;

		$Nodes = $this->Parser->Parse(file_get_contents($Filename));

		foreach($Nodes as $Node) {
			if($Node instanceof PhpParserNamespace)
			$this->ParseNamespace($Node, $Filename);

			if($Node instanceof PhpParserClass)
			$this->ParseClass($Node, $Filename);
		}

		return $this;
	}

	protected function
	ParseNamespace(PhpParserNamespace $Namespace, String $Filename):
	self {
	/*//
	handle finding a namespace in this file.
	//*/

		// create a new namespace.
		$Struct = NamespaceObject::FromPhpParser($Namespace);
		$Struct->SetFilename($Filename);

		// build a branch for it to sit in if needed.
		$Current = $this->BuildNamespaces($Struct->GetNamespaceChunked());
		$Existing = $Current->GetNamespaces()->Get($Struct->GetNameShort());

		if($Existing) {
			// if this namespace had already existed because of another file
			// merge this namespace contents with the original.

			$Existing->GetClasses()
			->MergeRight($Struct->GetClasses()->GetData());

			$Existing->GetFunctions()
			->MergeRight($Struct->GetFunctions()->GetData());
		} else {
			// if it is a new namespace throw it in on the current level.

			$Struct->SetParent($Current);

			$Current->GetNamespaces()->Shove(
				$Struct->GetNameShort(),
				$Struct
			);
		}

		return $this;
	}

	protected function
	ParseClass(PhpParserClass $Node, String $Filename):
	self {
	/*//
	handle finding a class not within a namespace in this file.
	//*/

		$Struct = ClassObject::FromPhpParser($Node,$this->Root);
		$Struct->SetFilename($Filename);

		$this->Root->GetClasses()
		->Shove(
			$Struct->GetName(),
			$Struct
		);

		return $this;
	}

	protected function
	BuildNamespaces(Array $Tree):
	NamespaceObject {
	/*//
	starting from the root up, build a branch of namespaces (if needed) and
	return the furthest/deepest most one of them.
	//*/

		$SpaceName = '\\';
		$Level = $this->Root;
		foreach($Tree as $StepName) {
			$SpaceName = trim("{$SpaceName}\\{$StepName}",'\\');

			if(!$Level->GetNamespaces()->HasKey($StepName)) {
				$New = new NamespaceObject;
				$New->SetName($SpaceName);
				$New->SetParent($Level);

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
