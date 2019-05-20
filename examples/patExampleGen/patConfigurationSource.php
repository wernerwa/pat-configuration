<?php
/**
 * Helper script that displays the source string or file
 *
 * $Id: patConfigurationSource.php 31 2005-03-04 21:40:04Z schst $
 *
 * You may pass the following GET parameters to the
 * script:
 *
 * - example => id of the example
 *
 * @author      Stephan Schmidt
 * @package     patConfiguration
 * @subpackage  Examples
 */

if (!isset($_GET['example'])) {
    die('No example selected.');
}
    
    $exampleId = $_GET['example'];

    $extensions = array(
                           'xml', 'php', 'ini', 'wddx'
                       );

    $content = '';
    foreach ($extensions as $ext) {
        $file   = '../config/' . $exampleId . '.' . $ext;
        if (!file_exists($file) || !is_readable($file)) {
            continue;
        }
        $content = $content . "<h2>"  . strtoupper($ext)." Configuration</h2>";
        $content = $content . "<pre>" . htmlspecialchars(str_replace("\t", '    ', file_get_contents($file))) . "</pre>";
    }
    if (strlen($content) > 0) {
        echo $content;
        exit();
    }
    
    // nothing at all.
    die('There is no configuration file for this example.');
