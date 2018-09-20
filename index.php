<?php
if (is_dir(dirname(__FILE__).'/install') == true){
    header('Location: /install'); exit;
}
    define('NOTIFY', 'NOT');
    if(NOTIFY == 'ALL'):
	error_reporting(E_ALL);
	error_reporting(-1);
    else: error_reporting(0); endif;
    define('DIR', __DIR__);
    define('DIR_APP', __DIR__ .'/usage/');
    define('DIR_CONTROL', __DIR__ .'/controller/');
    define('PATHER','engine');
    define('DIR_CONFIG', DIR .'/setting/');
    require DIR ."/". PATHER ."/start.php";
?>