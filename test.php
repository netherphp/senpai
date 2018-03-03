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

$Files = [
	//'class.php',
	//'src/Nether/Senpai/Statement.php',
	//'src/Nether/Senpai/Statements/ClassStatement.php',
	//'src/Nether/Senpai/Statements/MethodStatement.php'
];


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

/*
print_r($Files);
echo PHP_EOL;
*/

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
	echo PHP_EOL;

	echo "namespace\n{$Namespace->GetName()} {\n", PHP_EOL;

	foreach($Namespace->GetTraits() as $Trait) {
		echo "\ttrait\n\t{$Trait->GetName()} {\n", PHP_EOL;

		if($Trait->GetAnnotation()->GetData())
		echo "\t\t".str_replace("\n","\n\t\t",$Trait->GetAnnotation()->GetData())."\n\n";

		foreach($Trait->GetMethods() as $Method) {
			echo "\t\tmethod {$Method->GetAccessWords()}\n";
			echo "\t\t{$Method->GetName()};\n", PHP_EOL;

			if($Method->GetAnnotation()->GetData())
			echo "\t\t".str_replace("\n","\n\t\t",$Method->GetAnnotation()->GetData())."\n\n";
		}

		echo "\t};\n", PHP_EOL;
	}

	foreach($Namespace->GetClasses() as $Class) {
		echo "\tclass\n\t{$Class->GetName()} {\n", PHP_EOL;

		if($Class->GetAnnotation()->GetData())
		echo "\t\t".str_replace("\n","\n\t\t",$Class->GetAnnotation()->GetData())."\n\n";

		foreach($Class->GetMethods() as $Method) {
			echo "\t\tmethod {$Method->GetAccessWords()}\n";
			echo "\t\t{$Method->GetName()};\n", PHP_EOL;

			if($Method->GetAnnotation()->GetData())
			echo "\t\t".str_replace("\n","\n\t\t",$Method->GetAnnotation()->GetData())."\n\n";
		}

		echo "\t};\n", PHP_EOL;
	}
	echo "};\n";

	echo PHP_EOL;
}

