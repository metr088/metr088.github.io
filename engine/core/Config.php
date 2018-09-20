<?php

class Config{

protected $_config_data = array();

public function __construct(){
$config = require_once(DIR_CONFIG ."config.php");
$this->db = load_class('db', 'core');
$this->_config_data = $config;
$aonfig = $this->db->select('config', "*");
foreach ($aonfig as $row) {
$this->set_item($row['argument'], $row['body']);
}
}


public function set_item($str, $default){

if(is_array($str)){
$arr = array_merge($this->_config_data, $str);
}else{
$push = array($str => $default);
$arr = array_merge($this->_config_data, $push);
}

$this->_config_data = $arr;
}


public function item($str = null){
return $str ? $this->_config_data[$str]:$this->_config_data;	
}


public function set_db_item($name = null, $value = null){
if(empty($name)): return false; endif;

$appconfig = $this->db->count('config', ['argument' => $name]);
if($appconfig > 0): 
	$this->db->update('config', ['body' => $value], ['argument' => $name]);
else:
	$this->db->insert('config', ['argument' => $name, 'body' => $value]);
endif;
$this->set_item($name, $value);
return true;
}

public function set_db_items($data = array()){
if(!$data): return false; endif;

foreach ($data as $name => $value) {
$appconfig = $this->db->count('config', ['argument' => $name]);
if($appconfig > 0):
	$this->db->update('config', ['body' => $value], ['argument' => $name]);
else:
	$this->db->insert('config', ['argument' => $name, 'body' => $value]);
endif;
$this->set_item($name, $value);
}

return true;
}

}

?>