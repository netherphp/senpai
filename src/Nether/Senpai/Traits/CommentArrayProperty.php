<?php

namespace Nether\Senpai\Traits;

trait CommentArrayProperty {

	protected
	$Comments = [];

	public function
	GetComments():
	Array {

		return $this->Comments;
	}

	public function
	SetComments(Array $Input):
	self {

		$this->Comments = $Input;
		return $this;
	}

}
