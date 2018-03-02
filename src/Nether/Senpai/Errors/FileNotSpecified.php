<?php

namespace Nether\Senpai\Errors;

use \Exception;

class FileNotSpecified
extends Exception {

	public function
	__construct() {
		parent::__construct('No file was specified.');
		return;
	}

}
