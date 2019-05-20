<?php
/**
 * Definitions for the examples on misc features of patConfiguration
 *
 * $Id: _examples_sections_misc.php 34 2005-03-05 16:55:02Z schst $
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
