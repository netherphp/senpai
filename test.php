<?php

require(sprintf(
	'%s/vendor/autoload.php',
	dirname(__FILE__)
));

$Files = [];
$Directory = NULL;
$Finder = NULL;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$Directory = new RecursiveDirectoryIterator(
	'src',
	FilesystemIterator::SKIP_DOTS
);

$Finder = new RegexIterator(
	new RecursiveIteratorIterator($Directory),
	'/\.php$/i'
);

foreach($Finder as $File) {
	$Files[] = $File->GetPathname();
}

print_r($Files);
echo PHP_EOL;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

// index all the code.

$Indexer = new Nether\Senpai\Indexers\IndexIndexer;
foreach($Files as $File) {
	$FileIndexer = new Nether\Senpai\Indexers\FileIndexer($File);
	$Result = $FileIndexer->Run();

	$Indexer->AddNamespace($Result->GetNamespace());
}

// print an overview.

$Namespaces = $Indexer->Run();
foreach($Namespaces as $Namespace) {
	echo "Namespace: {$Namespace->GetName()}", PHP_EOL;

	foreach($Namespace->GetTraits() as $Trait) {
		echo "    Trait: {$Trait->GetFullName()}", PHP_EOL;
	}

	foreach($Namespace->GetClasses() as $Class) {
		echo "    Class: {$Class->GetFullName()}", PHP_EOL;
	}
}

