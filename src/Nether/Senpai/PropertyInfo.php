<?php

namespace Nether\Senpai;

use \Nether;
use \Reflection;
use \ReflectionProperty as Property;

class PropertyInfo extends Info {

	public $File;
	public $LineStart;
	public $LineEnd;

	public $Access;
	public $Static;

	public $Type = 'void';

	public function __construct($senpai,$reflect) {
		parent::__construct($senpai,$reflect);

		$this->FindPropertyInFile($reflect);
		// because ReflectionProperty doesn't have the nice methods like
		// ReflectionClass and ReflectionMethods have... arses.

		$this->Docblock = Docblock::NewFromSource($this->GetFileSlice(
			$this->File,
			$this->LineStart,
			$this->LineEnd
		));

		if($this->Docblock) {
			if(array_key_exists('type',$this->Docblock->Tags))
			$this->Type = $this->Docblock->Tags['type'][0];
		}

		$mods = $reflect->getModifiers();
		$this->Access = Reflection::getModifierNames(($mods & (Property::IS_PUBLIC|Property::IS_PROTECTED|Property::IS_PRIVATE)))[0];
		$this->Static = ((($mods&Property::IS_STATIC))?(true):(false));

		return;
	}

	protected function FindPropertyInFile($reflect) {
		$class = $reflect->getDeclaringClass();

		$this->File = $class->getFileName();
		$filedata = $this->GetFileSlice(
			$this->File,
			$class->getStartLine(),
			$class->getEndLine()
		);

		// find what line the property is declared on.
		foreach($filedata as $num => $line) {
			if(preg_match(
				"/^(?:[^\s]+ )?(?:[^\s]+)[\s\t]+\\\${$this->Name}/"
				,trim($line)
			)) {
				$this->LineStart = $class->getStartLine() + $num;
				break;
			}
		}

		// include the senpai docblock as part of the property, since it is part
		// of the classes and methods.
		if(preg_match(
			'/^\/\*\/\//',
			trim($filedata[++$num])
		)) {
			while(!preg_match(
				'/\/\/\*\/$/',
				trim($filedata[$num])
			)) {
				++$num;
			}

			$this->LineEnd = $class->getStartLine() + $num;
		} else {
			$this->LineEnd = $this->LineStart;
		}

		return;
	}

	public function ToMarkdown() {
		ob_start();

		printf(
			'%s %s %s;%s',
			(($this->Static)?("static {$this->Access}"):($this->Access)),
			$this->Type,
			$this->Name,
			str_repeat(PHP_EOL,2)
		);

		if($this->Docblock && $this->Docblock->Text) {
			$text = wordwrap($this->Docblock->Text,70);
			$text = preg_replace('/^/ms',"\t",$text);
			echo $text, PHP_EOL, PHP_EOL;
		} else {
			echo "\tThis property has no description.", PHP_EOL, PHP_EOL;
		}

		return ob_get_clean();
	}

}
