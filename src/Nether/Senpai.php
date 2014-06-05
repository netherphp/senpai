<?php

namespace Nether;

use \Nether;
use \TokenReflection;

class Senpai {

	public $Scanner;
	public $List = [];
	public $Theme;
	public $ThemeRoot;

	////////////////
	////////////////

	public function SetTheme($theme) {
		$this->Theme = $theme;
		return $this;
	}

	public function SetThemeRoot($path) {
		$this->ThemeRoot = $path;
		return $this;
	}

	////////////////
	////////////////

	public function __construct($dir) {
		$this->Scanner = new TokenReflection\Broker(new TokenReflection\Broker\Backend\Memory());
		$this->Scanner->processDirectory($dir);
		return;
	}

	////////////////
	////////////////

	public function Notice($what) {
		$this->List[] = new Senpai\SenpaiClass($this->Scanner->getClass($what));
		return $this;
	}

	////////////////
	////////////////

	protected function Sort() {
		usort($this->List,function($a,$b){
			if($a->Name > $b->Name) return 1;
			else if($a->Name < $b->Name) return -1;
			else return 0;
		});
	}

	////////////////
	////////////////

	public function SaveToDirectory($dir,$full=false) {

		$this->Sort();
		Nether\Stash::Set('code-index',$this->List);

		foreach($this->List as $item) {
			$item->Save($this,$dir,$full);
		}

		if($full) {
			// copy in the stylesheet.
			$css = sprintf('%s/%s/design.css',$this->ThemeRoot,$this->Theme);
			$js = sprintf('%s/%s/design.js',$this->ThemeRoot,$this->Theme);

			if(file_exists($css)) copy($css,"{$dir}/design.css");
			if(file_exists($js)) copy($js,"{$dir}/design.css");
		}

	}

}