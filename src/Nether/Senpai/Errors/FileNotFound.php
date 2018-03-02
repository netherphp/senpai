<?php

namespace Nether\Senpai\Errors;

use \Exception;

class FileNotFound
extends Exception {

	public function
	__construct(String $Filename) {
		parent::__construct("{$Filename} was not found.");
		return;
	}

}
