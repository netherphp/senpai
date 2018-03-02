<?php

namespace Nether\Senpai\Extractors;

use \PhpParser                as Parser;
use \Nether\Senpai\Traits     as Traits;
use \Nether\Senpai\Statements as Statements;
use \Nether\Senpai\Extractors as Extractors;

class MethodExtractor
extends Parser\NodeVisitorAbstract {

	use Traits\NamespaceProperty;
	use Traits\MethodArrayProperty;

	public function
	__construct(Statements\ClassStatement $Class) {
		$this->Class = $Class;
		return;
	}

	public function
	LeaveNode(Parser\Node $Node):
	Void {

		if($Node instanceof Parser\Node\Stmt\ClassMethod)
		($this->Methods[] = new Statements\MethodStatement)
		->SetClass($this->Class)
		->SetName($Node->name->ToString())
		->SetLineNumber($Node->GetLine())
		->SetData($Node);

		return;
	}

	public function
	AfterTraverse(Array $Nodes):
	Void {

		foreach($this->Methods as $Method) {
			$Reader = new Parser\NodeTraverser;
			$Comments = new Extractors\SenpaiCommentExtractor($Method);

			$Reader->AddVisitor($Comments);
			$Reader->Traverse($Method->GetData()->stmts);

			$Method
			->SetComments($Comments->GetComments())
			->SetData(NULL);
		}

		return;
	}

}
