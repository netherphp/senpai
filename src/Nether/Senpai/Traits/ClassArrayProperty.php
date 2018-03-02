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

	public function
	MergeClasses(Array $Input):
	self {

		$this->Classes = array_merge(
			$this->Classes,
			$Input
		);

		return $this;
	}

	public function
	SortClasses():
	self {

		ksort($this->Classes);
		return $this;
	}

}
