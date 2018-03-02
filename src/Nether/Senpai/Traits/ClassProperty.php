<?php

namespace Nether\Senpai\Traits;

trait ClassProperty {

	protected
	$Class = NULL;

	public function
	GetClass() {

		return $this->Class;
	}

	public function
	SetClass($Input):
	self {

		$this->Class = $Input;
		return $this;
	}

}