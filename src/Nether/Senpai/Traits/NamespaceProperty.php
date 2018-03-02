<?php

namespace Nether\Senpai\Traits;

trait NamespaceProperty {

	protected
	$Namespace = NULL;

	public function
	GetNamespace() {

		return $this->Namespace;
	}

	public function
	SetNamespace($Input):
	self {

		$this->Namespace = $Input;
		return $this;
	}

}