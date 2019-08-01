<?php
/**
 * Definitions for the examples of the different formats
 *
 * @package     patConfiguration
 * @subpackage  Examples
 * @author      Stephan Schmidt <schst@php-tools.net>
 */

    $sections['Formats'] = array(
        'descr'     =>  'These examples show you the different formats that patConfiguration is capable of reading and/or writing.',
        'basename'  =>  'example_formats_',
        'pages'     =>  array(
            'xml' => array(
                'title' =>  'XML',
                'descr' =>  'The most powerful reader and writer are the XML reader and writer.'
            ),
            'ini' => array(
                'title' =>  'INI',
                'descr' =>  'patConfiguration is able to read and write INI files.'
            ),
            'wddx' => array(
                'title' =>  'WDDX',
                'descr' =>  'patConfiguration is able to read and write WDDX files.'
            ),
            'php' => array(
                'title' =>  'PHP',
                'descr' =>  'patConfiguration is able to write PHP files.'
            ),
        ),
    );
