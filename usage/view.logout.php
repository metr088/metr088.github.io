<?
class logout extends Main_controller{
public function __construct(){
parent::__construct();
 }
public function index(){

$this->session->destroy();
redirect('/login');

}

}

?>