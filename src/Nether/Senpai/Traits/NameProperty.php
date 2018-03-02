<?php

namespace Nether\Senpai\Traits;

trait NameProperty {

	protected
	$Name = '';

	public function
	GetName():
	String {

		return $this->Name;
	}

	public function
	SetName(String $Input):
	self {

		$this->Name = $Input;
		return $this;
	}

}