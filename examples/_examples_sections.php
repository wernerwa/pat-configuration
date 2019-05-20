<?php
/**
 * Definitions of all included examples
 *
 * $Id: _examples_sections.php 34 2005-03-05 16:55:02Z schst $
 *
 * @package		patConfiguration
 * @subpackage	Examples
 * @author		Stephan Schmidt <schst@php-tools.net>
 */
 
 	$appName	=	'patConfiguration';
	$appForumId	=	3;
	$appDesc	=	'Please note that even though you can directly jump to any '
				.	'section in the examples, <b>we recommend you go through them '
				.	'sequentially</b> as they are presented here, especially if you '
				.	'are new to patForms. We have set the order of the examples '
				.	'so that you will not miss out any important details, and learn '
				.	'how to use the needed classes tutorial-like by reading the comments in '
				.	'the examples\' source code.<br/>';
				
	$tabs = array(
		'output' => array(
			'title'		=>	'Output',
			'type'		=>	'output',
			'file'		=>	'$exampleFile',
			'default'	=>	true,
		),
		'source' => array(
			'title' 	=>	'PHP Source',
			'type'		=>	'phpSource',
			'file'		=>	'$exampleFile',
			'default'	=>	false,
		),
	    'config' => array(
            'title'     =>    'Config file',
            'type'      =>    'output',
            'file'      =>    'patExampleGen/patConfigurationSource.php?example=$exampleId',
            'default'   =>    false,
        ),
	);
				
	$sections = array();
	
	include '_examples_sections_api.php';
	include '_examples_sections_define.php';
	include '_examples_sections_misc.php';
	include '_examples_sections_formats.php';
?>