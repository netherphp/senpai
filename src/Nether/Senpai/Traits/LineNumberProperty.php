<?php

namespace Nether\Senpai\Traits;

trait LineNumberProperty {

	protected
	$LineNumber = 0;

	public function
	GetLineNumber():
	Int {

		return $this->LineNumber;
	}

	public function
	SetLineNumber(Int $Input):
	self {

		$this->LineNumber = $Input;
		return $this;
	}

}