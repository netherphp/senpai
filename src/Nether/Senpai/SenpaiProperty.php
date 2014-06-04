<?php

namespace Nether\Senpai;

class SenpaiProperty extends ClassMember {

	public function Examine() {
		$r = $this->Reflector;
		$this->LocateSenpaiDoc();
		return;
	}

	public function ExamineTags() {
		$this->DetermineMemberTags();

		if(!$this->Info)
		$this->Info = 'This property has no description.';

		if(!$this->HasTag('type')) $this->AddTag('type','void');

		return;
	}

	////////////////
	////////////////

	protected function LocateSenpaiDoc() {
	/*//
	since senpai documents are contained inside the block they are defined in
	and properties have no blocks, we have to zip to the end of the property
	and see if there is one starting right after it. if we find one we update
	the line number the property ends on for extraction later.
	//*/

		$fp = fopen($this->File,'r');

		// scroll to where the property ends.
		for($num = 1; $num <= $this->LineEnd; $num++)
		fgets($fp);

		// see if we have a senpai docblock there.
		if(preg_match('/^\/\*\/\//',trim(fgets($fp)))) {
			while(!preg_match('/\/\/\*\/$/',fgets($fp)))
			++$num;

			$this->LineEnd = $num + 1;
		}

		fclose($fp);
		return;
	}

}
