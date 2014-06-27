<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

if (@!include __DIR__ . '/../../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

// configure environment
Tester\Environment::setup();
class_alias('Tester\Assert', 'Assert');
date_default_timezone_set('Europe/Prague');

$temp = __DIR__ . '/../temp/';
if (file_exists($temp)) {
	\Tester\Helpers::purge($temp);
} else {
	mkdir($temp);
}


// create temporary directory
define('TEMP_DIR', $temp . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid()));
Tester\Helpers::purge(TEMP_DIR);

$_SERVER = array_intersect_key($_SERVER, array_flip(array('PHP_SELF', 'SCRIPT_NAME', 'SERVER_ADDR', 'SERVER_SOFTWARE', 'HTTP_HOST', 'DOCUMENT_ROOT', 'OS', 'argc', 'argv')));
$_SERVER['REQUEST_TIME'] = 1234567890;
$_ENV = $_GET = $_POST = array();

if (extension_loaded('xdebug')) {
	xdebug_disable();
	Tester\CodeCoverage\Collector::start(__DIR__ . '/coverage.dat');
}

function id($val)
{
	return $val;
}

function getContainer()
{
	$configurator = new \Nette\Configurator();
	$configurator->setTempDirectory(__DIR__ . '/../temp');
	$configurator->addConfig(__DIR__ . '/../data/config.neon');

	return $configurator->createContainer();
}

function run(Tester\TestCase $testCase)
{
	$testCase->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : null);
}