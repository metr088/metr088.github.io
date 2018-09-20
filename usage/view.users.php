<?
class users extends Main_controller{
public function __construct(){
parent::__construct();
if($this->session['user_id'] != 1) show_404();
 }
public function index(){
$users = $this->db->select('users', '*', ['id[!]' => 1]);
$this->data['users'] = $users;
$this->data['content'] = 'panel/users/index';
$this->load->view('index', $this->data);
}

public function edit($id = null){
if($id == 1) show_404();

$rules = [
'login' => array('field' => 'login', 'label' => 'Логин', 'rules' => 'required|trim|xss_clean|'.$id ? '':'is_unique[users.login]'),
'password' => array('field' => 'password', 'label' => 'Пароль', 'rules' => $id ? '':'required')
];
$this->postValidation->set_rules($rules);


 if($this->postValidation->run() == TRUE){
 $data = $this->input->post(['login', 'password']);

 if(!$data['password'] and $id){
 	unset($data['password']);
 }else{ 
 	$data['password'] = sha1($data['password']); 
 }

 $this->db->save('users', $data, $id);
 redirect('/users');
 }else{
$this->data['error_post'] = $this->postValidation->error_string('<div class="alert alert-danger" role="alert">', '</div>');
$this->data['users'] = $id ? $this->db->get('users', '*', ['id' => $id]):null;
$this->data['content'] = 'panel/users/edit';
$this->load->view('index', $this->data);
 }
}

public function delete($id = null){
if(!$id or $id == 1) show_404();
$this->db->delete('users', ['id' => $id]);

$this->deleteif($id);

redirect('/users');
}


private function deleteif($id = null){
if(!$id) return false;

$row['user_id'] = $id;

$getdata = $this->db->select('manager', '*', $row);

foreach ($getdata as $getdatas) {
$jsonUP = $this->input->toJson($getdatas['result']);
if(file_exists($jsonUP['path'])): unlink($jsonUP['path']); endif;
$this->db->delete('manager', ['id' => $getdatas['id']]);
}

return true;
}

}