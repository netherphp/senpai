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

		foreach($this->Namespaces as $Namespace) {
			if(!array_key_exists($Namespace->GetName(),$Output)) {
				$Output[$Namespace->GetName()] = $Namespace;
				continue;
			}

			else {
				$Output[$Namespace->GetName()]
				->MergeClasses($Namespace->GetClasses())
				->SortClasses()
				->MergeTraits($Namespace->GetTraits())
				->SortTraits();
			}
		}

		ksort($Output);

		return $Output;
	}

}
