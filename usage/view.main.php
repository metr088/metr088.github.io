<?
class main extends Main_controller{
public function __construct(){
parent::__construct();
 }
public function index(){
$pop = 'panel/stat';
if($this->session['user_id'] != 1){
$pop = 'panelUser/stat';
}
$this->data['content'] = $pop;
$this->load->view('index', $this->data);

}

}

?>