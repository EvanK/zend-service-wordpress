<?php
/*
 * Start output buffering
 */
ob_start();

/*
 * Include PHPUnit dependencies
 */
require_once 'PHPUnit/Framework.php';

/*
 * Set error reporting to the level to which Zend Framework code must comply.
 */
error_reporting( E_ALL | E_STRICT );

/*
 * Prepend the library/ directory to the include_path.
 */
$path = array(
    dirname(__FILE__) . '/../../library',
    get_include_path()
    );
set_include_path(implode(PATH_SEPARATOR, $path));

/*
 * Load the user-defined test configuration file, if it exists
 */
$tests = dirname(__FILE__);
if (is_readable($tests . DIRECTORY_SEPARATOR . 'TestConfiguration.php')) {
    require_once $tests . DIRECTORY_SEPARATOR . 'TestConfiguration.php';
} else {
    require_once $tests . DIRECTORY_SEPARATOR . 'TestConfiguration.php.dist';
}
