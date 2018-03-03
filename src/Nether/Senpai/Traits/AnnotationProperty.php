<?php

namespace Nether\Senpai\Traits;

trait AnnotationProperty {

	protected
	$Annotation = NULL;

	public function
	GetAnnotation() {

		return $this->Annotation;
	}

	public function
	SetAnnotation($Input):
	self {

		$this->Annotation = $Input;
		return $this;
	}

}
