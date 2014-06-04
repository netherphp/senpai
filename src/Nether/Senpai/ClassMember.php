<?php

namespace Nether\Senpai;

class ClassMember extends CodeBlock {

	protected function DetermineMemberTags() {
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

		return;
	}

}
