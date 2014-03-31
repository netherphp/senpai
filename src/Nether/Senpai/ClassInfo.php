<?php

namespace Nether\Senpai;

use \Nether;
use \ReflectionMethod;

class ClassInfo extends Info {

	public $File;
	public $LineStart;
	public $LineEnd;

	public $Properties = [];
	public $Methods = [];

	public $StaticMethods = [];
	public $StaticProperties = [];

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

		$properties = $reflect->getProperties();
		foreach($properties as $property) {
			$p = new PropertyInfo($senpai,$property);
			$p->Class = $this;

			if($p->Static) $this->StaticProperties[$p->Name] = $p;
			else $this->Properties[$p->Name] = $p;
		}

		$methods = $reflect->getMethods();
		foreach($methods as $method) {
			$m = new MethodInfo($senpai,$method);
			$m->Class = $this;

			if($m->Static) $this->StaticMethods[$m->Name] = $m;
			else $this->Methods[$m->Name] = $m;
		}

		$sorter = function($a,$b){
			if($a->AccessInt == $b->AccessInt) {
				if($a->Name < $b->Name) return -1;
				else return 1;
			} else {
				if($a->AccessInt < $b->AccessInt) return -1;
				else return 1;
			}
		};

		uasort($this->Properties,$sorter);
		uasort($this->Methods,$sorter);

		return;
	}

	public function ToMarkdown($surface) {
		$surface->Set('class',$this);
		$surface->Area('class');
		return;
	}

}
