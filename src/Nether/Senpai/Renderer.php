<?php

namespace Nether\Senpai;
use \Nether;

use \Exception                            as Exception;
use \Nether\Senpai\Struct\NamespaceObject as NamespaceObject;

class Renderer {

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

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Run():
	self {

		$this
		->Bootstrap()
		->LoadOutputFile();

		foreach($this->GetConfig()->GetFormats() as $Format)
		switch(strtolower($Format)) {
			case 'html': {
				$this->RenderHtmlVersion();
				break;
			}
		}

		return $this;
	}

	protected function
	Bootstrap():
	self {

		// check that we have a place to dump our output.

		$OutputDir = $this->GetConfig()->GetOutputDir();

		if(!$OutputDir)
		throw new Exception("No OutputDir [{$OutputDir}] specified.");

		if(!is_dir($OutputDir) && !@mkdir($OutputDir,0777,TRUE))
		throw new Exception("Unable to create OutputDir [{$OutputDir}]");

		// check if we have compiled data, and can use it, or if we should
		// automatically kick off the build process as well.

		$OutputFile = $this->GetConfig()->GetOutputFile();

		if(!$OutputFile)
		throw new Exception('No OuputFile defined by project.');

		if(file_exists($OutputFile) && !is_readable($OutputFile))
		throw new Exception('Unable to read OutputFile');

		if(!file_exists($OutputFile)) {
			$Builder = Builder::GetFromFile($this->GetConfig()->GetFilename());
			$Builder->Run();
		}

		return $this;
	}

	protected function
	LoadOutputFile():
	self {

		$OutputFile = $this->GetConfig()->GetOutputFile();
		$Raw = NULL;
		$Data = NULL;

		if(!($Raw = file_get_contents($OutputFile)))
		throw new Exception('No data found withing OutputFile.');

		if(!(($Data = unserialize($Raw)) instanceof NamespaceObject))
		throw new Exception('Error parsing OutputFile');

		$this->Root = $Data;

		return $this;
	}

	protected function
	RenderHtmlVersion():
	self {

		$Printer = NULL;
		$OutputDir = $this->GetConfig()->GetOutputDir();

		$Printer = function(Nether\Senpai\Struct $Struct, Int $Level=0)
		use(&$Printer,$OutputDir) {

			if($Struct instanceof NamespaceObject) {
				$IndexFile = trim(sprintf(
					'%s%s%sindex.html',
					DIRECTORY_SEPARATOR,
					$Struct->GetName(),
					DIRECTORY_SEPARATOR
				),'\\');

				$IndexFile = sprintf(
					'%s%s%s',
					$OutputDir,
					DIRECTORY_SEPARATOR,
					$IndexFile
				);

				$IndexDir = dirname($IndexFile);

				// make sure a directory exists for it.

				if(!is_dir($IndexDir))
				@mkdir($IndexDir,0777,TRUE);

				// make sure a file exists for it.

				touch($IndexFile);

				////////

				$Surface = new Nether\Surface([
					'AutoStash'   => FALSE,
					'AutoRender'  => FALSE,
					'AutoCapture' => FALSE,
					'ThemeRoot'   => sprintf(
						'%s%sthemes',
						dirname(__FILE__,4),
						DIRECTORY_SEPARATOR
					)
				]);

				$Surface->Start();
				$Surface->Set('Root',$this->Root);
				$Surface->Set('Namespace',$Struct);
				echo $Surface->GetArea('code/namespace');

				file_put_contents(
					$IndexFile,
					$Surface->Render(TRUE)
				);

				unset($Surface);

				foreach($Struct->GetNamespaces() as $Struck)
				$Printer($Struck,($Level + 1));
			}

		};

		$Printer($this->Root);

		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	static public function
	GetFromFile(String $Filename):
	self {

		$Renderer = new static;
		$Renderer->SetConfig(Nether\Senpai\Config::GetFromFile($Filename));

		return $Renderer;
	}


}
