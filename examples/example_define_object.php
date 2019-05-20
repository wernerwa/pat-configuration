<?PHP
/**
 * patConfiguration example
 *
 * $Id: example_define_object.php 29 2005-03-04 21:25:29Z schst $
 *	
 * @package		patConfiguration
 * @subpackage	Examples
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

error_reporting(E_ALL);

/**
 * dummy class
 *
 * patConfiguration will instantiate this for you
 */
class page
{
	function page( $props )
	{
		if (!is_array($props)) {
			$props = array( 'id' => 'props' );
		}
		foreach ($props as $prop => $value) {
			$this->$prop = $value;
		}
	}
}
 
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
$conf = new	patConfiguration(
                            array(
                                'configDir' => './config'
                                )
                            );
// parse config file
$conf->loadConfig( 'example_define_object.xml' );

$config	= $conf->getConfigValue();
	
echo '<pre>';
print_r( $config );
echo '</pre>';
?>