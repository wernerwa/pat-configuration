<?PHP
/**
 * patConfiguration example
 *
 * $Id: example_api_create.php 30 2005-03-04 21:35:42Z schst $
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
$result = $conf->setConfigDir( './config' );

$conf->setConfigValue('html.table.border', '1');
$conf->setConfigValue('html.table.bordercolor', '#000000');
$conf->setConfigValue('html.table.padding', '3');
$conf->setConfigValue('html.table.spacing', '1');
$conf->setConfigValue('html.table.width', '100%');

$conf->writeConfigFile('example_api_create.xml', 'xml', array('mode'=>'pretty') );
print "XML configuration file created<br />";
$conf->writeConfigFile('example_api_create.ini', 'ini', array('mode'=>'pretty') );
print "INI configuration file created<br />";
$conf->writeConfigFile('example_api_create.php', 'php');
print "PHP configuration file created<br />";
$conf->writeConfigFile('example_api_create.wddx', 'wddx');
print "WDDX configuration file created<br />";
?>