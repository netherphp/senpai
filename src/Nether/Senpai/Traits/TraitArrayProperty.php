<?php

namespace Nether\Senpai\Traits;

trait TraitArrayProperty {

	protected
	$Traits = [];

	public function
	GetTraits():
	Array {

		return $this->Traits;
	}

	public function
	SetTraits(Array $Input):
	self {

		$this->Traits = $Input;
		return $this;
	}

}
