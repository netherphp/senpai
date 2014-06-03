<?php

namespace Nether\Senpai;

use \Nether;

class Info {

	public $Name;
	public $Docblock;

	////////////////
	////////////////

	public function __construct($senpai,$reflect) {
		$this->Name = $reflect->getName();
		return;
	}

	public function __toString() {
		return sprintf(
			'%s %s',
			strtolower(str_replace('Info','',basename(static::class))),
			$this->Name
		);
	}

	public function __get($key) {
		switch($key) {
			case 'Description': {
				if($this->Docblock && $this->Docblock->Text) return $this->Docblock->Text;
				else return "This entity has no description set.";
			}
		}

		return false;
	}

	////////////////
	////////////////

	public function GetFileSlice($file,$start,$end) {
	/*//
	@argv string Filename, int StartLine, int NumLines
	@return array
	fetch a slice of a file.
	//*/
		return array_slice(
			file($file,FILE_IGNORE_NEW_LINES),
			($start-1),
			($end-($start-1))
		);
	}

}
