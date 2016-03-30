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

Flight::before('start', function() {
  if (!function_exists("finfo_open")) {
      die("<p>Please enable the PHP Extension <code style='background-color: #eee; border: 1px solid #ccc; padding: 3px; border-radius: 3px; line-height: 1;'>FileInfo.dll</code> by uncommenting or adding the following line:</p><pre style='background-color: #eee; border: 1px solid #ccc; padding: 5px; border-radius: 3px;'><code><span style='color: #999;'>;</span>extension=php_fileinfo.dll <span style='color: #999; margin-left: 25px;'># You can just uncomment by removing the semicolon (;) in the front.</span></code></pre>");
  }

  if(isset($_REQUEST['a']) && $_REQUEST['a'] === 'authenticate') {
    Wiki::instance()->authenticateAction();
    exit();
  }

  if(PasswordAuthentication::isAuthenticationRequired()) {
    Wiki::instance()->authAction();
    exit();
  }
});

Flight::route('POST /create', function() {
  Wiki::instance()->createAction();
});
Flight::route('POST /edit', function() {
  Wiki::instance()->editAction();
});
Flight::route('POST /delete', function() {
  Wiki::instance()->deleteAction();
});
Flight::route('*', function() {
  if (!isset($_REQUEST['a'])) {
    Wiki::instance()->indexAction();
    exit();
  }

  Wiki::instance()->dispatch();
});

Flight::start();

?>
