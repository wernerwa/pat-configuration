<?php
/**
 * patConfiguration Writer
 *
 * abstract base class for config writers
 *
 * $Id: Writer.php 35 2005-03-06 10:01:02Z schst $
 *
 * @abstract
 * @package     patConfiguration
 * @subpackage  Writer
 * @author      Stephan Schmidt <schst@php-tools.net>
 */

/**
 * patConfiguration Writer
 *
 * abstract base class for config writers
 *
 * $Id: Writer.php 35 2005-03-06 10:01:02Z schst $
 *
 * @package     patConfiguration
 * @subpackage  Writer
 * @author      Stephan Schmidt <schst@php-tools.net>
 */
class patConfiguration_Writer
{
    /**
     * reference to patConfiguration object
     * @var object patConfiguration
     */
    public $configOb = null;

    /**
     * set reference to the patConfiguration object
     *
     * @access  public
     * @param   object patConfiguration
     */
    public function setConfigReference(&$config)
    {
        $this->configObj = &$config;
    }

    /**
     * serialize the config
     *
     * @abstract
     * @access  public
     * @param   array   $config     config to serialize
     * @param   array   $options    options for the serialization
     * @return  string  $content    xml representation
     */
    public function serializeConfig($config, $options)
    {
    }
}
