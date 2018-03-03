<?php

namespace Nether\Senpai\Indexers;

use \PhpParser                as Parser;
use \Nether                   as Nether;
use \Nether\Senpai\Extractors as Extractors;
use \Nether\Senpai\Statements as Statements;
use \Nether\Senpai\Indexers   as Indexers;
use \Nether\Senpai\Traits     as Traits;
use \Nether\Senpai\Errors     as Errors;

class FileIndexer {

	use Traits\FilenameProperty;

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	__construct(String $Filename=NULL) {

		if(is_string($Filename))
		$this->Filename = $Filename;

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Run():
	Extractors\FileExtractor {

		$Parser = NULL;
		$Walker = NULL;
		$Extractor = NULL;

		$this->Run_CheckFileAccess();

		list($Parser,$Walker,$Extractor) =
		$this->Run_PrepareFileObjects();

		$File = new Nether\Senpai\FileReader($this->Filename);
		$Extractor->SetFile($File);

		$Walker->Traverse($Parser->Parse(
			$File->GetData()
		));

		return $Extractor;
	}

	protected function
	Run_CheckFileAccess():
	Void {

		if(!$this->Filename)
		throw new Errors\FileNotSpecified;

		if(!file_exists($this->Filename))
		throw new Errors\FileNotFound;

		if(!is_readable($this->Filename))
		throw new Errors\FileNotReadable;

		return;
	}

	protected function
	Run_PrepareFileObjects():
	Array {

		$Parser = (new Parser\ParserFactory)
		->Create(
			Parser\ParserFactory::PREFER_PHP7,
			new Parser\Lexer([
				'usedAttributes' => [
					'comments',
					'startLine',
					'endLine',
					'startTokenPos',
					'endTokenPos'
				]
			])
		);

		$Walker = new Parser\NodeTraverser;
		$Extractor = new Extractors\FileExtractor;

		$Walker->AddVisitor($Extractor);

		return [ $Parser, $Walker, $Extractor ];
	}

}

