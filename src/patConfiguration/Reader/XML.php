<?PHP
/**
 * patConfiguration XML Reader
 *
 * used by the patConfiguration object to read XML config files
 *
 * $Id: XML.php 43 2005-04-05 12:08:26Z schst $
 * 
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * Trying to define reserved tag
 */
define('PATCONFIGURATION_READER_XML_ERROR_RESERVED_TAG', 'PCF::R::XML::1');

/**
 * patConfiguration XML Reader
 *
 * used by the patConfiguration object to read XML config files
 *
 * $Id: XML.php 43 2005-04-05 12:08:26Z schst $
 * 
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */
class patConfiguration_Reader_XML extends patConfiguration_Reader
{
   /**
	* store path information
	* @var	array
	*/
	var	$path = array();
	
   /**
	* array that stores configuration from the current file
	* @var	array
	*/
	var	$conf = array();

   /**
	* array that stores all xml parsers
	* @var	array
	*/
	var	$parsers = array();

   /**
	* stack of the namespaces
	* @var	array
	*/
	var	$nsStack = array();

   /**
	* stack for tags that have been found
	* @var	array
	*/
	var	$tagStack = array();

   /**
	* stack of values
	* @var	array
	*/
	var	$valStack = array();

   /**
	* current depth of the stored values, i.e. array depth
	* @var	int
	*/
	var	$valDepth = 1;

   /**
	* current CDATA found
	* @var	string
	*/
	var	$data = array();
	
   /**
	* all open files
	* @var	array
	*/
	var	$xmlFiles = array();

   /**
	* treatment of whitespace
	* @var	string
	*/
	var	$whitespace = array('default');

   /**
	* current namespace for define
	* @var	string
	*/	
	var	$currentNamespace = '_none';

   /**
	* current tag for define
	* @var	string
	*/	
	var	$currentTag	= false;

   /**
 	* stack for define tags
	* @var	array
	*/
	var	$defineStack = array();

   /**
	* files that have been included
	* @var	array	$includedFiles
	*/
	var	$includedFiles = array();

   /**
	* list of all files that were needed
	* @var	array	$externalFiles
	*/
	var	$externalFiles = array();

   /**
	* reserved tags
	* @var	array
	*/
	var	$reservedTags = array('configuration', 'configValue', 'define', 'getConfigValue', 'path');

   /**
	* load configuration from a file
	*
	* @access	public
	* @param	string	$configFile		full path of the config file
	* @param	array	$options		various options, depending on the reader
	* @return	array	$config			complete configuration
	*/
	function loadConfigFile( $configFile, $options = array() )
	{
		$this->path = array();
		$this->conf	= array();

		$result = $this->parseXMLFile($configFile);

		if (patErrorManager::isError($result)) {
			return $result;
		}
		
		return	array(
						'config'        => $this->conf,
						'externalFiles' => $this->externalFiles,
						'cacheAble'     => true
					);
	}
	
   /**
	* set defined tags
	*
	* @access	public
	* @param	array
	*/
	function setDefinedTags( $tags, $ns = null )
	{
		if ($ns == null) {
			$this->definedTags = $tags;
		} else {
			$this->definedTags[$ns] = $tags;
		}
		return true;
	}

