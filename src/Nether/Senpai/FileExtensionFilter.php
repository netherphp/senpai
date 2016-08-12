<?php

namespace Nether\Senpai;
use \Nether;

use \FilterIterator;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \Nether\Object\Datastore;

class FileExtensionFilter
extends FilterIterator {

	public function
	__construct(RecursiveDirectoryIterator $Iterator, Datastore $Extensions) {
	/*//
	//*/

		parent::__construct(new RecursiveIteratorIterator($Iterator));

		$this->Extensions = $Extensions;
		return;
	}

	public function
	Accept():
	Bool {
	/*//
	accept files which have a valid extension.
	//*/

		if($this->IsDot() || $this->IsDir())
		return FALSE;

		if($this->Extensions->HasValue($this->GetExtension()) !== FALSE)
		return TRUE;
	}

	static public function
	GetFromDirectory(String $Path, Datastore $Extensions):
	self {
	/*//
	construct a file iterator for the specified directory path that filters by
	the specified file extensions.
	//*/

		return new static(
			(new RecursiveDirectoryIterator($Path)),
			$Extensions
		);
	}

}
