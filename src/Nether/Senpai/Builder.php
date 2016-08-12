<?php

namespace Nether\Senpai;
use \Nether;

use \SplFileInfo;

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
	$Files = [];

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Run():
	self {

		foreach($this->Config->GetPaths() as $Iter => $Path) {
			if(is_dir($Path))
			$this->ScanDirectory($Path);

			else
			$this->ScanFile($Path);
		}

		echo 'Here are the files we decided to read:', PHP_EOL;
		print_r($this->Files);

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
	?Nether\Senpai\Struct {

		return NULL;
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
