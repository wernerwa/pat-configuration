<?php
/**
 * patConfiguration example
 *
 * @package     patConfiguration
 * @subpackage  Examples
 * @author      Stephan Schmidt <schst@php-tools.net>
 */

error_reporting(E_ALL);

/**
 * requires patErrorManager
 * make sure that it is in your include path
 */
require_once 'pat/patErrorManager.php';

patErrorManager::setErrorHandling(E_ALL, 'verbose');

/**
 * require main class
 */
require_once '../patConfiguration.php';

// create config
$conf = new patConfiguration(
    array(
                                'configDir' => './config'
                                )
);
// parse config file
$conf->loadConfig('example_define_basic.xml');


$config = $conf->getConfigValue();

echo '<pre>';
print_r($config);
echo '</pre>';
