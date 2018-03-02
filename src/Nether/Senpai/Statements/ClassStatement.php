<?php

namespace Nether\Senpai\Statements;

use \Nether\Senpai\Traits as Traits;

class ClassStatement {

	use Traits\NamespaceProperty;
	use Traits\NameProperty;
	use Traits\LineNumberProperty;
	use Traits\DataProperty;
	use Traits\CommentArrayProperty;
	use Traits\MethodArrayProperty;

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
