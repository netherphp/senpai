<?php

namespace Nether\Senpai\Statements;

use \Nether               as Nether;
use \Nether\Senpai\Traits as Traits;

class FunctionStatement
extends Nether\Senpai\Statement {

	use Traits\NamespaceProperty;
	use Traits\NameProperty;
	use Traits\LineNumberProperty;
	use Traits\DataProperty;
	use Traits\AnnotationProperty;
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
