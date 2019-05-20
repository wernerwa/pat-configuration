<?php
/**
 * The pat examples framework class - manages the navigation and
 * functionality of the examples collection of our tools.
 *
 * $Id: patExampleGen.php 29 2005-03-04 21:25:29Z schst $
 *
 * @package     patExampleGen
 * @author      Sebastian Mordziol <argh@php-tools.net>
 */

 // global flag needed for the error handling output
 $GLOBALS['errorStylesRendered'] = false;
 
/**
 * The pat examples framework class - manages the navigation and
 * functionality of the examples collection of our tools.
 *
 * $Id: patExampleGen.php 29 2005-03-04 21:25:29Z schst $
 *
 * @package     patExampleGen
 * @version     0.9
 * @author      Sebastian Mordziol <argh@php-tools.net>
 * @license     LGPL
 * @link        http://www.php-tools.de
 */
class patExampleGen
{
   /**
    * Stores all request variables
    *
    * @access   private
    * @var      array
    * @see      setSections()
    */
    var $vars = array();
    
   /**
    * Stores the sections collection to generate
    * the examples navigation from
    *
    * @access   private
    * @var      array
    */
    var $sections = array();
    
   /**
    * The XML_Beautifier class - used if installed to display
    * XML output in a nice readable format with indentation.
    *
    * @access   private
    * @var      object
    */
    var $beautifier = false;
    
   /**
    * Stores the tabs configuration used to create the tabnavigation
    * for each example.
    *
    * @access   private
    * @var      array
    * @see      setTabs()
    */
    var $tabs = array();
    
   /**
    * Stores the name of the application
    *
    * @access   private
    * @var      string
    * @see      setAppName()
    */
    var $appName = 'Unknown';
    
   /**
    * Stores the description of the application
    *
    * @access   private
    * @var      string
    * @see      setAppDescription()
    */
    var $appDescription = '';

   /**
    * Stores the ID of the application's forum
    *
    * @access   private
    * @var      int
    * @see      setAppForumId()
    */
    var $appForumId = 0;
    
   /**
    * Main process method that dispatches needed tasks according
    * to the given action.
    *
    * @access   public
    */
    function process()
    {
        $this->vars = $this->getRequestVars();
    
        $action = 'frameset';
        if (isset($this->vars['action'])) {
            $action = strtolower($this->vars['action']);
        }
        
        switch ($action) {
            case 'frameset':
                $this->displayFrameset();
                break;
                
            case 'navigation':
                $this->displayNavigation();
                break;
                
            case 'overview':
                $this->displayOverview();
                break;
                
            case 'example':
                $this->displayExample();
                break;
        }
    }
    
   /**
    * Retrieves the request variables from POST and GET
    *
    * @access   public
    * @return   array   $requestVars    The request vars
    */
    function getRequestVars()
    {
        return array_merge($_POST, $_GET);
    }
    
   /**
    * Sets the default tab configuration that will be used for
    * each example page. Additional tabs can be defined for each
    * example within the section definition.
    *
    * @access   public
    * @param    array   $tabs   The tab configuration
    * @see      $tabs
    */
    function setTabs($tabs)
    {
        $this->tabs = $tabs;
    }
    
   /**
    * Sets the name of the application
    *
    * @access   public
    * @param    string  $appName    The name
    * @see      $appName
    */
    function setAppName($appName)
    {
        $this->appName = $appName;
    }
    
   /**
    * Sets the description of the application
    *
    * @access   public
    * @param    string  $desc   The description text
    * @see      $appDescription
    */
    function setAppDescription($desc)
    {
        $this->appDescription = $desc;
    }
    
   /**
    * Sets the ID of the application's forum. Needed to display
    * the correct link to the forum.
    *
    * @access   public
    * @param    int     $id     The forum ID
    * @see      $appForumId
    */
    function setAppForumId($id)
    {
        $this->appForumId = $id;
    }
    
