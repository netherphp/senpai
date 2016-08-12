<?php

namespace Nether\Senpai;
use \Nether;

abstract class Struct {

	protected
	$Name = NULL;

	public function
	GetName():
	?String {
		return $this->Name;
	}

	public function
	SetName(String $Name):
	self {
		$this->Name = $Name;
		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected
	$Namespace = '\\';

	public function
	GetNamespace():
	String {
		return $this->Namespace;
	}

	public function
	SetNamespace(String $Namespace):
	self {
		$this->Namespace = $Namespace;
		return $this;
	}

}