   /**
	* handle start element
	* if the start element contains a namespace calls the eppropriate handler
	* 
	* @param	resource	resource id of the current parser
	* @param	string		name of the element
	* @param	array		array containg all attributes of the element
	*/
	function startElement($parser, $name, $attributes)
	{
		//	separate namespace and local name
		$tag = explode( ':', $name, 2 );

		if (count($tag) == 2) {
			list($ns, $name) = $tag;
		} else {
			$ns = false;
		}
		
		array_push( $this->tagStack, $name );
		array_push( $this->nsStack, $ns );
		
		$tagDepth				=	count( $this->tagStack );
		$this->data[$tagDepth]	=	null;
		
		// inherit whitespace treatment
		if (!isset($attributes['xml:space'])) {
			$attributes['xml:space'] = $this->whitespace[($tagDepth - 1)];
		}

		// store whitespace treatment
		array_push( $this->whitespace, $attributes['xml:space'] );

		$tagHandled = false;
		
		// no namespace, handle the builtin tags
		if ($ns === false) {
		    $tagHandled = true;
			switch (strtolower($name)) {
				// configuration, root tag => just ignore it
				case 'configuration':
					break;

				// define
				case 'define':
					//	type = string is default
					if (!isset($attributes['type'])) {
						$attributes['type'] = 'string';
					}

					//	use 'ns' or 'namespace'
					if (isset($attributes['ns'])) {
						$attributes['namespace'] = $attributes['ns'];
						unset($attributes['ns']);
					} 
					
					if (isset( $attributes['namespace'])) {
                        // define a namespace
                        $this->_defineNamespace($attributes['namespace']);
			        } elseif (isset($attributes['tag'])) {
			            // define a tag
						$this->_defineTag($attributes);
			        } elseif (isset($attributes['attribute'])) {
			            // define an attribute
						$this->_defineAttribute($attributes);
			        } elseif (isset($attributes['child'])) {
			            // define a child
						$this->_defineChild($attributes);
			        }				
					break;

				//	get a configValue that has been defined
				case 'getconfigvalue':
					$this->appendData( $this->getConfigValue( $attributes['path'] ) );
					break;

                // include a new file
				case 'xinc':
					$this->_xInclude($attributes);
					break;

				// path
				case 'path':
					$this->addToPath($attributes['name']);
					break;

				// create a new value
				case 'configvalue':
				    if (!isset($attributes['name'])) {
				        $attributes['name'] = false;
				    }
					//	store name and type of value
					$val = @array( 'type' => $attributes['type'],
                                   'name' => $attributes['name']
								);
                    if ($val['type'] == 'object') {
                    	if (isset($attributes['instanceof'])) {
                    		$val['instanceof'] = $attributes['instanceof'];
                    	} else {
                    		$val['instanceof'] = 'stdClass';
                    	}
                    }

					$this->valDepth	= array_push( $this->valStack, $val );
					break;
				default:
				    $tagHandled = false;
				    break;
            }
		}
		
		if ($tagHandled) {
			return true;
		}
		
		if ($ns === false) {
			$ns = '_none';
		}
		
		// check, whether the namespace has been defined.						
		if (!isset( $this->definedTags[$ns])) {
            $this->addToPath($name);
            return true;
		}

		//	check whether the tag has been defined 					
		if (!isset($this->definedTags[$ns][$name])) {
			$this->addToPath($name);
			return true;
		}

		$def     = $this->definedTags[$ns][$name];
		$type    = $def['type'];
		$tagName = $def['name'];
		
		if ($tagName == '_attribute') {
		    if (isset($attributes[$def['nameAttribute']])) {
                $tagName = $attributes[$def['nameAttribute']];	
		    } else {
		        $tagName = false;
		    }
		}
			
		//	store name and type of value
		$val = array(
		              'type' => $type,
                      'name' => $tagName
                    );

        // set the classname if creating an object
		if ($type === 'object') {
			if (isset($def['instanceof'])) {
				$val['instanceof'] = $def['instanceof'];
			} else {
                $val['instanceof'] = 'stdClass';
			}
		}

		// value is stored in an attribute
		if (isset($def['value'])) {
			if (isset($attributes[$def['value']])) {
				$val['value'] = $attributes[$def['value']];
			}
		// attributes are used as value
		} elseif (isset($def['attributes']) && is_array($def['attributes'])) {
		    
			//	value must be an array
			$value	=	array();
			//	check, which attributes exist
			foreach ($def['attributes'] as $name => $attDef) {
			    
				if (isset($attributes[$name])) {
					$value[$name] = $this->configObj->convertValue( $attributes[$name], $attDef['type'] );
				} elseif (isset($attDef['default'])) {
				    // use the default
					$value[$name] = $this->configObj->convertValue( $attDef['default'], $attDef['type'] );
				}
			}
			$val['value'] = $value;
		}

		// add the children default values
		if (isset($def['children'])) {
		    if (!isset($val['value']) || !is_array($val['value'])) {
		    	$val['value'] = array();
		    }
			foreach ($def['children'] as $name => $childDef) {
			    
                if (!isset($childDef['default'])) {
                    $val['value'][$name] = $this->configObj->convertValue(null, $childDef['type'] );
                } else {
                    $val['value'][$name] = $this->configObj->convertValue($childDef['default'], $childDef['type'] );
                }
			}
		}
		
		
		$this->valDepth	= array_push($this->valStack, $val);

		if (isset($def['content'])) {
			$val = array(
                        'type'    => 'auto',
                        'name'    => $def['content'],
                        '_isAuto' => true
						);

			$this->valDepth	= array_push( $this->valStack, $val );
		}
	}
		