   /**
    * Retrieves the default example view tab, the one
    * that is active when the page is loaded.
    *
    * @access   public
    * @return   string  $tabId  The ID of the tab
    */
    function getDefaultTab($tabs)
    {
        foreach ($tabs as $tabId => $tabDef) {
            if ($tabDef['default']) {
                return $tabId;
            }
        }
        
        reset($tabs);
        
        return key($tabs);
    }
    
   /**
    * Custom error handler that is set automatically to display
    * any patError error objets within the examples collection.
    *
    * Displays:
    * - Error level
    * - Error Message
    * - Error info
    * - Error file
    * - Error line
    * - plus the call stack that lead to the error
    *
    * @author   Sebastian Mordziol <argh@php-tools.net>
    * @access   public
    * @static
    * @param    object      error object
    * @return   object      error object
    */
    function &displayError(&$error)
    {
        $prefix = 'arghDebug';
        
        // display the needed styles and scripts, but only once.
        if (!$GLOBALS['errorStylesRendered']) {
            echo '<style>';
            echo '.'.$prefix.'Frame{';
            echo '	background-color:#FEFCF3;';
            echo '	padding:8px;';
            echo '	border:solid 1px #000000;';
            echo '	margin-top:13px;';
            echo '	margin-bottom:25px;';
            echo '	width:100%;';
            echo '}';
            echo '.'.$prefix.'Table{';
            echo '	border-collapse:collapse;';
            echo '	margin-top:13px;';
            echo '	width:100%;';
            echo '}';
            echo '.'.$prefix.'TD{';
            echo '	padding:3px;';
            echo '	padding-left:5px;';
            echo '	padding-right:5px;';
            echo '	border:solid 1px #bbbbbb;';
            echo '}';
            echo '.'.$prefix.'Type{';
            echo '	background-color:#cc0000;';
            echo '	color:#ffffff;';
            echo '	font-weight:bold;';
            echo '	padding:3px;';
            echo '}';
            echo '</style>';
            echo '<script language="javascript" type="text/javascript">';
            echo 'function '.$prefix.'displayBacktrace( eid )';
            echo '{';
            echo '	document.getElementById( "'.$prefix.'" + eid + "backtrace" ).style.display = "block";';
            echo '}';
            echo '</script>';
            
            $GLOBALS['errorStylesRendered'] = true;
        }

        $errorID = md5($error->getFile().$error->getLine());
        
        echo    '<div class="'.$prefix.'Frame">';
        printf(
            '<div style="margin-bottom:8px;"><span class="'.$prefix.'Type">%s:</span> %s in %s on line %s</div>Details: %s (<a href="javascript:'.$prefix.'displayBacktrace( \''.$errorID.'\' );">show backtrace</a>)',
            patErrorManager::translateErrorLevel($error->getLevel()),
            $error->getMessage(),
            $error->getFile(),
            $error->getLine(),
            $error->getInfo()
        );

        $backtrace  =   $error->getBacktrace();
        if (is_array($backtrace)) {
            $j  =   1;
            echo    '<table id="'.$prefix.$errorID.'backtrace" border="0" cellpadding="0" cellspacing="0" class="'.$prefix.'Table" style="display:none;">';
            echo    '	<tr>';
            echo    '		<td colspan="3" align="left" class="'.$prefix.'TD"><strong>Call stack</strong></td>';
            echo    '	</tr>';
            echo    '	<tr>';
            echo    '		<td class="'.$prefix.'TD"><strong>#</strong></td>';
            echo    '		<td class="'.$prefix.'TD"><strong>Function</strong></td>';
            echo    '		<td class="'.$prefix.'TD"><strong>Location</strong></td>';
            echo    '	</tr>';
            for ($i = count($backtrace)-1; $i >= 0; $i--) {
                echo    '	<tr>';
                echo    '		<td class="'.$prefix.'TD">'.$j.'</td>';
                if (isset($backtrace[$i]['class'])) {
                    echo    '	<td class="'.$prefix.'TD">'.$backtrace[$i]['class'].$backtrace[$i]['type'].$backtrace[$i]['function'].'()</td>';
                } else {
                    echo    '	<td class="'.$prefix.'TD">'.$backtrace[$i]['function'].'()</td>';
                }
                if (isset($backtrace[$i]['file'])) {
                    echo    '		<td class="'.$prefix.'TD">'.$backtrace[$i]['file'].':'.$backtrace[$i]['line'].'</td>';
                } else {
                    echo    '		<td class="'.$prefix.'TD">&nbsp;</td>';
                }
                echo    '	</tr>';
                $j++;
            }
            echo    '</table>';
        }
        echo    '</div>';
        
        $level  =   $error->getLevel();
        
        if ($level != E_ERROR) {
            return  $error;
        }
            
        exit();
    }

