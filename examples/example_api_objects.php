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

class Foo
{
    public function Foo($values)
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}

/**
 * require main class
 */
require_once '../patConfiguration.php';

// create config
$conf = new patConfiguration();
$conf->setConfigDir('./config');

// parse config file
$conf->parseConfigFile('example_api_objects.xml');

// get all config values
$values = $conf->getConfigValue();

echo '<pre>';
var_dump($values);
echo '</pre>';

// get only one config values
$include = $conf->getConfigValue('forum.pat_include');

echo $include;