   /**
	* handle end element
	*
	* if the end element contains a namespace calls the eppropriate handler
	* 
	* @param	resource	resource id of the current parser
	* @param	string		name of the element
	*/
	function endElement( $parser, $name )
	{
		//	remove whitespace treatment from stack
		$whitespace = array_pop($this->whitespace);

		//	get the data of the current tag
		$tagDepth = count($this->tagStack);

		$this->currentData = $this->data[$tagDepth];
		
		switch ($whitespace) {
			case 'preserve':
				break;
			case 'strip':
				$this->currentData = trim( preg_replace( '/\s\s*/', ' ', $this->currentData ) );
				break;
			case 'default':
			default:
				$this->currentData = trim($this->currentData);
				break;
		}
		
		//	delete the data before returning it
		$this->data[$tagDepth] = '';

		//	remove namespace from stack
		$ns   = array_pop( $this->nsStack );
		//	remove tag from stack
		$name = array_pop( $this->tagStack );

		$tagHandled = false;
		// default namespace
		if ($ns === false) {
		    $tagHandled = true;
		    
			switch( strtolower( $name ) ) {
				//	configuration
				case 'configuration':
				case 'getconfigvalue':
				case 'xinc':
					break;

				case 'define':
					$mode = array_pop ($this->defineStack);
					switch ($mode) {
						case 'ns':
							$this->currentNamespace = '_none';
							break;
						case 'tag':
							$this->currentTag = false;
							break;	
					}
					break;

				//	path
				case 'path':
					$this->removeLastFromPath();
					break;

				//	config value
				case 'configvalue':
					//	get last name and type
					$val = array_pop( $this->valStack );
									
					//	decrement depth, as one tag was removed from stack
					$this->valDepth--;

					//	if no value was found (e.g. other tags inside)
					//	use CDATA that was found between the tags
					if (!isset( $val['value'])) {
						$val['value'] = $this->getData();
					}
						
					$this->setTypeValue($val['value'], $val['type'], $val['name'], $val);
					break;
				default:
				    $tagHandled = false;
				    break;
			}
		}
		
		if ($tagHandled === true) {
			return true;
		}

		if ($ns === false) {
			$ns = '_none';
		}
		
        // check, whether the namespace and the tag have been defined.						
        if (!isset($this->definedTags[$ns]) || !isset($this->definedTags[$ns][$name])) {
        	// if data was found => store it
        	if ($data = $this->getData()) {
        		$this->setTypeValue($data);
        	}
        	//	shorten path
        	$this->removeLastFromPath();
        	return true;
        }
        
        //	get last name and type
        $val = array_pop($this->valStack);
        
        //	decrement depth, as one tag was removed from stack
        $this->valDepth--;
        
        //	if no value was found (e.g. other tags inside)
        //	use CDATA that was found between the tags
        if (!isset($val['value'])) {
        	$val['value'] = $this->getData();
        }
        
        // transform the value using PHP's constants
        // This feature allows you to include PHP constants in your configuration files
        if (isset( $this->definedTags[$ns][$name]['useconstants'] ) && $this->definedTags[$ns][$name]['useconstants'] === true) {
        	if (defined($val['value'])) {
        		$val['value'] = constant( $val['value'] );
        	}
        }

        $options = array();
        // value should be object of a specific class
        if (isset($val['instanceof'])) {
        	$options['instanceof'] = $val['instanceof'];
        }
        
        $this->setTypeValue( $val['value'], $val['type'], $val['name'], $options );
        
        if (isset($val['_isAuto'])) {
        	//	get last name and type
        	$val = array_pop( $this->valStack );
        
        	//	decrement depth, as one tag was removed from stack
        	$this->valDepth--;
        
        	//	if no value was found (e.g. other tags inside)
        	//	use CDATA that was found between the tags
        	if (!isset( $val['value']))
        		$val['value'] = $this->getData();
        			
        	$options = array();
        	if (isset($val['instanceof'])) {
        		$options['instanceof'] = $val['instanceof'];
        	}
        
        	$this->setTypeValue( $val['value'], $val['type'], $val['name'], $options );
        }
        return true;
	}
	
