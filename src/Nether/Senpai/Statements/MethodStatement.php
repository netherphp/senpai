<?php

namespace Nether\Senpai\Statements;

use \Nether               as Nether;
use \Nether\Senpai\Traits as Traits;

class MethodStatement
extends Nether\Senpai\Statement {

	use Traits\ClassProperty;
	use Traits\NameProperty;
	use Traits\LineNumberProperty;
	use Traits\FlagsProperty;
	use Traits\AnnotationProperty;
	use Traits\DataProperty;
	use Traits\CommentArrayProperty;

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	const IsAbstract  = 0x01;
	const IsStatic    = 0x02;
	const IsFinal     = 0x04;
	const IsPublic    = 0x08;
	const IsProtected = 0x01;
	const IsPrivate   = 0x02;

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	IsAbstract():
	Bool {

		return ($this->Flags & static::IsAbstract) === static::IsAbstract;
	}

	public function
	IsStatic():
	Bool {

		return ($this->Flags & static::IsStatic) === static::IsStatic;
	}

	public function
	IsFinal():
	Bool {

		return ($this->Flags & static::IsFinal) === static::IsFinal;
	}

	public function
	IsPublic():
	Bool {

		return ($this->Flags & static::IsPublic) === static::IsPublic;
	}

	public function
	IsProtected():
	Bool {

		return ($this->Flags & static::IsProtected) === static::IsProtected;
	}

	public function
	IsPrivate():
	Bool {

		return ($this->Flags & static::IsPrivate) === static::IsPrivate;
	}

	public function
	GetFullName():
	String {

		return sprintf(
			'%s::%s',
			$this->Class->GetFullName(),
			$this->GetName()
		);
	}

	public function
	GetAccessWords($Delim=' '):
	String {

		$Output = '';

		if($this->IsAbstract())
		$Output .= 'abstract' . $Delim;

		if($this->IsStatic())
		$Output .= 'static' . $Delim;

		if($this->IsFinal())
		$Output .= 'final' . $Delim;

		if($this->IsPublic())
		$Output .= 'public' . $Delim;

		if($this->IsProtected())
		$Output .= 'protected' . $Delim;

		if($this->IsPrivate())
		$Output .= 'private' . $Delim;

		return trim($Output,$Delim);
	}

}
