<?php
/**
 * Main examples dispatcher script that manages the whole
 * examples navigation and display framework via the
 * {@link patExampleGen} class.
 *
 * @package     patConfiguration
 * @subpackage  Examples
 * @author      Stephan Schmidt <schst@php-tools.net>
 */

   /**
    * The examples prepend file
    */
    include_once '_examples_prepend.php';

    $exampleGen->process();
