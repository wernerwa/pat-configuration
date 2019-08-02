# patConfiguration

patConfiguration is an interface to access (read AND write) XML based configuration files via PHP. Furthermore it can convert your XML config files into PHP config files.
Furthermore it can convert your XML config files into PHP config files. With the use of extensions it allows you to retrieve fully configured objects from your configuration.

| **Current Version** | v2.0.0          |
| ------------------- |:---------------:|
| **Released**        | 2005-08-13      |
| **Maintainer**      | Stephan Schmidt |
| **Developer(s)**    | Stephan Schmidt |
| **License**         | LGPL            |

The former web page www.php-tools.net is archived at [archive.org](https://web.archive.org/web/20140916050733/http://www.php-tools.net/site.php?&PHPSESSID=809e21db952802c1d124b7b07fc14bdf&file=patConfiguration/overview.xml)

## Why should I use patConfiguration?
Using XML based configuration files has several advantages: Your configuration can be edited using any text editor, they can easily be validated using a dtd or xml schema, they are easy to read and configurations can easily be extended.
## Features
patConfiguration is quite similar to the configuration reader i3conf by iternum GmbH, which was implemented in Java, but extends the features.

**Currently the following features are implemented:**

* several types for configuration values are supported: string, boolean, double, integer and array.
* Automatic conversion from strings to booleans, integers, arrays or doubles
* identify a configuration value via its path (similar to DOM or XPath)
* references to other configuration options in your config file, e.g. base a directory name on a basedir set in the same configuration
* extend patConfiguration so it returns fully operational objects instead of simple config values (extension for patDbc, patUser and patTemplate are already implemented)
* assign a certain namespace to your custom extensions
* dynamically include your extensions (or any other files) via a configuration option
* automatic creation of multidimensional array
* unlimited tag depth
* fetch several config options using wildcards
* direct access to values in an array
* modify and save config files in XML (or even create new ones)
* convert XML config files into PHP config files where all options are stored in an array
* uses caching, so XML files do not have to be parsed every time
* use external entities to include other XML configurations
* easy-to-use API
### patConfiguration in Java
I ported the XML-functionality of patConfiguration to Java. The project [XJConf](https://github.com/schst/xjconf-java) is available from my website <java.schst.net>.
patConfiguration - interface for reading and writing XML config files

Copyright (c) 2001-2003 by Stephan Schmidt <schst@php-tools.net>
download at http://www.php-tools.net

## CAUTION:
This is patConfiguration 2.0.0
patConfiguration is now driver-based and not restricted to XML files anymore.
Furthermore it uses patErrorManager to handle errors.
That means it breaks BC in most cases!

You downloaded this as a CVS snapshot. This version still contains
some bugs and should not be used in a production environment.

This program and all associated files are released under the GNU Lesser Public License,
see lgpl.txt for details!
