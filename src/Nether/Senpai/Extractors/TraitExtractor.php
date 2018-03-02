<?php

namespace Nether\Senpai\Extractors;

use \PhpParser                as Parser;
use \Nether\Senpai\Statements as Statements;
use \Nether\Senpai\Extractors as Extractors;

class TraitExtractor
extends Parser\NodeVisitorAbstract {

	protected
	$Traits = [];

	public function
	GetTraits():
	Array {

		return $this->Traits;
	}

	public function
	LeaveNode(Parser\Node $Node):
	Void {

		if($Node instanceof Parser\Node\Stmt\Trait_) {
			$Trait = new Statements\TraitStatement;
			$Trait->SetName($Node->name->ToString());
			$Trait->SetLineNumber($Node->GetLine());

			$Walker = new Parser\NodeTraverser;
			$Comments = new Extractors\SenpaiCommentExtractor($Trait);

			$Walker->AddVisitor($Comments);
			$Walker->Traverse($Node->stmts);

			$Trait->SetComments($Comments->GetComments());
			$this->Traits[] = $Trait;
		}

		return;
	}

}
