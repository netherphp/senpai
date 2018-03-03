<?php

namespace Nether\Senpai;

use \Nether as Nether;

class Annotation {

	use Traits\DataProperty;

	static public function
	FromString(String $Input):
	self {

		$Output = new static;

		$Output->SetData($Input);

		return $Output;
	}

}
