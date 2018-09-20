<?
//Создатель данного скрипта Melloy
//Alex Elizaryev
//https://vk.com/alexinde
class FrameWork{
    private static $instance;

	public function __construct(){
    self::$instance = $this;
    $this->db = load_class('db', 'core');
    $AutoLoad = require_once(DIR_CONFIG ."plugins.php");
    foreach ($AutoLoad as $key => $value) {
    if($value['path'] == ''){
    $this->{$key} = load_class($value['class']);
      }else{
    $this->{$key} = load_class($value['class'], $value['path']);
     }
    }

    $this->load = load_class('AutoLoader', '');
    $this->load->initialize();
	}

	public static function &get_instance(){
		return self::$instance;
	}

}
?>