<?php

namespace Nether\Senpai;
use \Nether;

use \SplFileInfo;
use \PhpParser\ParserFactory;

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

		$this->Root = new Struct\NamespaceObject;
		$this->Parser = (new ParserFactory)->Create(ParserFactory::PREFER_PHP7);

		foreach($this->Config->GetPaths() as $Iter => $Path) {
			if(is_dir($Path))
			$this->ScanDirectory($Path);

			elseif(is_file($Path))
			$this->ScanFile($Path);
		}

		foreach($this->Files as $Filename)
		$this->ParseFile($Filename);

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

		if($this->Config->Extensions->HasValue($File->GetExtension()))
		$this->Files[] = $Path;

		return $this;
	}

	public function
	ParseFile(String $Filename):
	self {

		echo "parsing {$Filename}...", PHP_EOL;

		$Nodes = $this->Parser->Parse(file_get_contents($Filename));

		return $this;
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
