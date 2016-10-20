<?php

namespace Nether\Senpai\Struct;
use \Nether;

use \PhpParser\Node\Stmt\Namespace_ as PhpParserNamespace;
use \PhpParser\Node\Stmt\Class_ as PhpParserClass;
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

	public function
	GetFilenameHTML():
	String {

		$Prefix = array_pop($this->GetNameChunked());
		
		if($Prefix)
		$Prefix .= '/';

		return "{$Prefix}index.html";
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	static public function
	FromPhpParser(PhpParserNamespace $Node, ?NamespaceObject $Namespace=NULL):
	self {

		$Struct = new static;
		$Name = implode('\\',$Node->name->parts);

		$Struct
		->SetName($Name)
		->SetParent($Namespace);

		// find things within a namespace that we want to handle.

		foreach($Node->stmts as $Child) {

			// handle classes.
			if($Child instanceof PhpParserClass) {
				$Class = ClassObject::FromPhpParser($Child, $Struct);
				$Struct->GetClasses()->Shove(
					$Class->GetName(),
					$Class
				);
			}

		}

		return $Struct;
	}

}
