<?
class configs extends Main_controller{
public function __construct(){
parent::__construct();
if($this->session['user_id'] != 1){
show_404();
}
 }
public function index(){
$theme_grad = array('nogradient' => 'Без дополнений', 'gradient1' => 'Персик', 'gradient3' => 'Синий', 'gradient2' => 'Пурпур', 'gradient4' => 'Аква');
$rules = [
'title' => array('field' => 'title', 'label' => 'Название сайта', 'rules' => 'trim|xss_clean'),
'gate_time' => array('field' => 'gate_time', 'label' => 'Время запроса', 'rules' => 'is_natural'),
'gate_count' => array('field' => 'gate_count', 'label' => 'Кол-во заросов', 'rules' => 'is_natural'),
'icon' => array('field' => 'icon', 'label' => 'Иконка сайта', 'rules' => 'trim'),
'login_icon' => array('field' => 'login_icon', 'label' => 'Иконка авторизации', 'rules' => 'trim'),
'theme' => array('field' => 'theme', 'label' => 'Тема', 'rules' => 'in_list[skin-black,skin-blue,skin-yellow,skin-green,skin-purple,skin-red]'),
'theme_grad' => array('field' => 'theme_grad', 'label' => 'Доп. темы', 'rules' => 'in_list[nogradient,gradient1,gradient2,gradient3,gradient4]')
];
$this->postValidation->set_rules($rules);
if($this->postValidation->run() == TRUE){
$data = $this->input->post(['title', 'gate_time', 'gate_count', 'theme', 'icon', 'login_icon', 'theme_grad']);
$this->config->set_db_items($data);
//redirect('/data/config');
$this->data['error'] = '<div class="alert alert-success" role="alert">Успешно сохранено!</div>';
$this->data['content'] = 'panel/config';
$this->load->view('index', $this->data);
}else{
$this->data['error'] = $this->postValidation->error_string('<div class="alert alert-danger" role="alert">', '</div>');
$this->data['content'] = 'panel/config';
$this->load->view('index', $this->data);
}

}

}