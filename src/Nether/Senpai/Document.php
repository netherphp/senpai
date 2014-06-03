<?php

namespace Nether\Senpai;

class Document {

	public $Tags = [];
	public $Text = '';

	static $Multitags = ['argv'];
	/*//
	@type array
	these are the tags that a document could contain that we should accept
	having multiples of and should therefore return an array. any tags not
	listed here will overwrite eachother if redeclared in the same document
	later on.
	//*/

	////////////////
	////////////////

	public function __construct($lines) {

		foreach($lines as $num => $line) {
			$this->ParseLine(trim($line));
		}

		$this->Text = trim($this->Text);

		return;
	}

	////////////////
	////////////////

	public function ParseLine($line) {

		if(!preg_match('/^@([^\s]+)(?: (.+?))?$/',$line,$m)) {

			// try to join paragraphs onto single lines while preserving other
			// line breaks for formatting markdown bullets and stuff.

			if(preg_match('/^[a-z0-9]/i',$line)) {
				$this->Text .= " {$line}";
			} else {
				if(!$line) $line = "\n";
				$this->Text .= "\n{$line}";
			}

			// clean up line starts from the attempted join process.
			$this->Text = preg_replace('/^ /ms','',$this->Text);

		} else {
			if(in_array($m[1],static::$Multitags)) {
				if(!array_key_exists($m[1],$this->Tags))
				$this->Tags[$m[1]] = array();

				if(array_key_exists(2,$m)) $this->Tags[$m[1]][] = $m[2];
				else $this->Tags[$m[1]][] = true;
			} else {
				if(array_key_exists(2,$m)) $this->Tags[$m[1]] = $m[2];
				else $this->Tags[$m[1]] = true;
			}

		}
	}

	////////////////
	////////////////

	static function NewFromSource($lines) {
		if(is_string($lines)) $lines = explode("\n",$text);

		if(count($lines) < 3)
		return false;

		$num = 1;
		if(preg_match('/^\/\*\/\//',trim($lines[$num]))) {
			while(!preg_match('/\/\/\*\/$/',trim($lines[$num]))) ++$num;
			return new self(array_slice($lines,2,($num-2)));
		} else {
			return false;
		}

	}

}
