<?php

namespace Nether\Senpai\Struct;
use \Nether;
use \PhpParser;

use \PhpParser\Node\Stmt\Class_ as PhpParserClass;
use \PhpParser\Node\Stmt\ClassMethod as PhpParserMethod;
use \PhpParser\Node\Stmt\Property as PhpParserProperty;
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

		foreach($Node->stmts as $Child) {
			if($Child instanceof PhpParserMethod) {
				$Method = MethodObject::FromPhpParser($Child, $Struct);
				$Struct->GetMethods()->Shove(
					$Method->GetName(),
					$Method
				);
			}

			elseif($Child instanceof PhpParserProperty) {
				$Property = PropertyObject::FromPhpParser($Child->props[0], $Struct);
				$Struct->GetProperties()->Shove(
					$Property->GetName(),
					$Property
				);
			}
		}

		return $Struct;
	}

}
