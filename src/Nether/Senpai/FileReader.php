<?php

namespace Nether\Senpai;

class FileReader {

	use Traits\FilenameProperty;
	use Traits\CommentArrayProperty;
	use Traits\DataProperty;

	public function
	__construct(String $Filename) {
		$this->Filename = $Filename;
		$this->Data = '';

		$this->Read();
		return;
	}

	protected function
	Read():
	Void {

		$File = fopen($this->Filename,'r');
		$Num = 0;
		$Noticed = FALSE;
		$Offset = 0;
		$Buffer = '';
		$Start = 0;

		while($Line = fgets($File)) {
			$Num++;

			// notice the start of blocks.
			if(!$Noticed && ($Offset = strpos($Line,'/*//')) !== FALSE) {
				$Noticed = TRUE;
				$Start = $Num;
				$Buffer = ltrim($Line);
			}

			// notice the content of blocks.
			elseif($Noticed && ($Offset = strpos($Line,'//*/')) === FALSE) {
				$Buffer .= ltrim($Line);
			}

			// notice the end of blocks.
			elseif($Noticed && ($Offset = strpos($Line,'//*/')) !== FALSE) {
				$Buffer .= trim($Line);
				$this->Comments[$Start] = $Buffer;
				$Noticed = FALSE;
				$Start = 0;
				$Buffer = '';
			}

			$this->Data .= $Line;
		}

		return;
	}

}
