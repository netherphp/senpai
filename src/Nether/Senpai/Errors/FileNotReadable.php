<?php

namespace Nether\Senpai\Errors;

use \Exception;

class FileNotReadable
extends Exception {

	public function
	__construct(String $Filename) {
		parent::__construct("{$Filename} is not readable.");
		return;
	}

}
