<?php

namespace Nether\Senpai;
use \Nether;

abstract class Struct {

	protected
	$Name = NULL;

	public function
	GetName():
	?String {
	/*//
	return the full name of this object as is.
	//*/

		return $this->Name;
	}

	public function
	SetName(String $Name):
	self {
	/*//
	give this object a name. it should be the full name including namespace.
	//*/

		$this->Name = $Name;
		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	GetNameShort():
	String {
	/*//
	@uses this::Name
	return the very short literal name of this object without its namespacing
	components.
	//*/

		return array_pop(explode('\\',$this->Name));
	}

	public function
	GetNamespaceName():
	String {
	/*//
	@uses this::Name
	return the namespace name that this object resides within.
	//*/

		$List = explode('\\',$this->Name);
		array_pop($List);

		return implode('\\',$List);
	}

	public function
	GetNameChunked():
	Array {
	/*//
	@uses this::Name
	return the full name of this object as a list.
	//*/

		return explode('\\',$this->Name);
	}

	public function
	GetNamespaceChunked():
	Array {
	/*//
	@uses this::Name
	return the namespace path as a list.
	//*/

		$List = explode('\\',$this->Name);
		array_pop($List);

		return $List;
	}

}
