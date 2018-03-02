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

	public function
	MergeTraits(Array $Input):
	self {

		$this->Traits = array_merge(
			$this->Traits,
			$Input
		);

		return $this;
	}

	public function
	SortTraits():
	self {

		ksort($this->Traits);
		return $this;
	}

}