   /**
	* handle character data
	* if the character data was found between tags using namespaces, the appropriate namesapce handler will be called
	* 
	* @param	int		$parser		resource id of the current parser
	* @param	string	$data		character data, that was found		
	*/
	function characterData( $parser, $data )
	{
		$tagDepth = count($this->tagStack);

		if (!isset($this->data[$tagDepth])) {
			$this->data[$tagDepth] = '';
		}
			
		$this->data[$tagDepth] .= $data;
	}

   /**
	* add element to path
	*
	* @access	private
	* @param	string	$key	element that should be appended to path
	* @return   integer
	*/
	function addToPath( $key )
	{
		return array_push($this->path, $key);
	}
	
   /**
	* remove last element from path
	*
	* @access	private
	* @return   string
	*/
	function removeLastFromPath()
	{
		return array_pop($this->path);
	}

   /**
	* set value for the current path
	*
	* @access	private
	* @param	mixed	$value	value that should be set
	*/
	function setValue( $value )
	{
		$string	= implode( '.', $this->path );
		$this->conf[$string] = $value;
	}

   /**
	* returns the current data between the open tags
	* data can be anything, from strings, to arrays or objects
	*
	* @access	private
	* @return	mixed	$value	data between text
	*/
	function getData()
	{
		return $this->currentData;
	}

   /**
	* append Data to the current data
	*
	* @param	mixed	$data	data to be appended
	*/
	function appendData( $data ) 
	{
		$tagDepth = count($this->tagStack) - 1;
		// init data
		if (!isset($this->data[$tagDepth])) {
			$this->data[$tagDepth] = '';
		}
		
		if (is_string($this->data[$tagDepth])) {
			//	append string
			if (is_string($data)) {
				$this->data[$tagDepth] .= $data;
			} else {
				$this->data[$tagDepth]  = array( $this->data[$tagDepth], $data );
			}
		} elseif (is_array($this->data[$tagDepth])) {
		    // add an array to the array
			if (is_array($data)) {
				$this->data[$tagDepth]	= array_merge( $this->data[$tagDepth], $data );
			} else {
				array_push($this->data[$tagDepth], $data);
			}
		} else {
			$this->data[$tagDepth] = $data;
		}
	}
	
   /**
	* convert a value to a certain type ans set it for the current path
	*
	* @access	private
	* @param	mixed		value that should be set
	* @param	string		type of the value (string, bool, integer, double)
	* @param	array		optional options for the conversion
	*/
	function setTypeValue($value, $type = 'leave', $name = '', $options = array())
	{
		// convert value
		$value = $this->configObj->convertValue($value, $type, $options);

		// check, if there are parent values
		// insert current value into parent array
		if (count($this->valStack) > 0) {
			if ($name) {
				$this->valStack[($this->valDepth-1)]['value'][$name] = $value;
			} else {
				$this->valStack[($this->valDepth-1)]['value'][]      = $value;
			}
		} else {
			//	No valuestack
			if( isset( $this->nsStack[( count( $this->nsStack )-1 )] ) && $this->nsStack[( count( $this->nsStack )-1 )] ) {
				$this->appendData( $value );
			} else {
				if ($name) {
					$this->addToPath( $name );
				}
	
				$this->setValue( $value );
	
				if ($name) {
					$this->removeLastFromPath();
				}
			}
		}
	}

