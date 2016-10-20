<?php

namespace Nether\Senpai;
use \Nether;

use \JsonSerializable;
use \Exception;

class Config
implements JsonSerializable {

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

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

	protected
	$OutputDir = NULL;

	public function
	GetOutputDir():
	?String {

		return $this->OutputDir;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

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
	SetOutputFile(String $Name):
	self {

		$this->OutputFile = $Name;
		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	__construct($Input=NULL) {

		$this->OutputFile = 'senpai.dat';
		$this->OutputDir = 'docs';
		$this->Paths = (new Nether\Object\Datastore)->SetData($this->Paths);
		$this->Extensions = (new Nether\Object\Datastore)->SetData($this->Extensions);
		$this->Formats = (new Nether\Object\Datastore)->SetData($this->Formats);

		if(!$Input || !is_object($Input))
		return;

		if(property_exists($Input,'Paths') && is_array($Input->Paths))
		$this->Paths->SetData($Input->Paths);

		if(property_exists($Input,'Extensions') && is_array($Input->Extensions))
		$this->Extensions->SetData($Input->Extensions);

		if(property_exists($Input,'Formats') && is_array($Input->Formats))
		$this->Formats->SetData($Input->Formats);

		if(property_exists($Input,'OutputFile') && is_string($Input->OutputFile))
		$this->OutputFile = $Input->OutputFile;

		if(property_exists($Input,'OutputDir') && is_string($Input->OutputDir))
		$this->OutputDir = $Input->OutputDir;

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	// JsonSerializable Interface

	public function
	JsonSerialize():
	Array {

		return [
			'OutputFile' => $this->OutputFile,
			'Paths'      => $this->Paths->Reindex()->GetData(),
			'Extensions' => $this->Extensions->Reindex()->GetData()
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
