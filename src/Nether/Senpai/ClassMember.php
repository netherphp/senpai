<?php

namespace Nether\Senpai;

class ClassMember extends CodeBlock {

	public $Class;

	public function __construct($class,$reflector) {
		$this->Class = $class;

		parent::__construct($reflector);
		return;
	}

	public function DetermineMemberTags() {
		$r = $this->Reflector;

		// determine sane access tags.
		if($r->isStatic()) {
			$this->AddTag('static')->AddTag('access','static');
		} else {
			if($r->isPublic()) $this->AddTag('public')->AddTag('access','public');
			if($r->isProtected()) $this->AddTag('protected')->AddTag('access','protected');
			if($r->isPrivate()) $this->AddTag('private')->AddTag('access','private');
		}

		// determine if member is from a trait.
		if($trait = $r->getDeclaringTraitName())
		$this->AddTag('trait',$trait);

		if($r->getDeclaringClassName() !== $this->Class->Name) {
			if(!$this->HasTag('trait'))
			$this->AddTag('inherited',$r->getDeclaringClassName());
		}

		return;
	}

}
