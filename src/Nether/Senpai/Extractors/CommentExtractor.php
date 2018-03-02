<?php

namespace Nether\Senpai\Extractors;

use \PhpParser as Parser;

class CommentExtractor
extends Parser\NodeVisitorAbstract {

	protected
	$Comments = [];

	public function
	GetComments():
	Array {

		return $this->Comments;
	}

	public function
	GetCommentFromLine(Int $LineNumber):
	?String {

		if(array_key_exists($LineNumber,$this->Comments))
		return $this->Comments[$LineNumber];

		return NULL;
	}

	public function
	LeaveNode(Parser\Node $Node):
	Void {

		foreach($Node->GetComments() as $Comment)
		$this->Comments[$Comment->GetLine()] = trim($Comment->GetText());

		return;
	}

}