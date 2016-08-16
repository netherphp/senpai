<?php

namespace Nether\Senpai\Struct;
use \Nether;
use \PhpParser;

use \PhpParser\Node\Stmt\Class_ as PhpParserClass;
use \Nether\Object\Datastore;
use \Nether\Senpai\Struct\NamespaceObject;

class ClassObject
extends Nether\Senpai\Struct {

	protected
	$Constants;

	public function
	GetConstants():
	Datastore {
		return $this->Constants;
	}

	protected
	$Methods;

	public function
	GetMethods():
	Datastore {
		return $this->Methods;
	}

	protected
	$Properties;

	public function
	GetProperties():
	Datastore {
		return $this->Properties;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	__construct() {

		$this->Constants = new Datastore;
		$this->Methods = new Datastore;
		$this->Properties = new Datastore;

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	static public function
	FromPhpParser(PhpParserClass $Node, ?NamespaceObject $Namespace=NULL):
	self {

		$Struct = new static;
		$Name = $Node->name;

		if($Namespace)
		$Name = "{$Namespace->GetName()}\\{$Name}";

		$Struct
		->SetName($Name)
		->SetParent($Namespace);

		return $Struct;
	}

}
