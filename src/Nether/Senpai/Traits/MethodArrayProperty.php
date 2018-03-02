<?php

namespace Nether\Senpai\Traits;

trait MethodArrayProperty {

	protected
	$Methods = [];

	public function
	GetMethods():
	Array {

		return $this->Methods;
	}

	public function
	SetMethods(Array $Input):
	self {

		$this->Methods = $Input;
		return $this;
	}

}
