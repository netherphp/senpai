<?php

namespace Nether\Senpai;

class SenpaiMethod extends ClassMember {

	public function Examine() {
		$this->PopulateFlags();
		return;
	}

	public function ExamineTags() {

		if(!$this->Info)
		$this->Info = 'This method has no description.';

		if(!array_key_exists('argv',$this->Tags))
		$this->Tags['argv'] = ['void'];

		if(!array_key_exists('return',$this->Tags))
		$this->Tags['return'] = 'void';

		return;
	}

}
