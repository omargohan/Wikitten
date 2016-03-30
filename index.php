<?php

session_start();

require_once 'configuration.php';
require_once 'flight/Flight.php';

$request_uri = parse_url($_SERVER['REQUEST_URI']);
$request_uri = explode("/", $request_uri['path']);
$script_name = explode("/", dirname($_SERVER['SCRIPT_NAME']));

$app_dir = array();
foreach ($request_uri as $key => $value) {
    if (isset($script_name[$key]) && $script_name[$key] == $value) {
        $app_dir[] = $script_name[$key];
    }
}

define('APP_DIR', rtrim(implode('/', $app_dir), "/"));

define('BASE_URL', "//" . $_SERVER['HTTP_HOST'] . APP_DIR);

unset($config_file, $request_uri, $script_name, $app_dir);


require_once __DIR__ . DIRECTORY_SEPARATOR . 'wiki.php';

Flight::route('*', function() {
  Wiki::instance()->dispatch();
});
Flight::start();

