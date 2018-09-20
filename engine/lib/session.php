<?
class session implements ArrayAccess{
    private $meta = '__UserData__';
    private $started;

    public function __construct(){
            $this->started = false;
            $this->start();
    }

    public function start(){
        $this->started || @session_start();
        $this->started = true;
    }

    public function commit(){
        session_commit();
    }

    public function destroy(){
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

public function getUser($str = NULL){
if(is_array($str)){
    $data = array();
    foreach ($str as $field) {
        $data[$field] = @$_SESSION[$field];
    }
   return $data;

  }else{
if(isset($str)){
return @$_SESSION[$str];
}else{
return @$_SESSION;
   }
  }

 }

public function setUser($str = null, $default = null){
if(is_array($str)){

    foreach ($str as $field => $key) {
    $_SESSION[$field] = $key;
    }

  }else{
    $_SESSION[$str] = $default;
  }

 }

 public function unsetUser($str = NULL){
if(is_array($str)){

    foreach ($str as $field) {
    unset($_SESSION[$field]);
    }

  }else{


if(isset($str)){
unset($_SESSION[$str]);
}else{

   $array_key = array_keys($_SESSION);
   foreach ($array_key as $field) {
    unset($_SESSION[$field]);
   }

   }


  }

 }


public function csrf($key = null){
  $key = isset($key) ? $key:$this->csrfToken();
    $csg = getallheaders();
    $csrf['TOKEN'] = @$csg['X-CSRF-TOKEN'];
     if($key !== $csrf['TOKEN']){
      show_error('Доступ закрыт!', 403, '');
      exit();     
     }
 }

public function csrfToken($key = null, $restart = false){
if (!isset($_SESSION['csrfToken']) or $restart == true) {
    $_SESSION['csrfToken'] = md5(genRandomString().$key);
}

return $_SESSION['csrfToken'];
 }

 private function sha512($str = null){
 return hash('sha512', $str);
 }

    public function getName(){
        return session_name();
    }

    private function init(){

        return true;
    }

    public function offsetExists($offset){
        return isset($_SESSION[$offset]);
    }

    public function offsetGet($offset){
        return $this->getUser($offset);
    }

    public function offsetSet($offset, $value){
        $_SESSION[$offset] = $value;
    }

    public function offsetUnset($offset){
        unset($_SESSION[$offset]);
    }
}
?>