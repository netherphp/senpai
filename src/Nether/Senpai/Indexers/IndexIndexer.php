<?php

namespace Nether\Senpai\Indexers;

use \Nether\Senpai\Traits     as Traits;
use \Nether\Senpai\Statements as Statements;


class IndexIndexer {

	protected
	$Namespaces = [];

	public function
	AddNamespace(Statements\NamespaceStatement $Namespace):
	self {

		$this->Namespaces[] = $Namespace;
		return $this;
	}

	public function
	GetNamespaces():
	Array {

		return $this->Namespaces;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Run():
	Array {

		$Output = [];

		// merge all the namespaces into a single tree.

		foreach($this->Namespaces as $Namespace) {
			if(!array_key_exists($Namespace->GetName(),$Output)) {
				$Output[$Namespace->GetName()] = $Namespace;
				continue;
			}

			else {
				$Output[$Namespace->GetName()]
				->MergeClasses($Namespace->GetClasses())
				->MergeTraits($Namespace->GetTraits());
			}
		}

		// unify the namespace object references, sort classes n stuff.

		foreach($this->Namespaces as $Namespace) {
			$Namespace
			->SortClasses()
			->SortTraits();

			foreach($Namespace->GetClasses() as $Class) {
				$Class
				->SetNamespace($Namespace)
				->SortMethods();
			}

			foreach($Namespace->GetTraits() as $Trait) {
				$Trait
				->SetNamespace($Namespace)
				->SortMethods();
			}
		}

		ksort($Output);
		return $Output;
	}

}
