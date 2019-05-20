<?php
/**
 * package.xml generation file for patConfiguration
 *
 * This file is executed by createSnaps.php to
 * automatically create a package that can be
 * installed via the PEAR installer.
 *
 * $Id: package.php 54 2006-02-03 08:01:11Z argh $
 *
 * @author      Stephan Schmidt <schst@php-tools.net>
 * @package     patConfiguration
 * @subpackage  Tools
 */

$version = '2.0.0';

/**
 * uses PackageFileManager
 */
require_once 'PEAR/PackageFileManager.php';

/**
 * current state
 */
$state = 'stable';

/**
 * release notes
 */
$notes = <<<EOT
patConfiguration has been refactored with version 2.0 is not fully downwards compatible.
Changes include:
- driver based architecture
- uses patError
- support for INI and WDDX has been added
- huge improvements for the define tag
- no more namespace handlers by supplying extensions
- license has been changed to PHP License

Changes since patConfiguration 2.0.0b1:
- fixed bug #104 (wrong parameter name) (schst)
- added an error check in the writeConfigFile() method in case the target file cannot be written (argh)
- fixed check for the writer's serializeConfig() method return values (argh)
- added new <define child="..." default="..." type="..."/> to define default values of non-existent child-tags

Changes since patConfiguration 2.0.0b2:
- added missing examples
- prevent PHP-Notices with issetConfigValue() (bug #145, contributed by Frank Kleine)
- fixed and improved the "could not load xxx for filetype" error message (argh)
EOT;

/**
 * package description
 */
$description = <<<EOT
patConfiguration is an interface to read and write different types of configuration files.
Formats include:
- read/write XML files
- read/write INI files
- read/write WDDX files
- write PHP files.
To improve performance, patConfiguration is able to use a caching system instead of parsing the files everytime they are needed.

It is best known for its support for XML based configurations, as you may define your own tags
and specify which PHP type they represent. Features include:
- create any PHP type (from boolean to objects)
- infinite nesting level
- define namespaces, tags and attributes
- choose how whitespace is handled
- xInclude
- use PHP constants in you configuration files
EOT;

$package = new PEAR_PackageFileManager();

$result = $package->setOptions(array(
    'package'           => 'patConfiguration',
    'summary'           => 'Interface for configuration files.',
    'description'       => $description,
    'version'           => $version,
    'state'             => $state,
    'license'           => 'PHP',
    'filelistgenerator' => 'file',
    'ignore'            => array( 'package.php', 'autopackage.php', 'package.xml', 'package2.xml', '.cvsignore', '.svn' ),
    'notes'             => $notes,
    'simpleoutput'      => true,
    'baseinstalldir'    => 'pat',
    'packagedirectory'  => './',
    'dir_roles'         => array(
                                 'docs' => 'doc',
                                 'examples' => 'doc',
                                 'tests' => 'test',
                                 )
    ));

if (PEAR::isError($result)) {
    echo $result->getMessage();
    die();
}

$package->addMaintainer('schst', 'lead', 'Stephan Schmidt', 'schst@php-tools.net');

$package->addDependency('patError', '', 'has', 'pkg', false);
$package->addDependency('php', '4.3.0', 'ge', 'php', false);

if (isset($_GET['make']) || (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'make')) {
    $result = $package->writePackageFile();
} else {
    $result = $package->debugPackageFile();
}

if (PEAR::isError($result)) {
    echo $result->getMessage();
    die();
}
