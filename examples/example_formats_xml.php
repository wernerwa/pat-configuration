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

$conf->setConfigDir('./config');

// set a new value
$conf->setConfigValue('new.config.value', array('a new value', 'another new value'));

// write a new file
$conf->writeConfigFile('example_formats_xml.xml', 'xml', array( 'mode' => 'pretty' ));