   /**
    * Displays an example page along with all defined tabs
    *
    * @access   public
    */
    function displayExample()
    {
        if (!isset($this->vars['example'])) {
            die('No example selected.');
        }
        
        $exampleId      =   $this->vars['example'];
        $section        =   $this->getExampleSection($exampleId);
        $example        =   $this->getExample($exampleId);
        $exampleFile    =   $exampleId.'.php';
        $tabs           =   $this->getTabs($exampleId);
        $defaultTab     =   $this->getDefaultTab($tabs);
        
        $this->displayHead('Example', 'displayTab( \''.$defaultTab.'\' );');
        
        echo '<script type="text/javascript" language="JavaScript1.2">';
        echo 'var tabs = new Array( \''.implode("', '", array_keys($tabs)).'\' );';
        echo 'var actTab = false;';
        echo 'function hiTab( tabID )';
        echo '{';
        echo '	document.getElementById( tabID + \'Tab\' ).className = \'tabA\';';
        echo '}';
        echo 'function loTab( tabID )';
        echo '{';
        echo '	if( tabID == actTab )';
        echo '		return true;';
        echo '	document.getElementById( tabID + \'Tab\' ).className = \'tabN\';';
        echo '}';
        echo 'function displayTab( tabID )';
        echo '{';
        echo '	for( var i = 0; i < tabs.length; i++ )';
        echo '	{';
        echo '		if( tabID == tabs[i] )';
        echo '			document.getElementById( tabs[i] ).style.display = \'block\';';
        echo '		else';
        echo '			document.getElementById( tabs[i] ).style.display = \'none\';';
        echo '	}';
        echo '	var oldAct = actTab;';
        echo '	actTab = tabID;';
        echo '	hiTab( actTab );';
        echo '	if( oldAct !== false )';
        echo '		loTab( oldAct );';
        echo '}';
        echo '</script>';
        echo '<div style="padding:20px;padding-bottom:5px;">';
        echo '	<div class="head"><b>'.$this->appName.' '.$section.' :: '.$example['title'].'</b></div>';
        echo '	<div class="abstract">'.htmlentities($example['descr']).'</div>';
        echo '</div>';

        // the tabs
        echo '<table border="0" cellpadding="0" cellspacing="0" class="tabs" width="100%">';
        echo '	<tr>';
        echo '		<td style="padding-left:15px;">&nbsp;</td>';
        foreach ($tabs as $tabId => $tabDef) {
            echo '<td class="tabN" id="'.$tabId.'Tab" onclick="displayTab( \''.$tabId.'\' );" onmouseover="hiTab( \''.$tabId.'\' );" onmouseout="loTab( \''.$tabId.'\' );">'.$tabDef['title'].'</td>';
            echo '<td>&nbsp;</td>';
        }
        echo '		<td width="100%">&nbsp;</td>';
        echo '	</tr>';
        echo '</table>';
        
        // the tab contents
        foreach ($tabs as $tabId => $tabDef) {
            // replace variables in the file string with
            // the corresponding local variables
            if (isset($tabDef['file'])) {
                // possible vars
                $needles = array(
                    '$exampleFile',
                    '$exampleId',
                );
                
                // store values
                $replace = array();
                foreach ($needles as $n) {
                    array_push($replace, ${substr($n, 1)});
                }
                
                // replace
                $tabDef['file'] = str_replace($needles, $replace, $tabDef['file']);
            }
        
            // what kind of tab is this?
            switch (strtolower($tabDef['type'])) {
                case 'phpsource':
                    $this->displayPHPSource($tabId, $tabDef['file']);
                    break;
                    
                case 'output':
                    $this->displayOutput($tabId, $tabDef['file']);
                    break;
                    
                case 'xmlsource':
                    $this->displayXMLSource($tabId, $tabDef['file']);
                    break;
                
                case 'text':
                    $this->displayText($tabId, $tabDef['text']);
                    break;

                case 'guide':
                    $this->displayGuide($tabId, $tabDef['file'], $tabDef['guide']);
                    break;
            }
        }
        
        $this->displayFooter();
    }
    
