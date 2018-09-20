<?
class gate extends API{
public function __construct(){
parent::__construct();
 }
public function get(){
header('Content-Type: text/html; charset=utf-8');
$rules = [
'p1' => array('field' => 'p1', 'label' => 'p1', 'rules' => 'trim|intval|xss_clean'),
'p2' => array('field' => 'p2', 'label' => 'p2', 'rules' => 'trim|intval|xss_clean'),
'p3' => array('field' => 'p3', 'label' => 'p3', 'rules' => 'trim|intval|xss_clean'),
'p4' => array('field' => 'p4', 'label' => 'p4', 'rules' => 'trim|intval|xss_clean'),
'p5' => array('field' => 'p5', 'label' => 'p5', 'rules' => 'trim|intval|xss_clean'),
'p6' => array('field' => 'p6', 'label' => 'p6', 'rules' => 'trim|intval|xss_clean'),
'p7' => array('field' => 'p7', 'label' => 'p7', 'rules' => 'trim|intval|xss_clean')
];
$this->getValidation->set_rules($rules);
if($this->getValidation->run() == TRUE){
$ip = $this->getIP();
$data = $this->input->get(['p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7']);

$info = $this->db->get('manager', "*", ['ip' => $ip, 'ORDER' => ['id' => 'DESC']]);
$tamp = $info['id'] ? $this->input->toJson($info['result']):null;
//var_dump($this->input->toJson($info['result'])); exit;
if($tamp){

$count = $this->db->count('manager', ['ip' => $ip]);
$gate_time = $this->config->item('gate_time') ? $this->config->item('gate_time'):0;
$gate_count = $this->config->item('gate_count') ? $this->config->item('gate_count'):0;
$time = $gate_time * 60;
if(($tamp['time'] + $time) >= time()){
exit(false);
}
if($count > $gate_count and $gate_count != 0){
exit(false);
}
}


$uploaddir = './upload/files/';
if (is_uploaded_file($_FILES['file']['tmp_name'])){
    $exites = pathinfo($uploaddir . basename($_FILES['file']['name']), PATHINFO_EXTENSION);
    if($exites !== 'zip') exit($this->log_message('Формат файла не zip!'));

    $basenamer = $this->translit($_FILES['file']['name']);
    $newbasename = basename($basenamer[1]);
    $filebasename = basename($basenamer[0]);
    $uploadfile = $uploaddir . $newbasename;
    $pather = 'upload/files/'. $newbasename;

    if (file_exists($uploadfile)) exit($this->log_message('Данный файл уже существует!'));

	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)){

   $jsonUp['path'] = $pather;
   $jsonUp['country'] = $this->getCountry($ip);
   $jsonUp['time'] = time();
   $jsonUp = array_merge($jsonUp, $data);
   $result = array(
         'name' => $filebasename,
         'ip' => $ip,
         'result' => $this->input->toArray($jsonUp)
    );
    $this->db->insert('manager', $result);
    $this->db->insert('history', ['timeout' => $jsonUp['time']]);
	exit(true);
}else{ exit($this->log_message('Не удалось загрузить файл поставьте 777 для папки "/upload/files"')); }}else{ exit(false); } }else{ exit(false); }

}


private function getIP() {
$client  = @$_SERVER['HTTP_CLIENT_IP'];
$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
$remote  = @$_SERVER['REMOTE_ADDR'];
 
if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
else $ip = $remote;
 
return $ip;
}

 private function getCountry($ip){
$region = 'unknown (UN)';
$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
if($ip_data && $ip_data->geoplugin_countryName != null){
    $region = $ip_data->geoplugin_countryName.' ('.$ip_data->geoplugin_countryCode.')';
}
return $region;
 }

private function log_message($message = null){
if(!$message) return false;
$this->db->insert('logs', ['text' => $message, 'timeout' => time()]);
return true;
}

private function translit($s){
  $s = str_replace(".zip", '', $s);
  $s = (string) $s; // преобразуем в строковое значение
  $s = strip_tags($s); // убираем HTML-теги
  $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
  $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
  $s = trim($s); // убираем пробелы в начале и конце строки
  $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
  $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
  $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
  $s = str_replace(" ", "", $s); // заменяем пробелы знаком минус
  $s = str_replace("_", "", $s); // заменяем пробелы знаком минус
  $s1 = $s.'.zip';
  return [$s, $s1]; // возвращаем результат
}

}

?>