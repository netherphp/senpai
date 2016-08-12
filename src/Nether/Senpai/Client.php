<?php

namespace Nether\Senpai;
use \Nether;

class Client
extends Nether\Console\Client {
/*//
the command line interface to get noticed by senpai.
//*/

	public function
	HandleCreate():
	Int {
	/*//
	this will handle the request to bootstrap a new project config file so
	you do not have to write one from scratch.
	//*/

		$Filename = sprintf(
			'%s%s%s',
			getcwd(),
			DIRECTORY_SEPARATOR,
			($this->GetOption('filename') ?? 'senpai.json')
		);
		$File = basename($Filename);
		$Path = dirname($Filename);

		////////

		if(file_exists($Filename))
		if(!$this::PromptEquals('File exists. Overwrite?','[y/n]', 'y')) {
			$this::Message('OK, Goodbye.');
			return 0;
		}

		////////

		$this::Message("Creating config file ({$File})...");

		$Config = new Nether\Senpai\Config;
		$Config->Write($Filename);

		return 0;
	}

	public function
	HandleBuild():
	Int {
	/*//
	this will handle the request to process the current project, generating
	the documentation and writing it out.
	//*/

		echo 'So you want to build documentation...', PHP_EOL;

		return 0;
	}

}
