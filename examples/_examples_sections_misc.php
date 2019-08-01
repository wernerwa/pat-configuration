<?php
/**
 * Definitions for the examples on misc features of patConfiguration
 *
 * @package     patConfiguration
 * @subpackage  Examples
 * @author      Stephan Schmidt <schst@php-tools.net>
 */

    $sections['Misc'] = array(
        'descr'     =>  'Miscellaneous features',
        'basename'  =>  'example_misc_',
        'pages'     =>  array(
            'whitespace' => array(
                'title' =>  'xml:space',
                'descr' =>  'Using xml:space to define the whitespace treatment. THIS IS NOT WORKING IN PHP5.'
            ),
            'xinc' => array(
                'title' =>  'xInc',
                'descr' =>  'Including XML files in your configuration.'
            ),
        ),
    );
