<?php
/**
 * Main examples dispatcher script that manages the whole 
 * examples navigation and display framework via the 
 * {@link patExampleGen} class.
 *
 * $Id: index.php 29 2005-03-04 21:25:29Z schst $
 *
 * @package		patConfiguration
 * @subpackage	Examples
 * @author		Stephan Schmidt <schst@php-tools.net>
 */
 
   /**
	* The examples prepend file
	*/
 	include_once '_examples_prepend.php';

	$exampleGen->process();
?>
