<?php

namespace Nether\Senpai\Extractors;

use \PhpParser                as Parser;
use \Nether                   as Nether;
use \Nether\Senpai\Traits     as Traits;
use \Nether\Senpai\Statements as Statements;
use \Nether\Senpai\Extractors as Extractors;

class MethodExtractor
extends Parser\NodeVisitorAbstract {

	use Traits\FileProperty;
	use Traits\NamespaceProperty;
	use Traits\MethodArrayProperty;

	public function
	__construct(Nether\Senpai\Statement $Class, Nether\Senpai\FileReader $File) {
		$this->Class = $Class;
		$this->File = $File;
		return;
	}

	public function
	LeaveNode(Parser\Node $Node):
	Void {

		if($Node instanceof Parser\Node\Stmt\ClassMethod) {
			($Method = new Statements\MethodStatement)
			->SetClass($this->Class)
			->SetName($Node->name->ToString())
			->SetLineNumber($Node->GetLine())
			->SetData($Node);

			if($Node->IsAbstract())
			$Method->EnableFlags(Statements\MethodStatement::IsAbstract);

			if($Node->IsStatic())
			$Method->EnableFlags(Statements\MethodStatement::IsStatic);

			if($Node->IsFinal())
			$Method->EnableFlags(Statements\MethodStatement::IsFinal);

			if($Node->IsPublic())
			$Method->EnableFlags(Statements\MethodStatement::IsPublic);

			if($Node->IsProtected())
			$Method->EnableFlags(Statements\MethodStatement::IsProtected);

			if($Node->IsPrivate())
			$Method->EnableFlags(Statements\MethodStatement::IsPrivate);

			$this->Methods[$Method->GetName()] = $Method;
		}

		return;
	}

	public function
	AfterTraverse(Array $Nodes):
	Void {

		foreach($this->Methods as $Method) {
			$Reader = new Parser\NodeTraverser;
			$Comments = new Extractors\SenpaiCommentExtractor($Method,$this->File);

			$Method
			->SetAnnotation(Nether\Senpai\Annotation::FromString($Comments))
			->SetData(NULL);
		}

		return;
	}

}
