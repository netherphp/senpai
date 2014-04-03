<?php

namespace Nether;

use \Nether;
use \Exception;
use \ReflectionClass;
use \ReflectionMethod;

class Senpai {

	const DOC_PUBLIC = 1;
	const DOC_DEV = 2;

	public $File;
	public $List = [];

	////////////////
	////////////////

	public function __construct() {
		return;
	}

	public function Notice($what) {
		preg_match('/^(.+?)=(.+?)$/',$what,$m);

		$struct = false;
		switch($m[1]) {
			case 'class': { $struct = $this->NoticeClass($m[2]); break; }
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

		return new Senpai\ClassInfo($this,$reflect);
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

	public function SaveMarkdownOverview($filename,$doctype=self::DOC_PUBLIC) {
		$doc = new Senpai\Output\MarkdownOverview($this,$doctype);

		file_put_contents($filename,$doc);
		return;
	}

	public function SaveMarkdownFiles($dir) {
		if(!is_dir($dir)) mkdir($dir,0777,true);


		foreach($this->List as $obj) {
			$filename = strtolower(sprintf('%s/%s.html',$dir,$obj->Name));
			$dirname = dirname($filename);

			if(!is_dir($dirname))
			mkdir($dirname,0777,true);

			$surface = new Nether\Surface;
			$surface->Start();
			$obj->ToMarkdown($surface);

			ob_start();
			$surface->Render();
			$content = ob_get_clean();

			echo "Writing file {$obj->Name}", PHP_EOL;
			file_put_contents($filename,$content);
		}

		return;
	}

}