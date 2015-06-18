<?php
define("DOMPDF_DIR", $_SERVER['DOCUMENT_ROOT'].'/_shared/dompdf' );
define("DOMPDF_INC_DIR", DOMPDF_DIR . "/include");
define("DOMPDF_LIB_DIR", DOMPDF_DIR . "/lib");

require_once(DOMPDF_INC_DIR . "/functions.inc.php");

def("DOMPDF_FONT_DIR", DOMPDF_DIR . "/lib/fonts/");
def("DOMPDF_FONT_CACHE", DOMPDF_FONT_DIR);
def("DOMPDF_TEMP_DIR", sys_get_temp_dir());
def("DOMPDF_CHROOT", realpath(DOMPDF_DIR));
def("DOMPDF_UNICODE_ENABLED", true);
if ( strpos(PHP_OS, "WIN") === false )
  def("TTF2AFM", DOMPDF_LIB_DIR ."/ttf2ufm/ttf2ufm-src/ttf2pt1");
else 
  def("TTF2AFM", "C:\\Program Files\\GnuWin32\\bin\\ttf2pt1.exe");
def("DOMPDF_PDF_BACKEND", "CPDF");
def("DOMPDF_DEFAULT_MEDIA_TYPE", "screen");
def("DOMPDF_DEFAULT_PAPER_SIZE", "a4");
def("DOMPDF_DEFAULT_FONT", "helvetica");
def("DOMPDF_DPI", 96);
def("DOMPDF_ENABLE_PHP", true);
def("DOMPDF_ENABLE_JAVASCRIPT", true);
def("DOMPDF_ENABLE_REMOTE", true);
def("DOMPDF_LOG_OUTPUT_FILE", DOMPDF_FONT_DIR."log.htm");
def("DOMPDF_FONT_HEIGHT_RATIO", 0.8);
def("DOMPDF_ENABLE_CSS_FLOAT", true);
function DOMPDF_autoload($class) {
  $filename = DOMPDF_INC_DIR . "/" . mb_strtolower($class) . ".cls.php";
  
  if ( is_file($filename) )
    require_once($filename);
}

// If SPL autoload functions are available (PHP >= 5.1.2)
if ( function_exists("spl_autoload_register") ) {
  $autoload = "DOMPDF_autoload";
  $funcs = spl_autoload_functions();
  
  // No functions currently in the stack. 
  if ( $funcs === false ) { 
    spl_autoload_register($autoload); 
  }
  
  // If PHP >= 5.3 the $prepend argument is available
  else if ( version_compare(PHP_VERSION, '5.3', '>=') ) {
    spl_autoload_register($autoload, true, true); 
  }
  
  else {
    // Unregister existing autoloaders... 
    $compat = version_compare(PHP_VERSION, '5.1.2', '<=') && 
              version_compare(PHP_VERSION, '5.1.0', '>=');
              
    foreach ($funcs as $func) { 
      if (is_array($func)) { 
        // :TRICKY: There are some compatibility issues and some 
        // places where we need to error out 
        $reflector = new ReflectionMethod($func[0], $func[1]); 
        if (!$reflector->isStatic()) { 
          throw new Exception('This function is not compatible with non-static object methods due to PHP Bug #44144.'); 
        }
        
        // Suprisingly, spl_autoload_register supports the 
        // Class::staticMethod callback format, although call_user_func doesn't 
        if ($compat) $func = implode('::', $func); 
      }
      
      spl_autoload_unregister($func); 
    } 
    
    // Register the new one, thus putting it at the front of the stack... 
    spl_autoload_register($autoload); 
    
    // Now, go back and re-register all of our old ones. 
    foreach ($funcs as $func) { 
      spl_autoload_register($func); 
    }

if ( function_exists("__autoload") ) {
      spl_autoload_register("__autoload");
    }
  }
}

else if ( !function_exists("__autoload") ) {

function __autoload($class) {
    DOMPDF_autoload($class);
  }
}

mb_internal_encoding('UTF-8');

global $_dompdf_warnings;
$_dompdf_warnings = array();
global $_dompdf_show_warnings;
$_dompdf_show_warnings = false;
global $_dompdf_debug;
$_dompdf_debug = false;
global $_DOMPDF_DEBUG_TYPES;
$_DOMPDF_DEBUG_TYPES = array(); //array("page-break" => 1);
def('DEBUGPNG', false);
def('DEBUGKEEPTEMP', false);
def('DEBUGCSS', false);
def('DEBUG_LAYOUT', false);
def('DEBUG_LAYOUT_LINES', true);
def('DEBUG_LAYOUT_BLOCKS', true);
def('DEBUG_LAYOUT_INLINE', true);
def('DEBUG_LAYOUT_PADDINGBOX', true);


// We check wether the user is accessing the demo locally

  if ( get_magic_quotes_gpc() )
    $_POST["html"] = stripslashes($_POST["html"]);
	
	$corpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		<head>
		<title>Ficha</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		
		<link href="http://localhost:8888/adm/admin_pdf.css" rel="stylesheet" type="text/css" media="all">
		<link href="http://localhost:8888/adm/admin_propilpar.css" rel="stylesheet" type="text/css" media="all">
		<link href="http://localhost:8888/adm/print.css" rel="stylesheet" type="text/css" media="print">
		</head>
		<body class="site">
		<div id=topo><h1>Sulivan Rodrigues (Administrador)</h1></div>

						<div id="menu">Gerado em '.date("d/m/Y H:i:s").'</div>
						<div id="conteudo">
							<div class="titulo"><h1>Dados do registro</h1></div>
							<div class="content">
							'.$_POST["html"].'
							</div>
						</div>
		</body></html>';
  
  $dompdf = new DOMPDF();
  //$corpo = file_get_contents("23.html");
  $dompdf->load_html($corpo);
  $dompdf->set_paper(DOMPDF_DEFAULT_PAPER_SIZE, "landscape");
  $dompdf->render();

  $dompdf->stream($_POST["file"].".pdf", array("Attachment" => false));

  ?>