   /**
	* define a new namespace
	*
	* @access	private
	* @param	string	$namespace
	*/
	function _defineNamespace( $namespace )
	{
		array_push( $this->defineStack, 'ns' );
		if (isset( $this->definedTags[$namespace] )) {
			$line = $this->_getCurrentLine();
			$file = $this->_getCurrentFile();

			$this->currentNamespace = false;

			return patErrorManager::raiseWarning(
										PATCONFIGURATION_ERROR_CONFIG_INVALID,
										'Cannot redefine namespace '.$namespace.' on line '.$line.' in '.$file
										 );
		}
		$this->definedTags[$namespace]	=	array();
		$this->currentNamespace			=	$namespace;
		return	true;
	}
	
   /**
	* define a new tag
	*
	* @access	private
	* @param	array	$attributes
	*/
	function _defineTag( $attributes )
	{
		array_push($this->defineStack, 'tag');
		if ($this->currentNamespace === false) {
			return	false;
		}

		$ns = $this->currentNamespace;
		
		if ($ns === '_none') {
            if (in_array($attributes['tag'], $this->reservedTags)) {
            	return patErrorManager::raiseError(PATCONFIGURATION_READER_XML_ERROR_RESERVED_TAG, 'Cannot redefine reserved tag.', $attributes['tag'] );
            }
		}

		$tag = $attributes['tag'];
		if (!isset( $attributes['name'] )) {
			$tagName = $attributes['tag'];
			$nameAttribute = null;
		} else {
			switch( $attributes['name'] ) {
				case '_none':
					$tagName       = null;
					$nameAttribute = null;
					break;
				case '_attribute':
					$tagName       = '_attribute';
					$nameAttribute = $attributes['attribute'];
					break;
				default:
					$tagName       = $attributes['name'];
					$nameAttribute = null;
					break;
			}
		}

		$this->definedTags[$ns][$tag] = array(
                                               'type' => $attributes['type'],
                                               'name' => $tagName
											);
		if (isset($attributes['value'])) {
			$this->definedTags[$ns][$tag]['value'] = $attributes['value'];
		}

		if (isset($attributes['content'])) {
			$this->definedTags[$ns][$tag]['content'] = $attributes['content'];
		}

		if (isset($attributes['instanceof'])) {
			$this->definedTags[$ns][$tag]['instanceof'] = $attributes['instanceof'];
		}

		if (isset($attributes['useconstants']) && $attributes['useconstants'] == 'true') {
			$this->definedTags[$ns][$tag]['useconstants'] = true;
		}

		if ($nameAttribute != null) {
			$this->definedTags[$ns][$tag]['nameAttribute'] = $nameAttribute;
		}

		$this->currentTag = $tag;
		return	true;
	}

   /**
	* define a new attribute
	*
	* @access	private
	* @param	array	$attributes
	*/
	function _defineAttribute( $attributes )
	{
		array_push( $this->defineStack, 'attribute' );
		if( $this->currentNamespace === false ) {
			return	false;
		}
		if( $this->currentTag === false ) {
			$line	=	$this->_getCurrentLine();
			$file	=	$this->_getCurrentFile();

			$this->currentNamespace		=	false;
			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_CONFIG_INVALID,
										'Cannot define attribute outside a tag on line '.$line.' in '.$file
										 );
		}

		$ns  = $this->currentNamespace;
		$tag = $this->currentTag;
		
		if (!isset($this->definedTags[$ns][$tag]['attributes'])) {
			$this->definedTags[$ns][$tag]['attributes']	= array();
		}
						
		$this->definedTags[$ns][$tag]['attributes'][$attributes['attribute']] = array(
                                                                                        'type'	=>	$attributes['type']
                                                                                    );

		if (isset( $attributes['default'])) {
			$this->definedTags[$ns][$tag]['attributes'][$attributes['attribute']]['default'] = $attributes['default'];
		}

