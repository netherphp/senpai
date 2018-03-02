<?php

namespace Nether\Senpai\Extractors;

use \PhpParser                as Parser;
use \Nether\Senpai\Statements as Statements;
use \Nether\Senpai\Extractors as Extractors;
use \Nether\Senpai\Traits     as Traits;

class TraitExtractor
extends Parser\NodeVisitorAbstract {

	use Traits\NamespaceProperty;
	use Traits\TraitArrayProperty;

	public function
	__construct(Statements\NamespaceStatement $Namespace) {
		$this->Namespace = $Namespace;
		return;
	}

	public function
	EnterNode(Parser\Node $Node):
	Void {

		if($Node instanceof Parser\Node\Stmt\Trait_) {
			($Trait = new Statements\TraitStatement)
			->SetNamespace($this->Namespace)
			->SetName($Node->name->ToString())
			->SetLineNumber($Node->GetLine());

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
