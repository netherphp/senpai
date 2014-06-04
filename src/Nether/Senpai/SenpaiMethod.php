<?php

namespace Nether\Senpai;

class SenpaiMethod extends ClassMember {

	public function Examine() {
		$r = $this->Reflector;
		return;
	}

	public function ExamineTags() {
		$this->DetermineMemberTags();

		if(!$this->Info)
		$this->Info = 'This method has no description.';

		if(!$this->HasTag('argv')) {
			$this->DetermineArguments();
		}

		if(!$this->HasTag('return')) $this->AddTag('return','void');

		return;
	}

	protected function DetermineArguments() {

		$argv = '';

		foreach($this->Reflector->getParameters() as $p) {
			$argv .= ", \${$p->getName()}";
		}

		if(!$argv) $argv = 'void';

		$this->AddTag('argv',[trim($argv,', ')]);
	}

}
