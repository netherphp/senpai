<?php

namespace Nether\Senpai\Traits;

trait ClassArrayProperty {

	protected
	$Classes = [];

	public function
	GetClasses():
	Array {

		return $this->Classes;
	}

	public function
	SetClasses(Array $Input):
	self {

		$this->Classes = $Input;
		return $this;
	}

}
