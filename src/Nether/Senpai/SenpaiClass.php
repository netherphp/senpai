<?php

namespace Nether\Senpai;

use \Nether;

class SenpaiClass extends CodeBlock {

	public $Extends = [];
	public $Properties = [];
	public $Methods = [];
	public $Traits = [];
	public $Interfaces = [];

	////////////////
	////////////////

	public function Examine() {
		$r = $this->Reflector;

		if($r->isFinal()) $this->AddTag('final');
		if($r->isAbstract()) $this->AddTag('abstract');
		if($r->isTrait()) $this->AddTag('trait');
		if($r->isInterface()) $this->AddTag('interface');

		$this->Extends = $r->getParentClassNameList();

		foreach($r->getInterfaces() as $iface) {
			$i = new SenpaiClass($iface);
			if(!$i->HasTag('skipdoc')) $this->Interfaces[$i->Name] = $i;
		}

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

		$sorter = function($a,$b){
			if($a->GetTag('access') > $b->GetTag('access')) return -1;
			elseif($a->GetTag('access') < $b->GetTag('access')) return 1;
			else {
				if($a->Name > $b->Name) return 1;
				elseif($a->Name < $b->Name) return -1;
				else return 0;
			}
		};

		uasort($this->Properties,$sorter);
		uasort($this->Methods,$sorter);
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

		$surface = $senpai
		->GetSurface()
		->Set('path-backpedal',str_repeat('../',$deep));

		if($full) {
			$surface->Start();
			$surface->Set('class',$this);
			$surface->Area('class');
			$output = $surface->Render(true);
		} else {
			$surface->Set('class',$this);
			$output = $surface->Area('class',true);
		}

		file_put_contents(
			$filename,
			$output
		);

		return;
	}

}
