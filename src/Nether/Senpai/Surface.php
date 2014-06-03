<?php

namespace Nether\Senpai;

use \Nether;

Nether\Option::Set([
	'surface-theme-root' => dirname(dirname(dirname(dirname(__FILE__)))).'/themes',
	'surface-theme' => 'senpai-html'
]);

class Surface extends Nether\Surface {

}
