<?
//Создатель данного скрипта Melloy
//Alex Elizaryev
//https://vk.com/alexinde
    require_once(DIR ."/". PATHER ."/function.php");
    require_once(DIR .'/'. PATHER ."/framework.php");
    //exit();

	function get_instance(){return FrameWork::get_instance();}
    set_error_handler('_exception_handler');
    $URI = load_class('uri', 'core');
	$RTR = load_class('Router', 'core');
    require_once DIR ."/". PATHER ."/controller.php";
	$RTR->_set_routing();
	if (isset($routing)){
	$RTR->_set_overrides($routing);
	}
	$diref = DIR_APP.$RTR->fetch_directory();
    $diref = str_replace('//', '/', $diref);
    $fatch_controller = ($RTR->fetch_directory() != '' ? $RTR->fetch_directory():'main');
    $fatch_controller = str_replace('/', '', $fatch_controller);
	$diref_controller = DIR_CONTROL;
    $diref_controller = str_replace('//', '/', $diref_controller);
	if (!file_exists($diref.'view.'.$RTR->fetch_class().'.php')){
	show_error('Unable to load your default view. Please make sure the view specified in your Routes.php file is valid.');
	}
	if (!file_exists($diref_controller.'device.'.$fatch_controller.'.php')){
	show_error('Unable to load your default controller. Please make sure the controller specified in your Routes.php file is valid.');
	}
	require($diref_controller.'device.'.$fatch_controller.'.php');
	require($diref.'view.'.$RTR->fetch_class().'.php');
	$class  = $RTR->fetch_class();
	$method = $RTR->fetch_method();
	$direxer = $RTR->fetch_directory();
    $FWM = new $class();
	if (method_exists($FWM, '_remap')){
	$FWM->_remap($method, array_slice($URI->rsegments, 2));
	}else{
	$direw = DIR_APP.$direxer;
    $direw = str_replace('//', '/', $direw);
    $fatchw_controller = ($direxer != '' ? $direxer:'main');
    $fatchw_controller = str_replace('/', '', $fatchw_controller);
	$direw_controller = DIR_CONTROL;
    $direw_controller = str_replace('//', '/', $direw_controller);
	if (!in_array(strtolower($method), array_map('strtolower', get_class_methods($FWM)))){
	if (!class_exists($class)){
	if (!file_exists($direw.'view.'.$class.'.php')){
	show_error("{$class}/{$method}");
	}
    if (!file_exists($direw_controller.'device.'.$class.'.php')){
	show_error("{$class}/{$method}");
	}					
    require_once($direw_controller.'controller/device.'.$fatchw_controller.'.php');
	require_once($direw.'view.'.$class.'.php');
	unset($FWM);
	$FWM = new $class();
	}
	}
	if(!method_exists($class,$method)): show_error("Такой страницы не найденно: {$method}"); endif;
	call_user_func_array(array(&$FWM, $method), array_slice($URI->rsegments, 2));
	}




	if (class_exists('db') AND isset($FWM->db)){
	$FWM->db->close();
	}
?>