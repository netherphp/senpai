<?php

namespace Nether\Senpai\Extractors;

use \PhpParser                as Parser;
use \Nether\Senpai\Statements as Statements;

class SenpaiCommentExtractor
extends Parser\NodeVisitorAbstract {

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
	__construct($Statement) {
		$this->Statement = $Statement;
		$this->LineNumber = $Statement->GetLineNumber();

		// here we mess with the line numbers to handle people who like
		// to format slightly different, and the parser itself shows
		// declarations as ending on the same line even when they are
		// not really doing so.

		if($Statement instanceof Statements\MethodStatement) {
			echo "Method: {$this->LineNumber}", PHP_EOL;
			if($Statement->GetData()->GetReturnType()) {
				$this->LineNumber = $Statement->GetData()->GetReturnType()->GetLine();
				echo "ReturnType: {$this->LineNumber}", PHP_EOL;
			}

			elseif(count($Statement->GetData()->GetParams())) {
				$Args = $Statement->GetData()->GetParams();
				end($Args);

				$this->LineNumber = current($Args)->GetLine();
				echo "LastParam: {$this->LineNumber}", PHP_EOL;
				unset($Args);
			}
		}

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////


	public function
	EnterNode(Parser\Node $Node) {

		foreach($Node->GetComments() as $Comment) {
			for($Fuzz = 0; $Fuzz <= 2; $Fuzz++) {
				if($Comment->GetLine() === ($this->LineNumber + $Fuzz)) {
					$this->Comments[$Comment->GetLine()] = trim($Comment->GetText());
					return Parser\NodeTraverser::STOP_TRAVERSAL;
				}
			}
		}

		return;
	}

}