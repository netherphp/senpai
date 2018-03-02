<?php

require(sprintf(
	'%s/vendor/autoload.php',
	dirname(__FILE__)
));

$Files = [
	'class.php'
];

foreach($Files as $File) {
	$Indexer = new Nether\Senpai\Indexers\FileIndexer($File);
	$Result = $Indexer->Run();
	print_r($Result);
}

