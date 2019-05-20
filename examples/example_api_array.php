<?php
/**
 * patConfiguration example
 *
 * $Id: example_api_array.php 29 2005-03-04 21:25:29Z schst $
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
$result = $conf->parseConfigFile('example_api_array.xml');

echo "<b>Please scroll down to see the different ways to access the data.</b><br />\n";

// get all config values
echo "\$conf->getConfigValue()<br />\n";
$values = $conf->getConfigValue();

echo '<pre>';
print_r($values);
echo '</pre>';

// get one value by path
echo "\$conf->getConfigValue('foo.bar')<br />\n";
$values = $conf->getConfigValue('foo.bar');

echo '<pre>';
print_r($values);
echo '</pre>';

// get one value by path and array syntax
echo "\$conf->getConfigValue('foo.bar[array1]')<br />\n";
$values = $conf->getConfigValue('foo.bar[array1]');

echo '<pre>';
print_r($values);
echo '</pre>';

// get one value by path and array syntax
echo "\$conf->getConfigValue('foo.bar[array1][0]')<br />\n";
$values = $conf->getConfigValue('foo.bar[array1][0]');

echo '<pre>';
print_r($values);
echo '</pre>';
