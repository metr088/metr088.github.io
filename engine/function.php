<?
//Создатель данного скрипта Melloy
//Alex Elizaryev
//https://vk.com/alexinde
if ( ! function_exists('genRandomString')){
function genRandomString($length = 32, $net = null) {
    $characters = $net == null ? "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-*&}{!%":$net;

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    return $string;
}
}
if ( ! function_exists('request_url')){
function request_url($uri = null, $enc = null){
  $result = '';
  $default_port = 80;
  if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
    $result .= 'https://';
    $default_port = 443;
  } else {
    $result .= 'http://';
  }
  $result .= $_SERVER['SERVER_NAME'];
  if ($_SERVER['SERVER_PORT'] != $default_port) {
    $result .= ':'.$_SERVER['SERVER_PORT'];
  }
  if($uri):
  $result .= '/'.$uri;
  endif;
  if($enc):
  $result = urlencode($result);
  endif;
  return $result;
}
}

if ( ! function_exists('remSumbol')){
function remSumbol($str = null){
$str = substr($str,0,-4);

return $str.'****';
}
}



if ( ! function_exists('form_dropdown'))
{
	function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '')
	{
		if ( ! is_array($selected))
		{
			$selected = array($selected);
		}

		// If no selected state was submitted we will attempt to set it automatically
		if (count($selected) === 0)
		{
			// If the form name appears in the $_POST array we have a winner!
			if (isset($_POST[$name]))
			{
				$selected = array($_POST[$name]);
			}
		}

		if ($extra != '') $extra = ' '.$extra;

		$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select name="'.$name.'"'.$extra.$multiple.">\n";

		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val) && ! empty($val))
			{
				$form .= '<optgroup label="'.$key.'">'."\n";

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';

					$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
				}

				$form .= '</optgroup>'."\n";
			}
			else
			{
				$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';

				$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
			}
		}

		$form .= '</select>';

		return $form;
	}
}



if ( ! function_exists('redirect')){
	function redirect($uri = '', $method = 'location', $http_response_code = 302){

		switch($method){
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
}

if ( ! function_exists('load_class')){
	function &load_class($class, $directory = ''){
		static $_classes = array();

		// Does the class exist?  If so, we're done...
		if (isset($_classes[$class])){
			return $_classes[$class];
		}

		$name = FALSE;

		// Look for the class first in the local application/libraries folder
		// then in the native system/libraries folder
		$direct = $directory ? $directory.'/':$directory;
		$path = DIR.'/'.PATHER.'/'.$direct;
     

			if (file_exists($path.$class.'.php')){
				$name = $class;

				if (class_exists($name) === FALSE){
					require($path.$class.'.php');
				}
			}

		if ($name === FALSE){
			show_404();
		}

		// Keep track of what we just loaded
		is_loaded($class);

		$_classes[$class] = new $name();
		return $_classes[$class];
	}
}
if ( ! function_exists('is_loaded'))
{
	function &is_loaded($class = '')
	{
		static $_is_loaded = array();

		if ($class != '')
		{
			$_is_loaded[strtolower($class)] = $class;
		}

		return $_is_loaded;
	}
}


if ( ! function_exists('remove_invisible_characters'))
{
	function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();
		
		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)
		
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}
		
		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}
}

if ( ! function_exists('show_error'))
{
	function show_error($message, $status_code = 500, $heading = 'Произошла ошибка')
	{
		$_error =& load_class('Exceptions', 'core');
		echo $_error->show_error($heading, $message, 'error_general', $status_code);
		exit;
	}
}

if ( ! function_exists('show_404'))
{
	function show_404($page = '', $log_error = TRUE)
	{
		$_error =& load_class('Exceptions', 'core');
		$_error->show_404($page, $log_error);
		exit;
	}
}


if ( ! function_exists('set_status_header'))
{
	function set_status_header($code = 200, $text = '')
	{
		$stati = array(
							200	=> 'OK',
							201	=> 'Created',
							202	=> 'Accepted',
							203	=> 'Non-Authoritative Information',
							204	=> 'No Content',
							205	=> 'Reset Content',
							206	=> 'Partial Content',

							300	=> 'Multiple Choices',
							301	=> 'Moved Permanently',
							302	=> 'Found',
							304	=> 'Not Modified',
							305	=> 'Use Proxy',
							307	=> 'Temporary Redirect',

							400	=> 'Bad Request',
							401	=> 'Unauthorized',
							403	=> 'Forbidden',
							404	=> 'Not Found',
							405	=> 'Method Not Allowed',
							406	=> 'Not Acceptable',
							407	=> 'Proxy Authentication Required',
							408	=> 'Request Timeout',
							409	=> 'Conflict',
							410	=> 'Gone',
							411	=> 'Length Required',
							412	=> 'Precondition Failed',
							413	=> 'Request Entity Too Large',
							414	=> 'Request-URI Too Long',
							415	=> 'Unsupported Media Type',
							416	=> 'Requested Range Not Satisfiable',
							417	=> 'Expectation Failed',

							500	=> 'Internal Server Error',
							501	=> 'Not Implemented',
							502	=> 'Bad Gateway',
							503	=> 'Service Unavailable',
							504	=> 'Gateway Timeout',
							505	=> 'HTTP Version Not Supported'
						);

		if ($code == '' OR ! is_numeric($code))
		{
			show_error('Status codes must be numeric', 500);
		}

		if (isset($stati[$code]) AND $text == '')
		{
			$text = $stati[$code];
		}

		if ($text == '')
		{
			show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
		}

		$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

		if (substr(php_sapi_name(), 0, 3) == 'cgi')
		{
			@header("Status: {$code} {$text}", TRUE);
		}
		elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
		{
			@header($server_protocol." {$code} {$text}", TRUE, $code);
		}
		else
		{
			@header("HTTP/1.1 {$code} {$text}", TRUE, $code);
		}
	}
}


if ( ! function_exists('_exception_handler'))
{
	function _exception_handler($severity, $message, $filepath, $line)
	{
		if ($severity == E_STRICT)
		{
			return;
		}

		$_error =& load_class('Exceptions', 'core');

		// Should we display the error? We'll get the current error_reporting
		// level and add its bits with the severity bits to find out.
		if (($severity & error_reporting()) == $severity)
		{
			$_error->show_php_error($severity, $message, $filepath, $line);
		}
	}
}
if ( ! function_exists('RemoveDir'))
{
function RemoveDir($path) {
 if ( file_exists( $path ) AND is_dir( $path ) ) {
    $dir = opendir($path);
    while ( false !== ( $element = readdir( $dir ) ) ) {
      if ( $element != '.' AND $element != '..' )  {
        $tmp = $path . '/' . $element;
        chmod( $tmp, 0777 );
        if ( is_dir( $tmp ) ) {
         RDir( $tmp );
        } else {
          unlink( $tmp );
       }
     }
   }
    closedir($dir);
   if ( file_exists( $path ) ) {
     rmdir( $path );
   }
 }
}
}

?>