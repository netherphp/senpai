<?php

namespace Nether\Senpai\Traits;

trait FilenameProperty {

	protected
	$FilenameProperty = '';

	public function
	GetFilename():
	String {

		return $this->Filename;
	}

	public function
	SetFilename(String $Input):
	self {

		$this->Filename = $Input;
		return $this;
	}

}