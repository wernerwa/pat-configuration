<?php
/**
 * Helper file for the examples collection that displays a list of
 * example files that may be missing or are not referenced to in the
 * examples section definitions.
 *
 * $Id: fileUsage.php 29 2005-03-04 21:25:29Z schst $
 * 
 * @access		public
 * @package		patExampleGen
 * @subpackage	Examples
 * @version		0.1
 * @author		Sebastian Mordziol <s.mordziol@metrix.de>
 * @link		http://www.php-tools.net
 */
 
   /**
    * Main prepend file
    */
 	require_once 'prepend.php';
 
	$knownExamples = array();
	$notReferenced = array();
	$missing = array();
 
	foreach( $sections as $sectionID => $sectionDef )
	{
		foreach( $sectionDef['pages'] as $pageID => $pageDef )
		{
			$file = $sectionDef['basename'].$pageID.'.php';
			
			array_push( $knownExamples, $file );
			
			if( !file_exists( '../'.$file ) )
			{
				array_push( $missing, $file );
			}
		}
	}
 
	$d = dir( '../' );
	while( false !== ( $entry = $d->read() ) ) 
	{
		if( stristr( $entry, 'example_' ) && stristr( $entry, '.php' ) && !in_array( $entry, $knownExamples ) )
		{
			array_push( $notReferenced, $entry );
		}
	}
	$d->close();

	echo '<b>The following examples are not referenced to in the sections:</b><br/><br/>';
	if( empty( $notReferenced ) )
	{
		echo '<i>none</i>';
	}
	else
	{
		echo implode( '<br>', $notReferenced );
	}
	
	echo '<br/><br/><b>The following examples are missing:</b><br/><br/>';
	if( empty( $missing ) )
	{
		echo '<i>none</i>';
	}
	else
	{
		echo implode( '<br>', $missing );
	}
	
?>