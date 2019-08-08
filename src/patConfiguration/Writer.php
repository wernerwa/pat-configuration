<?php
/**
 * patConfiguration Writer
 *
 * abstract base class for config writers
 *
 * @abstract
 *
 * @author      Stephan Schmidt <schst@php-tools.net>
 */

/**
 * patConfiguration Writer
 *
 * abstract base class for config writers
 *
 * @author      Stephan Schmidt <schst@php-tools.net>
 */
class patConfiguration_Writer
{
    /**
     * reference to patConfiguration object
     *
     * @var object patConfiguration
     */
    public $configOb = null;

    /**
     * set reference to the patConfiguration object
     *
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
     *
     * @param array $config  config to serialize
     * @param array $options options for the serialization
     *
     * @return string $content    xml representation
     */
    public function serializeConfig($config, $options)
    {
    }
}
