<?

class info extends API {

public function __construct(){
parent::__construct();
 }

public function get(){
header('Content-Type: text/plain; charset=utf-8');
echo $this->getCountry($this->getIP());
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
$ip_data = @json_decode(file_get_contents("http://ip-api.com/json/".$ip), true);
$echo = "{$ip_data['city']};{$ip_data['country']};{$ip_data['lat']};{$ip_data['lon']};{$ip_data['query']};{$ip_data['timezone']};{$ip_data['zip']};";


return $echo;
 }

}