   /**
    * Display an example's output in an example tab via an iframe
    *
    * @access   public
    * @param    string  $tabId  The ID of the tab
    * @param    string  $file   The file to show
    */
    function displayOutput($tabId, $file)
    {
        echo '<iframe class="exampleContent" style="display:none;" id="'.$tabId.'" src="'.$file.'"></iframe>';
    }
    
    function displayGuide($tabId, $file, $guide)
    {
        $file = explode("\n", $this->file_get_contents($file));

        echo '	<div class="exampleContent" id="'.$tabId.'">
				<style>
					.guideEntryTitle{
						font-weight:bold;
						font-size:12px;
						margin-bottom:6px;
					}
					.guideEntry{
						padding:8px;
						border:solid 1px #cfcfcf;
						background-color:#fafafa;
						margin-bottom:20px;
					}
				</style>';

        foreach ($guide as $row => $entry) {
            $slice = explode('-', $entry['lines']);
            $hilight = implode("\n", array_slice($file, ( $slice[0] -1 ), ( $slice[1] - $slice[0] ) + 1));

            ob_start();
            highlight_string('<?php'.$hilight.'?>');
            $content = ob_get_contents();
            ob_end_clean();
            
            $content = str_replace('&lt;?php', '', $content);
            $content = str_replace('?&gt;', '', $content);
            
            $numbers = '';
            for ($i=$slice[0]; $i <= $slice[1]; $i++) {
                $numbers .= $i.'.<br>';
            }
            
            echo '	<div class="guideEntryTitle">'.$entry['text'].'</div>
					<div class="guideEntry">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr valign="top">
								<td style="padding-right:5px;"><code>'.$numbers.'</code></td>
								<td>'.$content.'</td>
							</tr>
						</table>
					</div>';
        }

        echo '</div>';
    }

   /**
    * Display PHP source code in an example tab
    *
    * @access   public
    * @param    string  $tabId  The ID of the tab
    * @param    string  $file   The file to hilight
    */
    function displayPHPSource($tabId, $file)
    {
        echo '<div class="exampleContent" id="'.$tabId.'">';
        highlight_file($file);
        echo '</div>';
    }
    
   /**
    * Display a free text in a tab
    *
    * @access   public
    * @param    string  $tabId  The ID of the tab
    * @param    string  $text   The text to display
    */
    function displayText($tabId, $text)
    {
        echo '<div class="exampleContent" id="'.$tabId.'">';
        echo $text;
        echo '</div>';
    }

