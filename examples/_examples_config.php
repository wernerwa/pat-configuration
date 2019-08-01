<?php
/**
 * Configuration for the examples - edit this file
 * to set the paths to the needed classes as they
 * should be on your server.
 *
 * @package     patExampleGen
 * @subpackage  Examples
 * @author      Stephan Schmidt <schst@php-tools.net>
 */

    // just as a helper - the base path in which the patTools
    // can be found.
    $basePath = 'pat';

    // this sets the locations for all the needed classes for
    // the examples collection. Update this to make sure all
    // the examples work as they should on your system.
    $neededFiles = array(
        'patErrorManager'   =>  $basePath.'/patErrorManager.php',
    );
