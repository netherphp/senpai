<?php

namespace Nether\Senpai;

use \Nether;

class Docblock {

	public $Tags = [];
	public $Text = '';

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

		} else {
			if(!array_key_exists($m[1],$this->Tags))
			$this->Tags[$m[1]] = array();

			$this->Tags[$m[1]][] = $m[2];
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
