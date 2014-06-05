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

	public function GetSurface() {
		return new Nether\Surface([
			'Theme' => $this->Theme,
			'ThemeRoot' => $this->ThemeRoot,
			'Autocapture' => false,
			'Autostash' => false
		]);
	}

	////////////////
	////////////////

	public function SaveIndex($dir,$full=false) {

		$filename = sprintf('%s/index.html',$dir);
		$surface = $this->GetSurface();

		if($full) {
			$surface->Start();
			$surface->Area('index');
			$output = $surface->Render(true);
		} else {
			$output = $surface->Area('index');
		}

		file_put_contents(
			$filename,
			$output
		);

		return $this;
	}

	public function SaveToDirectory($dir,$full=false) {

		$this->Sort();
		Nether\Stash::Set('code-index',$this->List);

		foreach($this->List as $item) {
			$deep = count(explode('\\',$item->Name)) - 1;

			$filename = preg_replace('/[\\\\\/]/',DIRECTORY_SEPARATOR,sprintf(
				'%s/%s.html',
				$dir,
				strtolower($item->Name)
			));

			if(!is_dir(dirname($filename)))
			mkdir(dirname($filename),0777,true);

			$surface = $this
			->GetSurface()
			->Set('path-backpedal',str_repeat('../',$deep));

			if($full) {
				$surface->Start();
				$surface->Set('class',$item);
				$surface->Area('class');
				$output = $surface->Render(true);
			} else {
				$surface->Set('class',$item);
				$output = $surface->Area('class',true);
			}

			file_put_contents(
				$filename,
				$output
			);
		}

		if($full) {
			$css = sprintf('%s/%s/design.css',$this->ThemeRoot,$this->Theme);
			$js = sprintf('%s/%s/design.js',$this->ThemeRoot,$this->Theme);
			if(file_exists($css)) copy($css,"{$dir}/design.css");
			if(file_exists($js)) copy($js,"{$dir}/design.js");
		}

		return $this;
	}

}