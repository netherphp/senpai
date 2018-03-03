<?php

namespace Nether\Senpai;

abstract class Statement {

	abstract public function
	GetFullName():	String;
	/*//
	this function should traverse its parents to generate the fully qualified
	name for this statement.
	//*/

}
