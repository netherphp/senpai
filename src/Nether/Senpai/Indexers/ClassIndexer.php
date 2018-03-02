<?php

namespace Nether\Senpai\Indexers;

use \PhpParser                as Parser;
use \Nether\Senpai\Extractors as Extractors;
use \Nether\Senpai\Indexers   as Indexers;
use \Nether\Senpai\Statements as Statements;

class ClassIndexer {

	protected
	$Filename = '';
	/*//
	@type String
	the full path to the file the class is found in.
	//*/

	public function
	GetFilename():
	String {
	/*//
	@get Filename
	//*/

		return $this->Filename;
	}

	public function
	SetFilename(String $Input):
	self {
	/*//
	@set Filename
	//*/

		$this->Filename = $Input;
		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected
	$Namespace = '';

	public function
	GetNamespace():
	String {

		return $this->Namespace;
	}

	public function
	SetNamespace(String $Input):
	self {

		$this->Namespace = $Input;
		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected
	$Name = '';

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected
	$Methods = [];

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Run() {

		$Parser = new Parser\ParserFactory;
		$Walker = new Parser\NodeTraverser;
		$Extractor = new Extractors\ClassExtractor;
		$Output = [];

		$Parser = $Parser->Create(Parser\ParserFactory::PREFER_PHP7);

		$Walker->AddVisitor($Extractor);
		$Walker->Traverse($Parser->Parse(
			file_get_contents($this->Filename)
		));

		return $Output;
	}

}
