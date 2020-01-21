<?php

$_SERVER['HTTPS'] = '1';
$_SERVER['REQUEST_URI'] = '/webasyst/';

ob_start();
require_once 'C:/OS/domains/ss8.local/index.php';
ob_clean();

require_once '../lib/vendors/Shevsky/Ecom/Autoloader.php';
\Shevsky\Ecom\Autoloader::register();
