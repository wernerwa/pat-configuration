<?php
/**
 * Definitions for the examples of the <define/> tag
 *
 * $Id: _examples_sections_define.php 43 2005-04-05 12:08:26Z schst $
 *
 * @package     patConfiguration
 * @subpackage  Examples
 * @author      Stephan Schmidt <schst@php-tools.net>
 */

    $sections['Define'] = array(
        'descr'     =>  'These examples show you how to use the <define/> tag of patCOnfiguration.',
        'basename'  =>  'example_define_',
        'pages'     =>  array(
            'basic' => array(
                'title' =>  'Basic usage',
                'descr' =>  'This example shows how to use the basic feature set of the define tag.'
            ),
            'reserved' => array(
                'title' =>  'Reserved tags',
                'descr' =>  'Reserved tags may be redefined in a different namespace.'
            ),
            'auto' => array(
                'title' =>  'Auto type',
                'descr' =>  'Using the auto-type, patConfiguration will decide which type to use for a value.'
            ),
            'constants' => array(
                'title' =>  'Using constants',
                'descr' =>  'This example shows how to use contants in defined tags.'
            ),
            'object' => array(
                'title' =>  'Creating objects',
                'descr' =>  'This example shows you how to use objects with tag definitions.'
            ),
            'content' => array(
                'title' =>  'Defining the content',
                'descr' =>  'This shows you how to copy the content to a key of the tag.'
            ),
            'children' => array(
                'title' =>  'Defining children',
                'descr' =>  'This shows you how to create default values for children tags that are ommitted.'
            ),
            'external' => array(
                'title' =>  'Using external definition files',
                'descr' =>  'You may store your tag defintions in external files to load them from a central location.'
            ),
        ),
    );
