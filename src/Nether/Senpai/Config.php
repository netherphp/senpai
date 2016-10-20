<?php

namespace Nether\Senpai;
use \Nether;

use \JsonSerializable;
use \Exception;

class Config
implements JsonSerializable {

	////////////////////////////////////////////////////////////////
	// properties from the config file /////////////////////////////

	protected
	$Paths = [ ];

	public function
	GetPaths():
	Nether\Object\Datastore {
		return $this->Paths;
	}

	protected
	$Extensions = [ 'php' ];

	public function
	GetExtensions():
	Nether\Object\Datastore {
		return $this->Extensions;
	}

	protected
	$Formats = [ 'html','json','md' ];

	public function
	GetFormats():
	Nether\Object\Datastore {
		return $this->Formats;
	}

	protected
	$OutputFile = NULL;

	public function
	GetOutputFile():
	?String {

		return $this->OutputFile;
	}

	public function
	SetOutputFile(String $Name):
	self {

		$this->OutputFile = $Name;
		return $this;
	}

	protected
	$OutputDir = NULL;

	public function
	GetOutputDir():
	?String {

		return $this->OutputDir;
	}

	public function
	SetOutputDir(String $Dir):
	self {

		$this->OutputDir = $Dir;
		return $this;
	}

	protected
	$ProjectName = NULL;

	public function
	GetProjectName():
	?String {

		return $this->ProjectName;
	}

	public function
	SetProjectName(?String $Name):
	self {

		$this->ProjectName = $Name;
		return $this;
	}

	////////////////////////////////////////////////////////////////
	// properties about the config file ////////////////////////////

	protected
	$Filename = NULL;

	public function
	GetFilename():
	?String {

		return $this->Filename;
	}

	public function
	SetFilename(String $Name):
	self {
		$this->Filename = $Name;
		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	__construct($Input=NULL) {

		// properties to create datastores on.
		$StoreKeys = [
			'Paths', 'Extensions',
			'Formats'
		];

		// properties to merge input data from.
		$CopyKeys = [
			'Paths', 'Extensions',
			'OutputDir', 'OutputFile', 'Formats'
		];

		////////

		$this->ProjectName = 'Senpai Documentation';
		$this->OutputFile = 'senpai.dat';
		$this->OutputDir = 'docs';

		foreach($StoreKeys as $Key)
		$this->{$Key} = (new Nether\Object\Datastore)->SetData($this->{$Key});

		////////

		if(is_object($Input))
		foreach($CopyKeys as $Key)
		if(property_exists($Input,$Key)) {
			if(is_array($Input->{$Key}))
			$this->{$Key}->SetData($Input->{$Key});

			elseif(is_string($Input->{$Key}))
			$this->{$Key} = $Input->{$Key};
		}

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	// JsonSerializable Interface

	public function
	JsonSerialize():
	Array {

		return [
			'ProjectName' => $this->ProjectName,
			'OutputFile'  => $this->OutputFile,
			'OutputDir'   => $this->OutputDir,
			'Formats'     => $this->Formats->GetData(),
			'Paths'       => $this->Paths->Reindex()->GetData(),
			'Extensions'  => $this->Extensions->Reindex()->GetData()
		];
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Write(?String $Filename=NULL):
	self {

		if(!$Filename)
		$Filename = $this->GetFilename();

		if(!$Filename)
		throw new Exeption('no filename given');

		$File = basename($Filename);
		$Path = dirname($Filename);
		$JsonOpt = JSON_PRETTY_PRINT;

		////////

		if(file_exists($Filename) && !is_writable($Filename))
		throw new Exception('unable to overwrite file');

		elseif(!file_exists($Filename)) {
			if(!is_dir($Path) && !@mkdir($Path,0777,TRUE))
			throw new Exception('unable to create directory');

			if(is_dir($Path) && !is_writable($Path))
			throw new Exception('unable to write to directory');
		}

		////////

		if(file_put_contents($Filename,json_encode($this,$JsonOpt)) === FALSE)
		throw new Exception('last second wtf attempting to write file');

		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	static public function
	GetFromFile(String $Filename):
	self {

		if(!is_readable($Filename))
		throw new Exception('unable to read file');

		$Data = json_decode(file_get_contents($Filename));

		if(!is_object($Data))
		throw new Exception('error parsing file apparently');

		return (new static($Data))
		->SetFilename($Filename);
	}

}
