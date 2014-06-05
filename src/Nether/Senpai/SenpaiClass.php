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
			$t = new SenpaiClass($trait);
			if(!$t->HasTag('skipdoc')) $this->Traits[$t->Name] = $t;
		}

		foreach($r->getProperties() as $property) {
			$p = new SenpaiProperty($this,$property);
			if(!$p->HasTag('skipdoc')) $this->Properties[$p->Name] = $p;
		}

		foreach($r->getMethods() as $method) {
			$m = new SenpaiMethod($this,$method);
			if(!$m->HasTag('skipdoc')) $this->Methods[$m->Name] = $m;
		}

		return;
	}

	public function ExamineTags() {

		if(!$this->Info) {
			if($this->HasTag('trait')) $this->Info = 'This trait has no description.';
			else $this->Info = 'This class has no description.';
		}

		sort($this->Traits);
		ksort($this->Properties);
		ksort($this->Methods);
		return;
	}

	////////////////
	////////////////

	public function GetMembersWithTags($type,$tags) {

		if($type == 'method') $list = $this->Methods;
		else $list = $this->Properties;

		$output = [];

		foreach($tags as $tag) {
			foreach($list as $m) {
				$ok = true;

				if(is_array($tag)) {
					foreach($tag as $t) {
						if($m->HasTag($t)) {
							$ok = true;
							break;
						} else {
							$ok = false;
						}
					}
				} else {
					if(!$m->HasTag($tag))
					$ok = false;
				}

				if($ok)
				$output[] = $m;
			}

		}

		return $output;
	}

	public function GetMethodsWithTags($tags) {
		return $this->GetMembersWithTags('method',$tags);
	}

	public function GetPropertiesWithTags($tags) {
		return $this->GetMembersWithTags('property',$tags);
	}

	////////////////
	////////////////

	public function Save($senpai,$dir,$full=false) {

		$deep = count(explode('\\',$this->Name)) - 1;

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

		$surface->Set('path-backpedal',str_repeat('../',$deep));

		if($full) {
			$surface->Start();
			$surface->Set('class',$this);
			$surface->Area('class');

			$output = $surface->Render(true);

			file_put_contents(
				$filename,
				$output
			);
		} else {
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
		}



		return;
	}

}
