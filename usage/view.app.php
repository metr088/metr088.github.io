<?
class app extends Main_controller{
public function __construct(){
parent::__construct();
 }
 public function index(){
header('Content-type: text/javascript; charset=UTF-8');
$this->load->view('app');
 }

}