<?PHP
/**
 * patConfiguration WDDX Reader
 * 
 * used by the patConfiguration object to read WDDX config files
 *
 * $Id: WDDX.php 22 2005-02-05 15:50:56Z schst $
 *	
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * patConfiguration WDDX Reader
 * 
 * used by the patConfiguration object to read WDDX config files
 *
 * $Id: WDDX.php 22 2005-02-05 15:50:56Z schst $
 *	
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */
class patConfiguration_Reader_WDDX extends patConfiguration_Reader
{

   /**
	* load configuration from a file
	*
	* @access	public
	* @param	string	$configFile		full path of the config file
	* @param	array	$options		various options, depending on the reader
	* @return	array	$config			complete configuration
	*/
	function loadConfigFile( $configFile, $options = array() )
	{
		if (!function_exists('wddx_add_vars')) {
			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_DRIVER_NOT_WORKING,
										"WDDX extension is not installed on your system."
										 );
		}

		$fp   = @fopen( $configFile, 'r' );
		$wddx = fread( $fp, filesize( $configFile ) );
	
		$conf = wddx_deserialize( $wddx );
	
		if ($conf === NULL) {
			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_CONFIG_INVALID,
										"$configFile is no valid WDDX file."
										 );
		}
		return	array(
						'config'        => $conf,
						'externalFiles' => array(),
						'cacheAble'     => true
					);
	}
}
?>