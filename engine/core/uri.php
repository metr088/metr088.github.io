<?php
class uri {
	var	$keyval			= array();
	var $uri_string;
	var $segments		= array();
	var $rsegments		= array();
	protected $config;

	function __construct(){
	$this->config = load_class('Config', 'core')->item();
	}

	function _fetch_uri_string(){


		if (strtoupper($this->config['uri_protocol']) == 'AUTO')
		{
			// Is the request coming from the command line?
			if (php_sapi_name() == 'cli' or defined('STDIN'))
			{
				$this->_set_uri_string($this->_parse_cli_args());
				return;
			}

			// Let's try the REQUEST_URI first, this will work in most situations
			if ($uri = $this->_detect_uri())
			{
				$this->_set_uri_string($uri);
				return;
			}

			// Is there a PATH_INFO variable?
			// Note: some servers seem to have trouble with getenv() so we'll test it two ways
			$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
			if (trim($path, '/') != '' && $path != "/".SELF)
			{
				$this->_set_uri_string($path);
				return;
			}

			// No PATH_INFO?... What about QUERY_STRING?
			$path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
			if (trim($path, '/') != '')
			{
				$this->_set_uri_string($path);
				return;
			}

			// As a last ditch effort lets try using the $_GET array
			if (is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '')
			{
				$this->_set_uri_string(key($_GET));
				return;
			}

			// We've exhausted all our options...
			$this->uri_string = '';
			return;
		}

		$uri = strtoupper($this->config['uri_protocol']);


		if ($uri == 'REQUEST_URI'){
			$this->_set_uri_string($this->_detect_uri());
			return;
		}
		elseif ($uri == 'CLI'){
			$this->_set_uri_string($this->_parse_cli_args());
			return;
		}

		$path = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
		$this->_set_uri_string($path);
	}

	function _set_uri_string($str)
	{
		// Filter out control characters
		$str = remove_invisible_characters($str, FALSE);

		// If the URI contains only a slash we'll kill it
		$this->uri_string = ($str == '/') ? '' : $str;
	}