		return	true;
	}

   /**
	* define a new child
	*
	* @access	private
	* @param	array	$attributes
	*/
	function _defineChild($attributes)
	{
		array_push($this->defineStack, 'child');
		if ($this->currentNamespace === false ) {
			return	false;
		}
		if( $this->currentTag === false ) {
			$line	=	$this->_getCurrentLine();
			$file	=	$this->_getCurrentFile();

			$this->currentNamespace = false;
			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_CONFIG_INVALID,
										'Cannot define child outside a tag on line '.$line.' in '.$file
										 );
		}

		$ns  = $this->currentNamespace;
		$tag = $this->currentTag;
		
		if (!isset($this->definedTags[$ns][$tag]['children'])) {
			$this->definedTags[$ns][$tag]['children']	= array();
		}
						
		$this->definedTags[$ns][$tag]['children'][$attributes['child']] = array(
                                                                                 'type' => $attributes['type']
                                                                                );

		if (isset($attributes['default'])) {
			$this->definedTags[$ns][$tag]['children'][$attributes['child']]['default'] = $attributes['default'];
		}

		return	true;
	}

   /**
	* parse an external entity
	*
	* @param	int		$parser				resource id of the current parser
	* @param	string	$openEntityNames	space-separated list of the names of the entities that are open for the parse of this entity (including the name of the referenced entity)
	* @param	string	$base				currently empty string
	* @param	string	$systemId			system identifier as specified in the entity declaration
	* @param	string	$publicId			publicId, is the public identifier as specified in the entity declaration, or an empty string if none was specified; the whitespace in the public identifier will have been normalized as required by the XML spec
	*/
	function externalEntity( $parser, $openEntityNames, $base, $systemId, $publicId )
	{
		if ($systemId) {
			$file = $this->configObj->getFullPath( $systemId, $this->_getCurrentFile() );
			array_push( $this->externalFiles, $file );
			$this->parseXMLFile( $file );
		}
		return	true;
	}

   /**
	* get all files in a directory
	*
	* @access	private
	* @param	string	$dir
	* @param	string	$ext	file extension
	*/
	function getFilesInDir( $dir, $ext )
	{
		$files = array();
		
		if (!$dh = dir( $dir )) {
			return	$files;
		}
			
		while ($entry = $dh->read()) {
			if ($entry == '.' || $entry == '..') {
				continue;
			}
			
			if (is_dir( $dir . '/' . $entry )) {
				continue;
			}
			
			if (strtolower( strrchr( $entry, '.' ) ) != '.'.$ext) {
				continue;
			}
			
			array_push( $files, $dir. '/' . $entry );
		}

		return	$files;
	}
	
   /**
	* returns a configuration value
	*
	* if no path is given, all config values will be returnded in an array
	*
	* @access	public
	* @param	string	$path	path, where the value is stored
	* @return	mixed	$value	value
	*/
	function getConfigValue( $path = '' )
	{
		if ($path == '') {
			return	$this->conf;
		}

		if (strstr( $path, '*' )) {
			$path   = str_replace( '.', '\.', $path ).'$';
			$path   = '^'.str_replace( '*', '.*', $path ).'$';
			$values = array();
			foreach ($this->conf as $key => $value) {
				if (eregi( $path, $key )) {
					$values[$key]	=	$value;
				}
			}
			return	$values;
		}

		//	check wether a value of an array was requested
		if ($index = strrchr( $path, '[' )) {
			$path  = substr( $path, 0, strrpos( $path, '[' ) );
			$index = substr( $index, 1, ( strlen( $index ) - 2 ) );
			$tmp   = $this->getConfigValue( $path );

			return	$tmp[$index];
		}
		
		if (isset($this->conf[$path])) {
			return	$this->conf[$path];
		}
		
		return	false;
	}

   /**
	* parse an external xml file
	*
	* @param	string		filename, without dirname
	* @return	boolean		true on success, patError on failure
	*/
	function parseXMLFile( $file )
	{
		//	add it to included files
		array_push( $this->includedFiles, $file );

		$parserCount                 = count( $this->parsers );
		$this->parsers[$parserCount] = $this->createParser();
		
		if (!($fp = @fopen( $file, 'r' ))) {
			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_FILE_NOT_FOUND,
										'Could not open XML file '.$file
										 );
		}

		array_push( $this->xmlFiles, $file );

		flock( $fp, LOCK_SH );

		while ($data = fread( $fp, 4096 )) {
		    
			if ( !xml_parse( $this->parsers[$parserCount], $data, feof( $fp ) ) ) {
				$message = sprintf(	'XML error: %s at line %d in file %s',
                                    xml_error_string( xml_get_error_code( $this->parsers[$parserCount] ) ),
                                    xml_get_current_line_number( $this->parsers[$parserCount] ),
                                    $file );

				array_pop( $this->xmlFiles );
		
				flock($fp, LOCK_UN);
				fclose($fp);
				xml_parser_free( $this->parsers[$parserCount] );

				return patErrorManager::raiseError(
											PATCONFIGURATION_ERROR_CONFIG_INVALID,
											$message
											 );
    		}
		}

		array_pop($this->xmlFiles);
		
		flock($fp, LOCK_UN);
		fclose($fp);
		xml_parser_free( $this->parsers[$parserCount] );

		return true;
	}

   /**
	* get the current xml parser object
	*
	* @access	private
	* @return	resource	$parser
	*/
	function &_getCurrentParser()
	{
		$parserCount = count( $this->parsers ) - 1;
		return $this->parsers[$parserCount];
	}
	
   /**
	* get the current line number
	*
	* @access	private
	* @return	int	$line
	*/
	function _getCurrentLine()
	{
		$parser	= &$this->_getCurrentParser();
		$line	= xml_get_current_line_number( $parser );
		return $line;
	}

   /**
	* get the current file
	*
	* @access	private
	* @return	string $file
	*/
	function _getCurrentFile()
	{
		$file = $this->xmlFiles[( count( $this->xmlFiles )-1 )];
		return $file;
	}

   /**
	* create a parser
	*
	* @return	object	$parser
	*/
	function createParser()
	{
		//	init XML Parser
		$parser	= xml_parser_create( $this->configObj->encoding );
		xml_set_object( $parser, $this );

		if( version_compare( phpversion(), '5.0.0b' ) == -1 ) {
			xml_set_element_handler( $parser, 'startElement', 'endElement' );
			xml_set_character_data_handler( $parser, 'characterData' );
			xml_set_external_entity_ref_handler( $parser, 'externalEntity' );
		} else {
			xml_set_element_handler( $parser, array( $this, 'startElement' ), array( $this, 'endElement' ) );
			xml_set_character_data_handler( $parser, array( $this, 'characterData' ) );
			xml_set_external_entity_ref_handler( $parser, array( $this, 'externalEntity' ) );
		}

		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, false );

		return	$parser;
	}

   /**
	* include an xml file or directory
	*
	* @access	private
	* @param	array	options (=attributes of the tag)
	*/
	function _xInclude( $options )
	{
		if (!isset( $options['once'] )) {
			$options['once']	=	'no';
		}

		if (!isset( $options['relativeTo'] )) {
			$options['relativeTo'] = 'file';
		}
		
		switch( $options['relativeTo'] ) {
			case 'defines':
				$relativeTo = $this->configObj->getOption( 'definesDir' ).'/.';
				break;
			default:
				$relativeTo = $this->_getCurrentFile();
				break;
		}

		//	include a single file
		if( isset( $options['href'] ) ) {
			$file = $this->configObj->getFullPath( $options['href'], $relativeTo );

			if ($file === false) {
				return	false;
			}
			if ($options['once'] == 'yes' && in_array( $file, $this->includedFiles )) {
				return	true;
			}

			array_push( $this->externalFiles, $file );

			$this->parseXMLFile( $file );
		} elseif( isset( $options['dir'] ) ) {
			
			//	include a directory			
			if (!isset( $options['extension'] )) {
				$options['extension'] = 'xml';
			}
				
			$dir = $this->configObj->getFullPath( $options['dir'], $relativeTo );
			if ($dir === false) {
				return 	false;
			}
			$files = $this->getFilesInDir( $dir, $options['extension'] );

			foreach( $files as $file ) {
				array_push( $this->externalFiles, $file );
				$this->parseXMLFile( $file );
			}
		}
		return	true;
	}
}
?>