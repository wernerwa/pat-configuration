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
                                    'encoding' => 'ISO-8859-1',
                                   )
);

$conf->setConfigDir('./config');

// parse config file
$conf->parseConfigFile('example_api_save.xml');

// set a new value
$conf->setConfigValue('new.config.value', 'a new value');

// set a new value
$conf->setConfigValue('new.config.again_a_value', 'another new value');

// write a new file
$conf->writeConfigFile('example_api_save_xml.xml', array( 'mode' => 'pretty' ));

// write a WDDX file
$conf->writeConfigFile('example_api_save_wddx.wddx', array( 'comment' => 'This file has been created & saved' ));

// write an INI file
$conf->writeConfigFile('example_api_save_ini.ini', array());
