<?php

namespace Nether\Senpai\Traits;

trait FlagsProperty {

	protected
	$Flags = 0;

	public function
	GetFlags():
	Int {

		return $this->Flags;
	}

	public function
	SetFlags(Int $Input):
	self {

		$this->Flags = $Flags;
		return $this;
	}

	public function
	EnableFlags(Int $Input):
	self {

		$this->Flags |= $Input;
		return $this;
	}

	public function
	DisableFlags(Int $Input):
	self {

		$this->Flags &= ~$Input;
		return $this;
	}

}
