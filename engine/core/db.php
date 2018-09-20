<?
require_once(DIR .'/'. PATHER ."/class/Medoo.php");
use Medoo\Medoo;
class db extends Medoo{
public function __construct(){


$dbind = include(DIR_CONFIG ."database.php");
$testconnect = parent::__construct($dbind);

if($testconnect){
show_error('Нет соединения с базой данных.', 404, '');
}
}

public function save($table, $data, $id = null){

$u = is_array($id) ? $id:['id' => $id];

if(!$id){
$this->insert($table, $data);
}else{
$this->update($table, $data, $u);
}
return !$id ? $this->id():$id;
}

}