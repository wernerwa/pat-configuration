<?php
/**
 * Main prepend file for the examples framework, sets
 * error reporting and initializes the patExampleGen
 * class that manages the whole framework.
 *
 * $Id: _examples_prepend.php 29 2005-03-04 21:25:29Z schst $
 *
 * @package     patExampleGen
 * @subpackage  Examples
 * @author      Sebastian Mordziol <argh@php-tools.net>
 */

    error_reporting(E_ALL);

   /**
    * The examples needed files list
    */
    include_once '_examples_config.php';

   /**
    * The examples definitions
    */
    include_once '_examples_sections.php';

   /**
    * The examples manager class patExampleGen
    */
    include_once 'patExampleGen/patExampleGen.php';

   /**
    * Custom functions for the current examples collection
    */
    include_once '_examples_customFunctions.php';

    // set up the examples generator with the section data
    $exampleGen =& new patExampleGen;
    $exampleGen->setAppName($appName);
    $exampleGen->setAppDescription($appDesc);
    $exampleGen->setAppForumId($appForumId);
    $exampleGen->setSections($sections);
    $exampleGen->setTabs($tabs);

    // turn on error handling if not explicitly turned off
if (!isset($errorHandling) || $errorHandling != 'off') {
    /**
     * patErrorManager class
     */
    require_once $neededFiles['patErrorManager'];

    patErrorManager::setErrorHandling(E_ALL, 'callback', array( $exampleGen, 'displayError' ));
}
