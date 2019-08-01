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
$conf = new patConfiguration();
$result = $conf->setConfigDir('./config');

// parse config file
$result = $conf->parseConfigFile('example_define_auto.xml');

//  get all config values
$values = $conf->getConfigValue();

echo '<pre>';
var_dump($values);
echo '</pre>';
