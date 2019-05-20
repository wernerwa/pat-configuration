<?PHP
/**
 * patConfiguration example
 *
 * $Id: example_api_errors.php 29 2005-03-04 21:25:29Z schst $
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

/**
 * require main class
 */
require_once '../patConfiguration.php';

patErrorManager::setErrorHandling(E_ALL, 'ignore');

// create config
$conf = &new    patConfiguration(array(
                                            'configDir'     =>  './config',
                                            'errorHandling' =>  'return'
                                        ));

// parse config file
$result = $conf->parseConfigFile('notExisting.xml');

echo '<pre>';
print_r($result);
echo '</pre>';

echo 'set error handling to \'die\'.<br>';

// set different error handling
patErrorManager::setErrorHandling(E_ALL, 'die');

// parse config file
$result = $conf->parseConfigFile('notExisting.xml');
