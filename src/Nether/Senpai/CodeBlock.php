<?php

namespace Nether\Senpai;

use \Nether;

class CodeBlock {

	public $Name;
	/*//
	@type string
	the name or title of the code block. (e.g. a class name or function name).
	//*/

	public $Info;
	/*//
	@type string
	the documentation info from the code in markdown.
	//*/

	public $Tags = [];
	/*//
	@type array
	a list of all the at-tags associated with this code block.
	//*/

	public $File;
	/*//
	@type string
	the path to the file this block is contained in.
	//*/

	public $LineStart = 0;
	/*//
	@type int
	the line number of the file this block starts on.
	//*/

	public $LineStop = 0;
	/*//
	@type int
	the line number of the file this block ends on.
	//*/

	protected $Reflector;
	/*//
	the php reflection object to describe this block.
	//*/

	static $Infotags = ['deprecated'];

	////////////////
	////////////////

	public function __construct($reflect) {
		$this->Reflector = $reflect;

		$this->Name = $reflect->getName();

		if(method_exists($reflect,'getFilename'))
		$this->File = $reflect->getFilename();

		if(method_exists($reflect,'getStartLine'))
		$this->LineStart = $reflect->getStartLine();

		if(method_exists($reflect,'getEndLine'))
		$this->LineStop = $reflect->getEndLine();

		$this->Examine();
		$this->ReadDocument();
		$this->ExamineTags();

		return;
	}

	////////////////
	////////////////

	protected function Examine() {
	/*//
	@template
	code to examine the current code block with. this should be overriden by
	child classes for specific types of code blocks.
	//*/

		return;
	}

	protected function ExamineTags() {
	/*//
	@template
	code to examine the tags from the document with. this should be overridden
	by child classes for specific types of code blocks.
	//*/

		return;
	}

	////////////////
	////////////////

	protected function ReadDocument() {
	/*//
	parse the doc block associated with this code.
	//*/

		$doc = Document::NewFromSource($this->ExtractFromFile());

		if($doc) {
			$this->Tags = $doc->Tags;
			$this->Info = $doc->Text;
		}

		return;
	}

	////////////////
	////////////////

	public function ExtractFromFile() {
	/*//
	@argv int StartLine, int NumLines
	@return array
	fetch a slice of a file.
	//*/

		return array_slice(
			file($this->File,FILE_IGNORE_NEW_LINES),
			($this->LineStart - 1),
			($this->LineStop - 1)
		);
	}

	////////////////
	////////////////

	public function ParseInfo() {
		$text = \Michelf\MarkdownExtra::defaultTransform($this->Info);

		return $text;
	}

	////////////////
	////////////////

	public function SaveToDirectory($basedir) {
	/*//
	@argv string BaseDirectory
	save this structure to disk. it will be placed in a subfolder based on the
	namespace of this class.
	//*/

		$filename = preg_replace(
			'/[\\\\\/]/',
			DIRECTORY_SEPARATOR,
			sprintf(
				'%s/%s.html',
				$basedir,
				strtolower($this->Name)
			)
		);

		if(!is_dir(dirname($filename))) {
			mkdir(dirname($filename),0777,true);
		}

		$surface = Nether\Stash::Get('surface');
		$surface->Set('class',$this);
		$output = $surface->Area('class',true);
		file_put_contents($filename,$output);
		return;
	}

}
