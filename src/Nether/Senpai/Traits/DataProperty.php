<?php

namespace Nether\Senpai\Traits;
/*//
this is a comment that describes the namespace.
//*/

trait DataProperty {
/*//
this property is designed to be used for generic data of any type that
an object may need to store temporarily.
//*/

	protected
	$Data = NULL;

	public function
	GetData() {
	/*//
	@get Data
	//*/

		return $this->Data;
	}

	public function
	SetData($Input):
	self {
	/*//
	@set Data
	//*/

		$this->Data = $Input;
		return $this;
	}

}