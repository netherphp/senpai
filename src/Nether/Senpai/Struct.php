<?php

namespace Nether\Senpai;
use \Nether;

abstract class Struct {

	protected
	$Name = NULL;

	public function
	GetName():
	?String {
	/*//
	return the full name of this object as is.
	//*/

		return $this->Name;
	}

	public function
	SetName(String $Name):
	self {
	/*//
	give this object a name. it should be the full name including namespace.
	//*/


		$this->Name = ($Name!=='\\')?(trim($Name,'\\')):($Name);

		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	GetNameShort():
	String {
	/*//
	@uses this::Name
	return the very short literal name of this object without its namespacing
	components.
	//*/

		$Name = array_pop(explode('\\',$this->Name));
		if(!$Name) $Name = 'Root Namespace';

		return $Name;
	}

	public function
	GetNamespaceName():
	String {
	/*//
	@uses this::Name
	return the namespace name that this object resides within.
	//*/

		$List = explode('\\',$this->Name);
		array_pop($List);

		return implode('\\',$List);
	}

	public function
	GetNameChunked():
	Array {
	/*//
	@uses this::Name
	return the full name of this object as a list.
	//*/

		return explode('\\',$this->Name);
	}

	public function
	GetNamespaceChunked():
	Array {
	/*//
	@uses this::Name
	return the namespace path as a list.
	//*/

		$List = explode('\\',$this->Name);
		array_pop($List);

		return $List;
	}

	public function
	GetRelativePath(Int $Current=1, ?String $Filename=NULL):
	String {

			if($Current >= 1)
			$Output = str_repeat('../',($Current));
			else
			$Output = './';

			$Output .= str_replace('\\','/',$this->GetNamespaceName());

			if($Filename) {
				$Output = trim($Output,'/');
				$Output .= "/{$Filename}";
			}
			
			return $Output;
	}

	public function
	GetFilenameHTML():
	String {

		return "{$this->GetNameShort()}.html";
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected
	$Parent = NULL;

	public function
	GetParent():
	?Nether\Senpai\Struct {
		return $this->Parent;
	}

	public function
	SetParent(?Nether\Senpai\Struct $Parent):
	self {
		$this->Parent = $Parent;
		return $this;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected
	$Filename = NULL;

	public function
	GetFilename():
	?String {
		return $this->Filename;
	}

	public function
	SetFilename(String $Filename):
	self {
		$this->Filename = $Filename;
		return $this;
	}

}
