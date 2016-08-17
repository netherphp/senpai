<?php

namespace Nether\Senpai\Struct;
use \Nether;

use \PhpParser\Node\Stmt\Namespace_ as PhpParserNamespace;
use \PhpParser\Node\Stmt\Class_ as PhpParserClass;
use \PhpParser\Node\Stmt\Function_ as PhpParserFunction;
use \Nether\Object\Datastore;

class FunctionObject
extends Nether\Senpai\Struct {

	public static function
	FromPhpParser($Node):
	self {

		$Struct = new static;
		$Name = $Node->name;

		$Struct->SetName($Name);

		return $Struct;
	}

}
