<?php

namespace Nether\Senpai\Traits;
/*//
this is a comment that describes the namespace.
//*/

trait FileProperty {


	protected
	$File = NULL;

	public function
	GetFile() {
	/*//
	@get File
	//*/

		return $this->File;
	}

	public function
	SetFile($Input):
	self {
	/*//
	@set File
	//*/

		$this->File = $Input;
		return $this;
	}

}