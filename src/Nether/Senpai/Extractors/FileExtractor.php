<?php

namespace Nether\Senpai\Extractors;

use \PhpParser                as Parser;
use \Nether\Senpai\Extractors as Extractors;
use \Nether\Senpai\Statements as Statements;
use \Nether\Senpai\Traits     as Traits;

class FileExtractor
extends Parser\NodeVisitorAbstract {

	use Traits\NamespaceProperty;

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	__construct() {
	/*//
	setup the global namespace in the event none are found within this file.
	//*/

		$this->SetNamespace(
			(new Statements\NamespaceStatement)
			->SetName('\\')
		);

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	EnterNode(Parser\Node $Node):
	Void {
	/*//
	locate namespaces within this file.
	//*/

		if($Node instanceof Parser\Node\Stmt\Namespace_) {
			($this->GetNamespace())
			->SetName("\\{$Node->name}")
			->SetLineNumber($Node->GetLine())
			->SetData($Node);
		}

		return;
	}

	public function
	AfterTraverse(Array $Nodes):
	Void {
	/*//
	after we have gone through the file we have determined if there were any
	namespaces or if we are still in the global one, so now we can loop
	through the namespace and dig more code out.
	//*/

		$Walker = new Parser\NodeTraverser;
		$Reader = new Parser\NodeTraverser;
		$Classes = new Extractors\ClassExtractor($this->Namespace);
		$Traits = new Extractors\TraitExtractor($this->Namespace);
		$Comments = new Extractors\SenpaiCommentExtractor($this->Namespace);

		// walk the codebase to find structures we expect to find in
		// within namespaces.

		$Walker->AddVisitor($Traits);
		$Walker->AddVisitor($Classes);
		$Walker->Traverse(
			($this->GetNamespace())
			->GetData()
			->stmts
		);

		// locate the senpai codeblock if it exists. we use a separate
		// traverser because the senpai one bails as soon as it can to try
		// and save time.

		$Reader->AddVisitor($Comments);
		$Reader->Traverse(
			($this->GetNamespace())
			->GetData()
			->stmts
		);

		// parse the data and attach it to the namespace.

		$this->Namespace->SetComments($Comments->GetComments());
		$this->Namespace->SetTraits($Traits->GetTraits());
		$this->Namespace->SetClasses($Classes->GetClasses());
		$this->Namespace->SetData(NULL);

		return;
	}

}
