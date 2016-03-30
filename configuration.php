<?php

// Conditionally load configuration from a config.php file in the conf subdirectory, if it exists.
if (is_file($config_file = __DIR__ . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'config.php')) {
    require_once $config_file;
}

if (!defined('APP_NAME')) {
    define('APP_NAME', 'Wikitten');
}

if (!defined('LIBRARY')) {
    define('LIBRARY', __DIR__ . DIRECTORY_SEPARATOR . 'library');
}

if (!defined('DEFAULT_FILE')) {
    define('DEFAULT_FILE', 'index.md');
}

if (!defined('USE_WIKITTEN_LOGO')) {
    define('USE_WIKITTEN_LOGO', true);
}

if (!defined('USE_DARK_THEME')) {
    define('USE_DARK_THEME', false);
}

if (!defined('USE_PAGE_METADATA')) {
    define('USE_PAGE_METADATA', true);
}

if (!defined('ENABLE_EDITING')) {
    define('ENABLE_EDITING', false);
}

if (!defined('ENABLE_PASTEBIN')) {
    define('ENABLE_PASTEBIN', false);
}

if (!defined('PASTEBIN_API_KEY')) {
    define('PASTEBIN_API_KEY', false);
}

if(!defined('ENABLE_CREATING')) {
    define('ENABLE_CREATING', false);
}

if(!defined('ENABLE_DELETION')) {
    define('ENABLE_DELETION', false);
}

if(!defined('ENABLE_PASSWORD_AUTHENTICATION')) {
    define('ENABLE_PASSWORD_AUTHENTICATION', false);
}

define('PLUGINS', __DIR__ . DIRECTORY_SEPARATOR . 'plugins');
define('APP_DIR', '');

?>
