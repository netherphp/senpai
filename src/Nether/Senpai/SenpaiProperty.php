<?php

namespace Nether\Senpai;

class SenpaiProperty extends ClassMember {

	public function Examine() {
		$this->PopulateFlags();

		// so this bit of bullshit here is because of a lack of planning
		// internally of php.
		$class = $this->Reflector->getDeclaringClass();
		$this->File = $class->getFilename();
		$this->LineStart = $class->getStartLine();
		$this->LineStop = $class->getEndLine();
		$this->FindThisPropertyDamnit();

		return;
	}

	public function ExamineTags() {

		if(!$this->Info)
		$this->Info = 'This property has no description.';

		if(!array_key_exists('type',$this->Tags))
		$this->Tags['type'] = 'void';

		return;
	}

	protected function FindThisPropertyDamnit() {
	/*//
	because ReflectionProperty doesn't have the nice methods like
	ReflectionClass and ReflectionMethods have... arses. seriously, wtf. yall
	are tanking my zen over here.
	//*/

		$filedata = $this->ExtractFromFile();

		$pattern = "/^(?:[^\s]+ )?(?:[^\s]+)[\s\t]+\\\${$this->Name}/";
		// static access $name;

		foreach($filedata as $num => $line) {
			// echo "{$num}: {$line}", PHP_EOL;
			if(preg_match($pattern,trim($line))) {
				$this->LineStart += $num;
				break;
			}
		}

		// include the senpai docblock as part of the property, since it is part
		// of the classes and methods.
		$endoffset = 1;
		if(preg_match('/^\/\*\/\//',trim($filedata[++$num]))) {
			while(!preg_match('/\/\/\*\/$/',trim($filedata[$num]))) {
				// echo "{$num}: {$line}", PHP_EOL;
				++$num; ++$endoffset;
			}

			$this->LineStop = $this->LineStart + $endoffset;
		} else {
			$this->LineStop = $this->LineStart;
		}

		return;
	}

}
