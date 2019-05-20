<?php
/**
 * patConfiguration example
 *
 * $Id: example_define_constants.php 29 2005-03-04 21:25:29Z schst $
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

// define a constant, used in your XML files
define('FOO', 54633);

// parse config file
$conf->loadConfig('example_define_constants.xml');

$config = $conf->getConfigValue();

echo '<pre>';
print_r($config);
echo '</pre>';
