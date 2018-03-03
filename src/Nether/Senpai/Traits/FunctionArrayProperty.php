<?php

namespace Nether\Senpai\Traits;

trait FunctionArrayProperty {

	protected
	$Functions = [];

	public function
	GetFunctions():
	Array {

		return $this->Functions;
	}

	public function
	SetFunctions(Array $Input):
	self {

		$this->Functions = $Input;
		return $this;
	}

	public function
	MergeFunctions(Array $Input):
	self {

		$this->Functions = array_merge(
			$this->Functions,
			$Input
		);

		return $this;
	}

	public function
	SortFunctions():
	self {

		ksort($this->Functions);
		return $this;
	}

}
