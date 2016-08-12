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

		echo 'So you want to create a new project...', PHP_EOL;

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
