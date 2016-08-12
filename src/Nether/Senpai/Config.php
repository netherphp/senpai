<?php

namespace Nether\Senpai;
use \Nether;

use \JsonSerializable;
use \Exception;

class Config
implements JsonSerializable {

	protected
	$Paths = [ ];

	protected
	$Extensions = [ 'php' ];

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

	// JsonSerializable Interface

	public function
	JsonSerialize():
	Array {

		return [
			'Paths'      => $this->Paths,
			'Extensions' => $this->Extensions
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

}
