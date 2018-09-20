<?
//Создатель данного скрипта Melloy
//Alex Elizaryev
//https://vk.com/alexinde
class AutoLoader{

protected $data;	
protected $cached_vars = array();
var $_ci_classes = array();
var $_ci_varmap = array();
	public function initialize(){
		return $this;
	}


public function view($view, $vars = array()){
		return $this->_is_view($view,$vars);
	}
protected function _is_view($view='', array $app=array()){
	$tpl = DIR.'/view/'.$view;
	$exites = pathinfo($tpl, PATHINFO_EXTENSION);
	$urist = ($exites == '') ? $tpl.'.php' : $tpl;

   		$Auget = get_instance();
		foreach (get_object_vars($Auget) as $Aukey => $Auvar){
		if ( ! isset($this->{$Aukey})){
				$this->{$Aukey} =& $Auget->{$Aukey};
		}
		}
	if (is_array($app)){
	$this->cached_vars = array_merge($this->cached_vars, $app);
	}
	extract($this->cached_vars);

	if(file_exists($urist)){
	include($urist);
    }else{
    show_error('Файл: '.$view.'.php не найден!');
    }
 }
	public function libs($helper = []){
    foreach ($helper as $key => $value) {
    $this->lib($value);
    }
    return true;
	}
	public function lib($helper = null,$app=null){
        if(empty($helper)): return false; endif;
		$base_helper = DIR.'/'.PATHER.'/lib/'.$helper.'.php';
		if (!file_exists($base_helper)){
		show_error('Файл: '.$helper.'.php не найден!');
		}
		include_once($base_helper);
        return $this->_ci_init_class($helper, null, $app);
	}

	protected function prep_filename($filename, $extension){
		if ( ! is_array($filename))
		{
			return array(strtolower(str_replace('.php', '', str_replace($extension, '', $filename)).$extension));
		}
		else
		{
			foreach ($filename as $key => $val)
			{
				$filename[$key] = strtolower(str_replace('.php', '', str_replace($extension, '', $val)).$extension);
			}

			return $filename;
		}
	}


	protected function _ci_init_class($class, $sufix = null, $config = null, $object_name = NULL){

			$name = $class;
		

		// Is the class name valid?
		if ( ! class_exists($name)){
			show_error("Non-existent class: ".$class);
		}

		// Set the variable name we will assign the class to
		// Was a custom class name supplied?  If so we'll use it
		$class = strtolower($class);
        if(isset($sufix)):
		$classvar = $class.'_'.$sufix;
        else:
        $classvar = $class;
        endif;
		$this->_ci_classes[$class] = $classvar;


		$CI = get_instance();
		if ($config !== NULL)
		{
			$CI->$classvar = new $name($config);
		}
		else
		{
			$CI->$classvar = new $name;
		}
	}



}
?>