   /**
    * Display XML source code in an example tab
    *
    * @access   public
    * @param    string  $tabId  The ID of the tab
    * @param    string  $file   The file to hilight
    */
    function displayXMLSource($tabId, $file)
    {
        $url = str_replace('index.php', $file, 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
    
        $source = $this->url_get_contents($url);
        if (!$source) {
            die('Could not open XML source file for hilighting: "'.$url.'"');
        }
    
        echo '<div class="exampleContent" id="'.$tabId.'">';
        $this->displayXMLString($source);
        echo '</div>';
    }
    
   /**
    * Hilights an xml string and displays it
    *
    * @access   public
    * @param    string  $xml    The XML string
    * @see      highlightXML()
    */
    function displayXMLString($xml)
    {
        echo $this->highlightXML($xml);
    }
    
   /**
    * Highlights an xml string and returns the highlighted source
    *
    * @access   public
    * @param    string  $xml    The XML string
    * @return   string  $xml    The highlighted string
    */
    function highlightXML($xml)
    {
        @include_once('XML/Beautifier.php');
        
        if (class_exists('XML_Beautifier')) {
            if (!is_object($this->beautifier)) {
                $this->beautifier =& new XML_Beautifier();
            }
            
            $result =   $this->beautifier->formatString($xml);
            if (!PEAR::isError($result)) {
                $xml = $result;
            }
        }
        
        ob_start();
        highlight_string($xml);
        $xml = ob_get_contents();
        ob_end_clean();
        
        return $xml;
    }

   /**
    * Displays the examples main frameset
    *
    * @access   public
    */
    function displayFrameset()
    {
        echo '<html>';
        echo '<head>';
        echo '	<title>'.$this->appName.': examples</title>';
        echo '</head>';
        echo '<frameset cols="300,*" border="1" frameborder="1" framespacing="0">';
        echo '	<frame name="nav" src="index.php?action=navigation" scrolling="yes" marginwidth="0" marginheight="0" frameborder="1">';
        echo '	<frame name="display" src="index.php?action=overview" scrolling="yes" marginwidth="0" marginheight="0" frameborder="1">';
        echo '</frameset>';
        echo '</html>';
    }

   /**
    * Sets the sections from which to generate the examples framework
    *
    * @access   public
    * @param    array   &$sections  The sections definition
    */
    function setSections($sections)
    {
        $this->sections = $sections;
    }
    
   /**
    * Displays the main examples navigation
    *
    * @access   public
    */
    function displayNavigation()
    {
        $this->displayHead('Nav');
    
        echo '<script language="JavaScript1.2" type="text/javascript">';
        echo '	var sections = new Array();';
        echo '	function addSection( section )';
        echo '	{';
        echo '		sections.push( section );';
        echo '	}';
        echo '	function toggleSection( section )';
        echo '	{';
        echo '		var el		=	document.getElementById( section );';
        echo '		var sign	=	document.getElementById( section + \'-sign\' );';
        echo '		if( el.style.display == \'block\' )';
        echo '		{';
        echo '			el.style.display	=	\'none\';';
        echo '			sign.innerHTML		=	\'[+]\';';
        echo '	}';
        echo '		else';
        echo '		{';
        echo '			el.style.display	=	\'block\';';
        echo '			sign.innerHTML		=	\'[-]\';';
        echo '		}';
        echo '	}';
        echo '</script>';
        echo '<h2>Examples navigation</h2>';
        echo '<a href="index.php?action=Overview" target="display" title="Overview of all available examples">&raquo; Overview</a><br/><br/>';

        foreach ($this->sections as $sectionName => $section) {
            echo '<h3 onclick="toggleSection( \''.$sectionName.'\' );"><span id="'.$sectionName.'-sign" class="sign">[+]</span> '.$this->appName.'::'.$sectionName.' ('.count($section['pages']).')</h3>';
            echo '<div id="'.$sectionName.'" class="section"><script language="JavaScript1.2" type="text/javascript">addSection( \''.$sectionName.'\' );</script>';
            
            foreach ($section['pages'] as $pageId => $pageData) {
                $exampleFile = $section['basename'].$pageId;
                if (isset($pageData['alias'])) {
                    $exampleFile = $pageData['alias'];
                }
            
                if (!file_exists($exampleFile.'.php')) {
                    echo '&raquo; '.$pageData['title'].'<br>';
                    continue;
                }
                
                echo '<a href="index.php?action=example&example='.$exampleFile.'" target="display" title="'.$pageData['descr'].'" class="nav">&raquo; '.$pageData['title'].'</a><br/>';
            }
            
            echo '</div>';
        }
        
        echo '<div id="navFooter">';
        echo '	If you need help with '.$this->appName.', want to comment on it or ';
        echo '	need any additional info, please visit the following resources:';
        echo '</div>';
        echo '<div id="navFooterLinks">';
        echo '	<a href="http://forum.php-tools.net/viewforum.php?f='.$this->appForumId.'" class="nav" target="_blank">&raquo; Forum</a><br/>';
        echo '	<a href="http://dogs.php-tools.net/docs/'.$this->appName.'" class="nav" target="_blank">&raquo; API Documentation</a><br/>';
        echo '	<a href="http://www.php-tools.net/site.php?file='.$this->appName.'/documentation.xml" class="nav" target="_blank">&raquo; End-user documentation</a><br/>';
        echo '	<a href="http://www.php-tools.net/site.php?file='.$this->appName.'/overview.xml" class="nav" target="_blank">&raquo; Homepage</a><br/>';
        echo '	<a href="http://bugs.php-tools.net/enter_bug.cgi?product='.$this->appName.'" class="nav" target="_blank">&raquo; Report a bug</a><br/>';
        echo '	<a href="http://cvs.php-tools.net/horde/chora/cvs.php/'.$this->appName.'" class="nav" target="_blank">&raquo; CVS Tree</a><br/>';
        echo '	<a href="http://snaps.php-tools.net" class="nav" target="_blank">&raquo; CVS Snapshots</a>';
        echo '</div>';
        
        $this->displayFooter();
    }
    
   /**
    * Displays the examples overview page
    *
    * @access   public
    */
    function displayOverview()
    {
        $this->displayHead('Overview');

        echo '<h2>'.$this->appName.' examples overview</h2>';
        echo '<h3>';
        echo '	These examples show the functionality of '.$this->appName.' in detail. ';
        echo '	This overview lists all examples with a small description, and to ';
        echo '	navigate the examples, use the navigation on the left.';
        echo '</h3>';
        
        if (!empty($this->appDescription)) {
            echo '<h5>'.$this->appDescription.'</h5>';
        }
        
        echo '<ul>';

        foreach ($this->sections as $section => $sectionData) {
            $state = '';
            if (isset($sectionData['state'])) {
                $state = '('.$sectionData['state'].')';
            }
            
            echo '&raquo; <a href="#'.$section.'">'.$this->appName.'::'.$section.'</a> '.$state.'<br>';
        }
        
        echo '</ul><br/>';

        foreach ($this->sections as $section => $sectionData) {
            echo '<h4><a name="'.$section.'"></a>'.$this->appName.'::'.$section.' ('.count($sectionData['pages']).')</h4>';
            echo '<p>'.$sectionData['descr'].'</p>';
            echo '<ul>';
            foreach ($sectionData['pages'] as $pageId => $pageData) {
                $exampleFile = $sectionData['basename'].$pageId;
                if (isset($pageData['alias'])) {
                    $exampleFile = $pageData['alias'];
                }
    
                $state = '';
                if (isset($pageData['state'])) {
                    $state = '('.$pageData['state'].')';
                }
        
                echo '<li>';
            
                if (!file_exists($exampleFile.'.php')) {
                    echo $pageData['title'].' '.$state.'<br />';
                } else {
                    echo '	<a href="index.php?action=example&example='.$exampleFile.'">'.$pageData['title'].'</a> '.$state.'<br />';
                }
                echo '	'.$pageData['descr'].'<br />';
                echo '</li>';
            }
            echo '</ul><br />';
        }
        
        $this->displayFooter();
    }
    
   /**
    * Displays the header for any example page
    *
    * @access   public
    * @param    string  $area   The area we are in - is added to the body style
    */
    function displayHead($area, $onload = null)
    {
        echo '<html>';
        echo '<head>';
        echo '	<title>'.$this->appName.' Examples</title>';
        echo '	<style>';
        echo '		@import url( patExampleGen/styles.css );';
        
        if (file_exists('../custom.css') || file_exists('custom.css')) {
            echo '		@import url( custom.css );';
        }
        
        echo '	</style>';
        echo '</head>';
        echo '<body class="'.$area.'" onload="'.$onload.'" marginheight="10" marginwidth="10" leftmargin="10" rightmargin="10" topmargin="10" bottommargin="10">';
    }
    
   /**
    * Displays the footer for any example page
    *
    * @access   public
    */
    function displayFooter()
    {
        echo '</body>';
        echo '</html>';
    }

   /**
    * get the example section
    *
    * @access   public
    * @param    string      example id
    * @return   string      section title
    */
    function getExampleSection($id)
    {
        foreach ($this->sections as $title => $spec) {
            if (strncmp($spec['basename'], $id, strlen($spec['basename'])) === 0) {
                return $title;
            }
        }
        
        return false;
    }

   /**
    * Get an example's details
    *
    * @access   public
    * @param    string      example id
    * @return   string      section title
    */
    function getExample($id)
    {
        foreach ($this->sections as $title => $spec) {
            if (strncmp($spec['basename'], $id, strlen($spec['basename'])) !== 0) {
                continue;
            }
                
            foreach ($spec['pages'] as $name => $data) {
                if ($id != $spec['basename'].$name) {
                    continue;
                }
                    
                if (!isset($data['templates'])) {
                    $data['templates'] = array( $id.'.tmpl' );
                }
            
                return $data;
            }
        }

        return false;
    }
    
   /**
    * Get the tabs for an example (per example tabs can be modified/added).
    *
    * @access   public
    * @param    string  $id     The example id
    * @return   array   $tabs   The tabs for the specified example
    */
    function getTabs($id)
    {
        foreach ($this->sections as $title => $spec) {
            if (strncmp($spec['basename'], $id, strlen($spec['basename'])) !== 0) {
                continue;
            }
                
            foreach ($spec['pages'] as $name => $data) {
                if ($id != $spec['basename'].$name) {
                    continue;
                }
                
                if (!isset($data['tabs'])) {
                    return $this->tabs;
                }
            
                $tabs = $this->tabs;
                foreach ($data['tabs'] as $tabId => $tabDef) {
                    if (!isset($tabDef['default'])) {
                        $tabDef['default'] = false;
                    }
                
                    $tabs[$tabId] = $tabDef;
                }
                
                return $tabs;
            }
        }

        return false;
    }
    
   /**
    * Replacement for file_get_contents
    *
    * @access   public
    * @static
    * @param    string  filename
    * @return   mixed   content of the file or false
    */
    function file_get_contents($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }
    
        if (function_exists("file_get_contents")) {
            return file_get_contents($filename);
        }

        $fp =   @fopen($filename, "rb");
        if (!$fp) {
            return false;
        }
        
        flock($fp, LOCK_SH);
        $content    =   fread($fp, filesize($filename));
        flock($fp, LOCK_UN);
        fclose($fp);
        return $content;
    }

   /**
    * Simple url content loader
    *
    * @access   public
    * @static
    * @param    string  $url        The Url to get the content from
    * @return   mixed   $content    The content of the file or false
    */
    function url_get_contents($url)
    {
        if (function_exists("file_get_contents")) {
            return file_get_contents($url);
        }

        $fp =   @fopen($url, "rb");
        if (!$fp) {
            return false;
        }
        
        $content = '';
        while (!feof($fp)) {
            $content    .=  fread($fp, 2000);
        }
        fclose($fp);
        
        return $content;
    }
}
