<?php

namespace Nether\Senpai\Struct;
use \Nether;
use \PhpParser;

use \Nether\Object\Datastore;

class NamespaceObject
extends Nether\Senpai\Struct {

	protected
	$Namespaces;

	public function
	GetNamespaces():
	Datastore {
		return $this->Namespaces;
	}

	protected
	$Classes;

	public function
	GetClasses():
	Datastore {
		return $this->Classes;
	}

	protected
	$Functions;

	public function
	GetFunctions():
	Datastore {
		return $this->Functions;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	__construct() {

		$this->Namespaces = new Datastore;
		$this->Classes = new Datastore;
		$this->Functions = new Datastore;

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	static public function
	FromPhpParser(PhpParser\Node\Stmt\Namespace_ $Node):
	self {

		$Name = $Node->name->parts;

		$Struct = (new static)
		->SetName(implode('\\',$Name));

		return $Struct;
	}

}
