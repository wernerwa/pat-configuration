<?PHP
/**
 * patConfiguration reader for INI files
 *
 * used by the patConfiguration object to read INI config files
 *
 * $Id: INI.php 22 2005-02-05 15:50:56Z schst $
 *	
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * patConfiguration reader for INI files
 *
 * used by the patConfiguration object to read INI config files
 *
 * $Id: INI.php 22 2005-02-05 15:50:56Z schst $
 *	
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */
class patConfiguration_Reader_INI extends patConfiguration_Reader
{
   /**
	*	load configuration from a file
	*
	*	@access	public
	*	@param	string	$configFile		full path of the config file
	*	@param	array	$options		various options, depending on the reader
	*	@return	array	$config			complete configuration
	*/
	function loadConfigFile( $configFile, $options = array() )
	{
		$conf = parse_ini_file( $configFile );
	
		return	array(
						'config'        => $conf,
						'externalFiles' => array(),
						'cacheAble'     => true
					);
	}
}
?>