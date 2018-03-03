<?php

namespace Nether\Senpai\Extractors;

use \PhpParser                as Parser;
use \Nether                   as Nether;
use \Nether\Senpai\Statements as Statements;
use \Nether\Senpai\Extractors as Extractors;
use \Nether\Senpai\Traits     as Traits;

class ClassExtractor
extends Parser\NodeVisitorAbstract {

	use Traits\FileProperty;
	use Traits\NamespaceProperty;
	use Traits\ClassArrayProperty;

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	__construct(Statements\NamespaceStatement $Namespace, Nether\Senpai\FileReader $File) {
		$this->Namespace = $Namespace;
		$this->File = $File;
		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	EnterNode(Parser\Node $Node):
	Void {

		if($Node instanceof Parser\Node\Stmt\Class_) {
			($Class = new Statements\ClassStatement)
			->SetNamespace($this->Namespace)
			->SetName($Node->name->ToString())
			->SetLineNumber($Node->GetLine())
			->SetData($Node);

			$this->Classes[$Class->GetFullName()] = $Class;
		}

		return;
	}

	public function
	AfterTraverse(Array $Nodes):
	Void {

		foreach($this->Classes as $Class) {
			$Walker = new Parser\NodeTraverser;
			$Reader = new Parser\NodeTraverser;
			$Methods = new Extractors\MethodExtractor($Class,$this->GetFile());
			$Comments = new Extractors\SenpaiCommentExtractor($Class,$this->GetFile());

			$Walker->AddVisitor($Methods);
			$Walker->Traverse($Class->GetData()->stmts);

			$Class
			->SetMethods($Methods->GetMethods())
			->SetAnnotation(Nether\Senpai\Annotation::FromString($Comments))
			->SetData(NULL);
		}


		return;
	}

}
