<?php

namespace Nether\Senpai;

class SenpaiMethod extends ClassMember {

	public function Examine() {
		$r = $this->Reflector;
		$this->DetermineMemberTags();
		return;
	}

	public function ExamineTags() {

		if(!$this->Info)
		$this->Info = 'This method has no description.';

		if(!$this->HasTag('argv')) $this->AddTag('argv',['void']);
		if(!$this->HasTag('return')) $this->AddTag('return','void');

		return;
	}

}
