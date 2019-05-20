<?PHP
/**
 * patConfiguration example
 *
 * $Id: example_api_cache_option.php 29 2005-03-04 21:25:29Z schst $
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

$conf = new patConfiguration();
$conf->setConfigDir('./config');
$conf->setCacheDir('./cache');

// enabling this option, patConfiguration::loadConfig() is a wrapper for
// patConfiguration::loadCachedConfig()
$conf->setOption('alwaysUseCache', true);

//  parse config file
$conf->loadConfig('example_api_cache_option.xml');

// get all config values
$values = $conf->getConfigValue();

echo '<pre>';
print_r($values);
echo '</pre>';
