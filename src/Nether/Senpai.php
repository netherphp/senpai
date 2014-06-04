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

	public function SaveToDirectory($dir) {

		foreach($this->List as $item) {
			$item->Save($this,$dir);
		}

	}

}