<?php

namespace Nether\Senpai;

use \Nether;

class SenpaiClass extends CodeBlock {

	public $Extends = [];
	public $Properties = [];
	public $Methods = [];
	public $Traits = [];

	////////////////
	////////////////

	public function Examine() {
		$r = $this->Reflector;

		if($r->isFinal()) $this->AddTag('final');
		if($r->isAbstract()) $this->AddTag('abstract');
		if($r->isTrait()) $this->AddTag('trait');

		$this->Extends = $r->getParentClassNameList();

		foreach($r->getTraits() as $trait) {
			$this->Traits[$trait->getName()] = new SenpaiClass($trait);
		}

		foreach($r->getProperties() as $property) {
			$this->Properties[$property->getName()] = new SenpaiProperty($property);
		}

		foreach($r->getMethods() as $method) {
			$this->Methods[$method->getName()] = new SenpaiMethod($method);
		}

		return;
	}

	public function ExamineTags() {

		if(!$this->Info) {
			if($this->HasTag('trait')) $this->Info = 'This trait has no description.';
			else $this->Info = 'This class has no description.';
		}


		return;
	}

	////////////////
	////////////////

	public function Save($senpai,$dir) {

		$filename = preg_replace('/[\\\\\/]/',DIRECTORY_SEPARATOR,sprintf(
			'%s/%s.html',
			$dir,
			strtolower($this->Name)
		));

		if(!is_dir(dirname($filename)))
		mkdir(dirname($filename),0777,true);

		$surface = new Nether\Surface([
			'Theme' => $senpai->Theme,
			'ThemeRoot' => $senpai->ThemeRoot,
			'Autocapture' => false,
			'Autostash' => false
		]);

		$surface->Set('class',$this);
		file_put_contents(
			$filename,
			$surface->Area('class',true)
		);

		return;
	}

}
