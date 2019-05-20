<?php
/**
 * Definitions for the examples on the patConfiguration API
 *
 * $Id: _examples_sections_api.php 33 2005-03-05 15:28:08Z schst $
 *
 * @package     patConfiguration
 * @subpackage  Examples
 * @author      Stephan Schmidt <schst@php-tools.net>
 */

    $sections['API'] = array(
        'descr'     =>  'The API of patConfigution',
        'basename'  =>  'example_api_',
        'pages'     =>  array(
            'basic' => array(
                'title' =>  'Basic usage',
                'descr' =>  'This example shows how to read a configuration file and extract configuration values.'
            ),
            'array' => array(
                'title' =>  'Accessing arrays',
                'descr' =>  'This example shows you how to access a part of an array.'
            ),
            'objects' => array(
                'title' =>  'Building objects',
                'descr' =>  'This example shows you how to create objects using patConfiguration.'
            ),
            'cache' => array(
                'title' =>  'Caching',
                'descr' =>  'This example shows you how to use loadChachedConfig().'
            ),
            'cache_option' => array(
                'title' =>  'Global caching',
                'descr' =>  'This example shows you how enable caching for all config files.'
            ),
            'save' => array(
                'title' =>  'Saving config files',
                'descr' =>  'This example shows you how to load and save configuration files.'
            ),
            'create' => array(
                'title' =>  'Create Files',
                'descr' =>  'This example shows how to create new configuration files from scratch.'
            ),
            'errors' => array(
                'title' =>  'Error-Handling',
                'descr' =>  'This example shows how to use patErrorManager.'
            ),
        ),
    );
