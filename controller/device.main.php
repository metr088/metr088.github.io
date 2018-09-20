<?
class Main_controller extends controller{
public function __construct(){
parent::__construct();
$this->load->lib('session');


   $url_string = array('login');
   if(!in_array($this->uri->uri_string(), $url_string)){
    if(!$this->session['user_id']){
     redirect('/login');
     exit();
    }
   }



 }
}

?>