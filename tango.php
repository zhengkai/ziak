<?php
require __DIR__.'/vendor/autoload.php';

header('Server: Tango');

use \Tango\Core\Tango;
use \Tango\Core\Config;
use \Tango\Core\TangoException;

Config::setFile('tango', __DIR__.'/config/tango.php');
Config::setFile('html',  __DIR__.'/config/html.php');
Config::setFile('als',   __DIR__.'/config/als.php');
Config::setFile('log',   __DIR__.'/config/log.php');

TangoException::register();

define('INT32_MAX', 2147483647);
define('NOW', $_SERVER['REQUEST_TIME']);

define('TANGO_SALT',    '8BURQtzPBQ1y6KSrcb5FpM5Q7JU7pOOieYeuXlfUoJYXW4uRyEKum3qcRaMPGs9kCx65Yydis9B');
define('COOKIE_DOMAIN', 'kt.local');

Tango::init();
