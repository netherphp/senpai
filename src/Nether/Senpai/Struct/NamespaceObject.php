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

		$Struct = (new static)
		->SetName($Node->name->getFirst())
		->SetNamespace($Node->name->getLast());

		return $Struct;
	}

}
