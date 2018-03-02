<?php

namespace Nether\Senpai\Indexers;

use \PhpParser                as Parser;
use \Nether\Senpai\Extractors as Extractors;
use \Nether\Senpai\Indexers   as Indexers;

use \PhpParser\ParserFactory;
use \PhpParser\NodeTraverser;
use \PhpParser\NodeVisitorAbstract;
use \PhpParser\Node;
use \PhpParser\Node\Stmt\Namespace_;
use \PhpParser\Node\Stmt\Class_;
use \PhpParser\Node\Stmt\ClassMethod;

class MethodIndexer {

	protected
	$Filename = '';

	protected
	$Parser = NULL;

	protected
	$Walker = NULL;

	protected
	$Extractor = NULL;

	public function
	__construct($Filename) {

		$this->Filename = $Filename;
		$this->Parser = (new Parser\ParserFactory)->Create(
			Parser\ParserFactory::PREFER_PHP7
		);
		$this->Walker = new NodeTraverser;
		$this->Extractor = new Extractors\MethodExtractor;

		$this->Walker->AddVisitor($this->Extractor);

		// read in the file we want to parse.
		$Result = $this->Parser->Parse(file_get_contents($Filename));
		$this->Walker->Traverse($Result);

		return;
	}

	public function
	GetExtractor():
	Extractors\MethodExtractor {
	/*//
	get the extraction object that can describe the class and the comments
	found within its methods.
	//*/

		return $this->Extractor;
	}

}
