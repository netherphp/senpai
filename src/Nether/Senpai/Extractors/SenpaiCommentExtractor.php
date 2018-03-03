<?php

namespace Nether\Senpai\Extractors;

use \PhpParser                as Parser;
use \Nether\Senpai\Traits     as Traits;
use \Nether\Senpai\Statements as Statements;

class SenpaiCommentExtractor {

	use Traits\FileProperty;

	protected
	$Statement = NULL;

	protected
	$LineNumber = 0;

	protected
	$Comments = [];

	public function
	GetComments():
	Array {

		return $this->Comments;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	__construct($Statement, $File) {
		$this->Statement = $Statement;
		$this->LineNumber = $Statement->GetLineNumber();
		$this->File = $File;

		// here we mess with the line numbers to handle people who like
		// to format slightly different, and the parser itself shows
		// declarations as ending on the same line even when they are
		// not really doing so.

		if($Statement instanceof Statements\MethodStatement) {
			//echo "Method: {$this->LineNumber}", PHP_EOL;
			if($Statement->GetData()->GetReturnType()) {
				$this->LineNumber = $Statement->GetData()->GetReturnType()->GetLine();
				//echo "ReturnType: {$this->LineNumber}", PHP_EOL;
			}

			elseif(count($Statement->GetData()->GetParams())) {
				$Args = $Statement->GetData()->GetParams();
				end($Args);

				$this->LineNumber = current($Args)->GetLine();
				//echo "LastParam: {$this->LineNumber}", PHP_EOL;
				unset($Args);
			}
		}

		for($Fuzz = 0; $Fuzz <= 3; $Fuzz++) {
			$Key = $this->LineNumber + $Fuzz;
			if(array_key_exists($Key,$this->File->GetComments())) {
				$this->Comments[$Key] = $this->File->GetComments()[$Key];
				break;
			}
		}

		return;

		foreach($this->File->GetComments() as $Line => $Comment) {
			for($Fuzz = 0; $Fuzz <= 3; $Fuzz++) {
				if($Line === ($this->LineNumber + $Fuzz)) {
					$this->Comments[$Line] = trim($Comment);
					break;
				}
			}
		}

		return;
	}

	public function
	__toString():
	String {

		if(count($this->Comments)) {
			reset($this->Comments);
			return current($this->Comments);
		}

		return '';
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

}