	private function _detect_uri(){
		if ( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME']))
		{
			return '';
		}

		$uri = $_SERVER['REQUEST_URI'];
		if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
		{
			$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
		}
		elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
		{
			$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
		}

		// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
		// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
		if (strncmp($uri, '?/', 2) === 0)
		{
			$uri = substr($uri, 2);
		}
		$parts = preg_split('#\?#i', $uri, 2);
		$uri = $parts[0];
		if (isset($parts[1]))
		{
			$_SERVER['QUERY_STRING'] = $parts[1];
			parse_str($_SERVER['QUERY_STRING'], $_GET);
		}
		else
		{
			$_SERVER['QUERY_STRING'] = '';
			$_GET = array();
		}

		if ($uri == '/' || empty($uri))
		{
			return '/';
		}

		$uri = parse_url($uri, PHP_URL_PATH);

		// Do some final cleaning of the URI and return it
		return str_replace(array('//', '../'), '/', trim($uri, '/'));
	}

	// --------------------------------------------------------------------

	/**
	 * Parse cli arguments
	 *
	 * Take each command line argument and assume it is a URI segment.
	 *
	 * @access	private
	 * @return	string
	 */
	private function _parse_cli_args()
	{
		$args = array_slice($_SERVER['argv'], 1);

		return $args ? '/' . implode('/', $args) : '';
	}

	// --------------------------------------------------------------------

	/**
	 * Filter segments for malicious characters
	 *
	 * @access	private
	 * @param	string
	 * @return	string
	 */
	function _filter_uri($str)
	{
		if ($str != '' && $this->config['permitted_uri_chars'] != '')
		{
			// preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
			// compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
			if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($this->config['permitted_uri_chars'], '-'))."]+$|i", $str))
			{
				show_error('The URI you submitted has disallowed characters.');
			}
		}

		// Convert programatic characters to entities
		$bad	= array('$',		'(',		')',		'%28',		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

		return str_replace($bad, $good, $str);
	}

	// --------------------------------------------------------------------

	/**
	 * Explode the URI Segments. The individual segments will
	 * be stored in the $this->segments array.
	 *
	 * @access	private
	 * @return	void
	 */
	function _explode_segments()
	{
		foreach (explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $this->uri_string)) as $val)
		{
			// Filter segments for security
			$val = trim($this->_filter_uri($val));

			if ($val != '')
			{
				$this->segments[] = $val;
			}
		}
	}

	// --------------------------------------------------------------------
	/**
	 * Re-index Segments
	 *
	 * This function re-indexes the $this->segment array so that it
	 * starts at 1 rather than 0.  Doing so makes it simpler to
	 * use functions like $this->uri->segment(n) since there is
	 * a 1:1 relationship between the segment array and the actual segments.
	 *
	 * @access	private
	 * @return	void
	 */
	function _reindex_segments()
	{
		array_unshift($this->segments, NULL);
		array_unshift($this->rsegments, NULL);
		unset($this->segments[0]);
		unset($this->rsegments[0]);
	}

	function segment($n, $no_result = FALSE)
	{
		return ( ! isset($this->segments[$n])) ? $no_result : $this->segments[$n];
	}

	function rsegment($n, $no_result = FALSE)
	{
		return ( ! isset($this->rsegments[$n])) ? $no_result : $this->rsegments[$n];
	}

	function uri_to_assoc($n = 3, $default = array())
	{
		return $this->_uri_to_assoc($n, $default, 'segment');
	}

	function ruri_to_assoc($n = 3, $default = array())
	{
		return $this->_uri_to_assoc($n, $default, 'rsegment');
	}

	function _uri_to_assoc($n = 3, $default = array(), $which = 'segment')
	{
		if ($which == 'segment')
		{
			$total_segments = 'total_segments';
			$segment_array = 'segment_array';
		}
		else
		{
			$total_segments = 'total_rsegments';
			$segment_array = 'rsegment_array';
		}

		if ( ! is_numeric($n))
		{
			return $default;
		}

		if (isset($this->keyval[$n]))
		{
			return $this->keyval[$n];
		}

		if ($this->$total_segments() < $n)
		{
			if (count($default) == 0)
			{
				return array();
			}

			$retval = array();
			foreach ($default as $val)
			{
				$retval[$val] = FALSE;
			}
			return $retval;
		}

		$segments = array_slice($this->$segment_array(), ($n - 1));

		$i = 0;
		$lastval = '';
		$retval  = array();
		foreach ($segments as $seg)
		{
			if ($i % 2)
			{
				$retval[$lastval] = $seg;
			}
			else
			{
				$retval[$seg] = FALSE;
				$lastval = $seg;
			}

			$i++;
		}

		if (count($default) > 0)
		{
			foreach ($default as $val)
			{
				if ( ! array_key_exists($val, $retval))
				{
					$retval[$val] = FALSE;
				}
			}
		}

		// Cache the array for reuse
		$this->keyval[$n] = $retval;
		return $retval;
	}

	function assoc_to_uri($array)
	{
		$temp = array();
		foreach ((array)$array as $key => $val)
		{
			$temp[] = $key;
			$temp[] = $val;
		}

		return implode('/', $temp);
	}

	function slash_segment($n, $where = 'trailing')
	{
		return $this->_slash_segment($n, $where, 'segment');
	}

	function slash_rsegment($n, $where = 'trailing')
	{
		return $this->_slash_segment($n, $where, 'rsegment');
	}

	function _slash_segment($n, $where = 'trailing', $which = 'segment')
	{
		$leading	= '/';
		$trailing	= '/';

		if ($where == 'trailing')
		{
			$leading	= '';
		}
		elseif ($where == 'leading')
		{
			$trailing	= '';
		}

		return $leading.$this->$which($n).$trailing;
	}

	function segment_array()
	{
		return $this->segments;
	}

	function rsegment_array()
	{
		return $this->rsegments;
	}

	function total_segments()
	{
		return count($this->segments);
	}

	function total_rsegments()
	{
		return count($this->rsegments);
	}

	function uri_string()
	{
		return $this->uri_string;
	}

	function ruri_string()
	{
		return '/'.implode('/', $this->rsegment_array());
	}

}
?>