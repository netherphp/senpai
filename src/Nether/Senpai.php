<?php

namespace Nether;

use \Nether;
use \Exception;
use \ReflectionClass;
use \ReflectionMethod;

class Senpai {

	public $List = [];
	/*//
	@type array
	a list of all the structures parsed by the engine.
	//*/

	////////////////
	////////////////

	public function __construct() {
		return;
	}

	////////////////
	////////////////

	public function Notice($what) {
		preg_match('/^(.+?)=(.+?)$/',$what,$m);

		$struct = false;
		switch($m[1]) {
			case 'class': {
				$struct = $this->NoticeClass($m[2]);
				break;
			}
		}

		if(!$struct) {
			echo "NOT FOUND: {$what}", PHP_EOL;
		} else {
			$this->List[$struct->Name] = $struct;
		}

		return $this;
	}

	public function NoticeClass($class) {
		if(is_string($class)) {
			try { $reflect = new ReflectionClass($class); }
			catch(Exception $e) { return false; }
		} else $reflect = $class;

		return new Senpai\SenpaiClass($reflect);
	}

	public function NoticeMethod($method) {
		if(is_string($method)) {
			try { $reflect = new ReflectionMethod($method); }
			catch(Exception $e) { return false; }
		} else $reflect = $method;

		return new Senpai\MethodInfo($this,$reflect);
	}

	////////////////
	////////////////

	public function BuildTree() {
		$tree = [
			'\\' => (object)['Namespaces' => [], 'Classes' => []]
		];

		foreach($this->List as $class) {
			$cur = $tree['\\'];
			$cpath = explode('\\',dirname($class->Name));

			foreach($cpath as $cp) {
				if(!array_key_exists($cp,$cur->Namespaces))
				$cur->Namespaces[$cp] = (object)['Namespaces' => [], 'Classes' => []];

				$cur = $cur->Namespaces[$cp];
			}

			$cur->Classes[] = basename($class->Name);
		}

		return $tree;
	}

	////////////////
	////////////////

	public function SaveToDirectory($dir) {
	/*//
	@argv string Directory
	save all the structures senpai has noticed to the specified directory.
	each structure will be given its own file.
	//*/

		if(!is_dir($dir))
		mkdir($dir,0777,true);

		foreach($this->List as $obj) {
			$obj->SaveToDirectory($dir);
		}

		return;
	}

}