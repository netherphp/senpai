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

	public function
	MergeMethods(Array $Input):
	self {

		$this->Methods = array_merge(
			$this->Methods,
			$Input
		);

		return $this;
	}

	public function
	SortMethods():
	self {

		ksort($this->Methods);
		return $this;
	}

}
