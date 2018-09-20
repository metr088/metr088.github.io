<?
class login extends Main_controller{
public function __construct(){
parent::__construct();
if($this->session['user_id']){
redirect('/');
exit();
}
 }
public function index(){
$this->data['error'] = false;
$rules = [
'login' => array('field' => 'login', 'label' => 'Логин', 'rules' => 'trim|required|xss_clean'),
'password' => array('field' => 'password', 'label' => 'Пароль', 'rules' => 'required|sha1')
];
$this->postValidation->set_rules($rules);

if($this->postValidation->run() == TRUE){
$data = $this->input->post(['login', 'password']);
$countuser = $this->db->count('users', ['login' => $data['login'], 'password' => $data['password']]);
 if($countuser > 0){
 $getuser = $this->db->get('users', ['id','login','password'], ['login' => $data['login'], 'password' => $data['password']]);


 $this->session->setUser([
 'user_id' => $getuser['id'],
 'user_login' => $getuser['login'],
 'user_password' => $getuser['password']
 ]);
 redirect('/');
 }else{
 $this->data['error'] = 'Неверный пароль или логин!';
 }
}else{
 $this->data['error_post'] = $this->postValidation->error_string('<div class="alert alert-danger" role="alert">', '</div>');
}


$this->data['post_data'] = @$data ? $data:null;
$this->load->view('login',$this->data);

}

}

?>