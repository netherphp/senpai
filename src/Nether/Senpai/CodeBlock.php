<?php

namespace Nether\Senpai;

use \Nether;

class CodeBlock {

	public $Reflector;

	public $Name;
	public $File;
	public $LineBegin;
	public $LineEnd;
	public $Tags = [];
	public $Info;

	public $Infotags = [
		'deprecated','todo','override','undocumented',
		'trait','abstract','final','inherited'
	];

	////////////////
	////////////////

	public function __construct($reflector) {
		$this->Reflector = $reflector;

		$this->Name = $reflector->getName();
		$this->File = $reflector->getFileName();
		$this->LineBegin = $reflector->getStartLine();
		$this->LineEnd = $reflector->getEndLine();

		echo "[{$reflector->getPrettyName()}]", PHP_EOL;

		$this->Examine();
		$this->ReadSenpaiBlock();
		$this->ExamineTags();

		ksort($this->Tags);
		return;
	}

	////////////////
	////////////////

	public function Examine() {
	/*//
	@generic
	//*/

		return;
	}

	public function ExamineTags() {
	/*//
	@generic
	//*/

		return;
	}

	////////////////
	////////////////

	public function AddTag($name,$value=null) {
		if(!$value) $value = $name;

		$this->Tags[$name] = $value;

		return $this;
	}

	public function HasTag($name) {
		return array_key_exists($name,$this->Tags);
	}

	public function GetTag($name) {
		if(array_key_exists($name,$this->Tags)) return $this->Tags[$name];
		else return null;
	}

	public function GetInfoTags($more=[]) {

		$output = [];

		foreach(array_merge($this->Infotags,$more) as $name)
		if($this->HasTag($name))
		$output[$name] = $this->GetTag($name);

		return $output;
	}

	public function GetInfoTagString($join=' ',$more=[]) {
		return implode($join,array_keys($this->GetInfoTags($more)));
	}

	public function GetInfoParsed() {
		return \Michelf\MarkdownExtra::defaultTransform($this->Info);
	}

	////////////////
	////////////////

	public function ReadSenpaiBlock() {

		$doc = Document::NewFromSource($this->ExtractFromFile());

		$this->Info = $doc->Text;

		foreach($doc->Tags as $tag => $tval)
		$this->AddTag($tag,$tval);

		return;
	}

	////////////////
	////////////////

	public function ExtractFromFile() {
	/*//
	@argv int StartLine, int NumLines
	@return array
	fetch a slice of a file.
	//*/

		// if it hits a php built in thing, we cannot open those.
		if(!$this->File)
		return '';

		$output = '';
		$num = 1;
		$fp = fopen($this->File,'r');

		// seek to the beginning of the code we want.
		while($num++ < $this->LineBegin)
		fgets($fp);

		// read until the end of the code we want.
		while($num++ <= ($this->LineEnd+1) && !feof($fp))
		$output .= fgets($fp);

		fclose($fp);
		return $output;
	}

	////////////////
	////////////////

}
