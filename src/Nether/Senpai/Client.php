<?php

namespace Nether\Senpai;
use \Nether;

class Client
extends Nether\Console\Client {
/*//
the command line interface to get noticed by senpai.
//*/

	public function
	HandleHelp():
	Int {
	/*//
	do what you want cause a pirate is free, as long as it conforms to these
	available choices.
	//*/

		$this::Messages(
			'Nether\\Senpai Autodoc Engine',
			'=============================',
			'',

			'> create [options]',
			'  bootstrap a new senpai.json project file with the default values.',
			'',
			'      --project=senpai.json',
			'      the file name that should be used for the project file.',
			'',

			'> build [options]',
			'  compile the documentation data as described by the senpai.json file.',
			'',
			'      --project=senpai.json',
			'      if you have multiple projects build this specific one.',
			'',

			'> render [options]',
			'  generate the usable documentation. will automatically run the build step if the documentation data is missing.',
			'',
			'      --project=senpai.json',
			'      if you have multiple projects render this specific one.',
			''
		);

		return 0;
	}

	public function
	HandleCreate():
	Int {
	/*//
	this will handle the request to bootstrap a new project config file so
	you do not have to write one from scratch.
	//*/

		$Filename = $this->GetFilename();
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

		$Filename = $this->GetFilename();
		$File = basename($Filename);
		$Path = dirname($Filename);

		try {
			$Builder = Nether\Senpai\Builder::GetFromFile($Filename);
		}

		catch(Exception $Error) {
			$this::Message('Something Happened:');
			$this::Message($Error->GetMessage());
			return 1;
		}

		$this::Message("Building Project {$Filename}");
		$Builder->Run();

		return 0;
	}

	public function
	HandleRender():
	Int {

		$Filename = $this->GetFilename();
		$File = basename($Filename);
		$Path = dirname($Filename);

		try {
			$Renderer = Nether\Senpai\Renderer::GetFromFile($Filename);
		}

		catch(Exception $Error) {
			$this::Message('Something Happened:');
			$this::Message($Error->GetMessage());
			return 1;
		}

		$this::Message("Rendering Project {$Filename}");
		$Renderer->Run();

		return 0;
	}

	protected function
	GetFilename():
	String {
	/*//
	get the config filename from either an option or the default file we will
	use for saving options.
	//*/

		return sprintf(
			'%s%s%s',
			getcwd(),
			DIRECTORY_SEPARATOR,
			($this->GetOption('project') ?? $this->GetOption('filename') ?? 'senpai.json')
		);
	}

}
