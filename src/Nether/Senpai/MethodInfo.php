<?php

namespace Nether\Senpai;

use \Nether;
use \Reflection;
use \ReflectionMethod as Method;

class MethodInfo extends Info {

	public $File;
	public $LineStart;
	public $LineEnd;

	public $Access;
	public $Static;
	public $ReturnType = 'void';
	public $Args = ['void'];

	public $Class;

	public function __construct($senpai,$reflect) {
		parent::__construct($senpai,$reflect);

		$this->File = $reflect->getFileName();
		$this->LineStart = $reflect->getStartLine();
		$this->LineEnd = $reflect->getEndLine();
		$this->Docblock = Docblock::NewFromSource($this->GetFileSlice(
			$this->File,
			$this->LineStart,
			$this->LineEnd
		));

		if($this->Docblock) {
			if(array_key_exists('return',$this->Docblock->Tags))
			$this->ReturnType = $this->Docblock->Tags['return'][0];

			if(array_key_exists('argv',$this->Docblock->Tags))
			$this->Args = $this->Docblock->Tags['argv'];
		}

		$mods = $reflect->getModifiers();
		$this->AccessInt = ($mods & (Method::IS_PUBLIC|Method::IS_PROTECTED|Method::IS_PRIVATE));
		$this->Access = Reflection::getModifierNames($this->AccessInt)[0];
		$this->Static = ((($mods&Method::IS_STATIC))?(true):(false));

		return;
	}

	public function ToMarkdown() {
		ob_start();

		foreach($this->Args as $argv) {
			printf(
				'%s %s %s(%s);%s',
				$this->Access,
				$this->ReturnType,
				$this->Name,
				$argv,
				str_repeat(PHP_EOL,2)
			);
		}

		if($this->Docblock && $this->Docblock->Text) {
			$text = wordwrap($this->Docblock->Text,70);
			$text = preg_replace('/^/ms',"\t",$text);
			echo $text, PHP_EOL, PHP_EOL;
		} else {
			echo "\tThis method has no description.", PHP_EOL, PHP_EOL;
		}

		return ob_get_clean();
	}

}
