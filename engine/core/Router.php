<?php
class Router {
	var $routes			= array();
	var $error_routes	= array();
	var $class			= '';
	var $method			= 'index';
	var $directory		= '';
	var $default_controller;

	function __construct(){
		$this->uri =& load_class('uri', 'core');
	}

	function _set_routing(){
		$segments = array();
        $route = require_once(DIR_CONFIG ."router.php");
        foreach($route as $key => $value){
        $routers[$key] = str_replace(":", "/", $value);
        }
        unset($route);
		$this->routes = (!isset($routers) OR !is_array($routers))? array():$routers;
		unset($routers);

        $this->default_controller = (!isset($this->routes['index']) OR $this->routes['index'] == '') ? FALSE : $this->routes['index'];

		if (count($segments) > 0){
		return $this->_validate_request($segments);
		}
		$this->uri->_fetch_uri_string();
		if ($this->uri->uri_string == '')
		{
		return $this->_set_default_controller();
		}
		$this->uri->_explode_segments();
		$this->_parse_routes();
		$this->uri->_reindex_segments();
	}

	function _set_default_controller()
	{
		if ($this->default_controller === FALSE)
		{
			show_error("Не удается определить, что должно отображаться. Маршрут по умолчанию не указан в файле маршрутизации.");
		}
		if (strpos($this->default_controller, '/') !== FALSE)
		{
			$x = explode('/', $this->default_controller);

			$this->set_class($x[0]);
			$this->set_method($x[1]);
			$this->_set_request($x);
		}else{

			$this->set_class($this->default_controller);
			$this->set_method('index');
			$this->_set_request(array($this->default_controller, 'index'));
		}

		$this->uri->_reindex_segments();
	}

	function _set_request($segments = array())
	{
		$segments = $this->_validate_request($segments);

		if (count($segments) == 0)
		{
			return $this->_set_default_controller();
		}

		$this->set_class($segments[0]);

		if (isset($segments[1]))
		{
			$this->set_method($segments[1]);
		}
		else
		{
			$segments[1] = 'index';
		}

		$this->uri->rsegments = $segments;
	}

	function _validate_request($segments)
	{
		if (count($segments) == 0){
			return $segments;
		}

		if (file_exists(DIR_APP.'view.'.$segments[0].'.php')){
			return $segments;
		}

		if (is_dir(DIR_APP.$segments[0]))
		{
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);	
			if (count($segments) > 0){			
				$diref = DIR_APP.$this->fetch_directory();
                $diref = str_replace('//', '/', $diref);
				if ( ! file_exists($diref.'view.'.$segments[0].'.php')){
					show_error('Запрошенной страницы не существует.');
				}
			}else{
				if (strpos($this->default_controller, '/') !== FALSE)
				{
					$x = explode('/', $this->default_controller);
					$this->set_class($x[0]);
					$this->set_method($x[1]);
				}else{
					$this->set_class($this->default_controller);
					$this->set_method('index');
				} 
				$direfr = DIR_APP.$this->fetch_directory();
                $direfr = str_replace('//', '/', $direfr);
				if ( ! file_exists($direfr.'/view.'.(isset($x[0])? $x[0]:$this->default_controller).'.php')){
					$this->directory = '';
					return array();
				}
			//$this->directory = '';	
			//$this->set_directory((isset($x[0])? $this->uri->segment(0):$this->uri->segment(0)));
			//$segments[0] = (isset($x[0])? $x[0]:$this->default_controller);
			//echo $this->uri->segment(0);

			}
			//var_dump($segments);
			return $segments;
		}
		show_404();
        //show_error('Запрошенной страницы не существует.');
	}

	function _parse_routes()
	{
		$uri = implode('/', $this->uri->segments);

		if (isset($this->routes[$uri]))
		{
			return $this->_set_request(explode('/', $this->routes[$uri]));
		}

		foreach ($this->routes as $key => $val)
		{
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

			if (preg_match('#^'.$key.'$#', $uri))
			{
				if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}

				return $this->_set_request(explode('/', $val));
			}
		}
		$this->_set_request($this->uri->segments);
	}

	function set_class($class)
	{
		$this->class = str_replace(array('/', '.'), '', $class);
	}

	function fetch_class()
	{
		return $this->class;
	}

	function set_method($method)
	{
		$this->method = $method;
	}
	function fetch_method()
	{
		if ($this->method == $this->fetch_class())
		{
			return 'index';
		}

		return $this->method;
	}
	function set_directory($dir)
	{
		$this->directory = str_replace(array('/','.'), '', $dir).'/';
	}
	function fetch_directory()
	{
		return $this->directory;
	}
	function _set_overrides($routing)
	{
		if ( ! is_array($routing))
		{
			return;
		}

		if (isset($routing['directory']))
		{
			$this->set_directory($routing['directory']);
		}

		if (isset($routing['controller']) AND $routing['controller'] != '')
		{
			$this->set_class($routing['controller']);
		}

		if (isset($routing['function']))
		{
			$routing['function'] = ($routing['function'] == '') ? 'index' : $routing['function'];
			$this->set_method($routing['function']);
		}
	}


}
?>