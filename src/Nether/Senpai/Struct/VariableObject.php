<?php

namespace Nether\Senpai\Struct;
use \Nether;
use \PhpParser;

class VariableObject
extends Nether\Senpai\Struct {

	static public function
	FromPhpParser(PhpParser\Node\Stmt $Node, ?Nether\Senpai\Struct $Parent=NULL):
	self {

		$Struct = new static;
		$Name = $Node->name;

		$Struct->SetName($Name);

		if($Parent)
		$Struct->SetParent($Parent);

		return $Struct;
	}

}
