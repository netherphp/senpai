<?php

namespace Nether\Senpai\Statements;

use \Nether\Senpai\Traits as Traits;

class TraitStatement {

	use Traits\NamespaceProperty;
	use Traits\NameProperty;
	use Traits\LineNumberProperty;
	use Traits\DataProperty;
	use Traits\CommentArrayProperty;

	public function
	GetFullName():
	String {

		return sprintf(
			'%s\\%s',
			$this->Namespace->GetName(),
			$this->GetName()
		);
	}

}
