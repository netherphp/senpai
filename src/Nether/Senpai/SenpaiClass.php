<?php

namespace Nether\Senpai;

class SenpaiClass extends CodeBlock {

	public $Members = [];
	/*//
	@type array
	a list of all the properties and methods in this class.
	//*/

	protected function Examine() {
	/*//
	@override
	//*/

		foreach($this->Reflector->getProperties() as $property) {
			if($property->getDeclaringClass()->getName() == $this->Reflector->getName())
			$this->Members[] = new SenpaiProperty($property);
		}

		foreach($this->Reflector->getMethods() as $method) {
			if($method->getDeclaringClass()->getName() == $this->Reflector->getName())
			$this->Members[] = new SenpaiMethod($method);
		}

		return;
	}

	protected function ExamineTags() {
	/*//
	@override
	//*/

		if(!$this->Info)
		$this->Info = 'This class has no description set.';

		return;
	}

	////////////////
	////////////////

	public function GetMembers($filter,$bitmask=false) {
	/*//
	@argv int FlagFilter, bool UseAsBitmask
	fetch class members fitting the filter, a bitmask of constants from the
	ClassMember class.
	//*/

		$output = [];
		foreach($this->Members as $member) {
			if($bitmask) {
				if($member->Flags & $filter)
				$output[$member->Name] = $member;
			} else {
				if($member->Flags == $filter)
				$output[$member->Name] = $member;
			}
		}

		ksort($output);
		return $output;
	}

	public function FilterMembers($is,$not) {
	/*//
	@argv int Is, int Not
	//*/

		$output = [];
		foreach($this->Members as $m) {
			if(($m->Flags & $is) && !($m->Flags & $not) && !array_key_exists('skipdoc',$m->Tags))
			$output[$m->Name] = $m;
		}

		ksort($output);
		return $output;
	}

}
