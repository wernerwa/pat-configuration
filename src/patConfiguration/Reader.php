<?php
/**
 * patConfiguration reader base class
 *
 * $Id: Reader.php 45 2005-04-05 19:48:28Z schst $
 *
 * @package     patConfiguration
 * @subpackage  Reader
 * @author      Stephan Schmidt <schst@php-tools.net>
 * @link        http://www.php-tools.net
 * @copyright   PHP Application Tools
 */

/**
 * patConfiguration reader base class
 *
 * $Id: Reader.php 45 2005-04-05 19:48:28Z schst $
 *
 * @package     patConfiguration
 * @subpackage  Reader
 * @author      Stephan Schmidt <schst@php-tools.net>
 */
class patConfiguration_Reader
{
    /**
     * reference to patConfiguration object
     * @var object patConfiguration
     */
    public $configObj = null;

    /**
     * set reference to the patConfiguration object
     *
     * @access  public
     * @param   object  &$config
     */
    public function setConfigReference(&$config)
    {
        $this->configObj = &$config;
    }

    /**
     * load configuration from a file
     *
     * @access  public
     * @abstract
     * @param   string  $configFile     full path of the config file
     * @param   array   $options        various options, depending on the reader
     * @return  array   $config         complete configuration
     */
    public function loadConfigFile($configFile, $options = array())
    {
    }
}
