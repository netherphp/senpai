<?php

namespace Nether\Senpai\Extractors;

use \PhpParser                as Parser;
use \Nether                   as Nether;
use \Nether\Senpai\Statements as Statements;
use \Nether\Senpai\Extractors as Extractors;
use \Nether\Senpai\Traits     as Traits;

class TraitExtractor
extends Parser\NodeVisitorAbstract {

	use Traits\FileProperty;
	use Traits\NamespaceProperty;
	use Traits\TraitArrayProperty;

	public function
	__construct(Statements\NamespaceStatement $Namespace, Nether\Senpai\FileReader $File) {
		$this->Namespace = $Namespace;
		$this->File = $File;
		return;
	}

	public function
	EnterNode(Parser\Node $Node):
	Void {

		if($Node instanceof Parser\Node\Stmt\Trait_) {
			($Trait = new Statements\TraitStatement)
			->SetNamespace($this->Namespace)
			->SetName($Node->name->ToString())
			->SetLineNumber($Node->GetLine())
			->SetData($Node);

			$Comments = new Extractors\SenpaiCommentExtractor($Trait,$this->File);

			$Trait->SetAnnotation(Nether\Senpai\Annotation::FromString($Comments));
			$this->Traits[] = $Trait;
		}

		return;
	}

	public function
	AfterTraverse(Array $Nodes):
	Void {

		foreach($this->Traits as $Trait) {
			$Walker = new Parser\NodeTraverser;
			$Reader = new Parser\NodeTraverser;
			$Methods = new Extractors\MethodExtractor($Trait,$this->File);
			$Comments = new Extractors\SenpaiCommentExtractor($Trait,$this->File);

			$Walker->AddVisitor($Methods);
			$Walker->Traverse($Trait->GetData()->stmts);

			$Trait
			->SetMethods($Methods->GetMethods())
			->SetAnnotation(Nether\Senpai\Annotation::FromString($Comments))
			->SetData(NULL);
		}


		return;
	}

}
