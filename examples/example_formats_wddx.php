<?PHP
/**
 * patConfiguration example
 *
 * $Id: example_formats_wddx.php 48 2005-04-05 21:27:48Z schst $
 *	
 * @package		patConfiguration
 * @subpackage	Examples
 * @author		Stephan Schmidt <schst@php-tools.net>
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
$conf = new	patConfiguration();

$conf->setConfigDir('./config');
	
// set a new value
$conf->setConfigValue( 'new.config.value', 'FOO' );
$conf->setConfigValue( 'another-value', array('FOO','BAR'));

// write a new file
$conf->writeConfigFile( 'example_formats_wddx.wddx', 'wddx');
?>