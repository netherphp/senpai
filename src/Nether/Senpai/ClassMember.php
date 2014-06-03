<?php

namespace Nether\Senpai;

class ClassMember extends CodeBlock {

	const IsPublic    = 0b1;
	const IsProtected = 0b10;
	const IsPrivate   = 0b100;
	const IsStatic    = 0b1000;
	const IsProperty  = 0b10000;
	const IsMethod    = 0b100000;
	const IsAbstract  = 0b1000000;
	const AllFlags    = 0b1111111;

	////////////////
	////////////////

	public $Flags = 0;
	/*//
	@type int
	all the type flags that a member could have that we will use for filtering
	later on.
	//*/

	protected function PopulateFlags() {
	/*//
	populate the flags from php's reflection.
	//*/

		if($this->Reflector->isPublic()) $this->Flags += static::IsPublic;
		if($this->Reflector->isProtected()) $this->Flags += static::IsProtected;
		if($this->Reflector->isPrivate()) $this->Flags += static::IsPrivate;
		if($this->Reflector->isStatic()) $this->Flags += static::IsStatic;

		if(property_exists($this->Reflector,'isAbstract'))
		if($this->Reflector->isAbstract())
		$this->Flags += static::IsAbstract;

		if(get_class($this->Reflector) == 'ReflectionProperty')
		$this->Flags += static::IsProperty;

		if(get_class($this->Reflector) == 'ReflectionMethod')
		$this->Flags += static::IsMethod;

		return;
	}

}
