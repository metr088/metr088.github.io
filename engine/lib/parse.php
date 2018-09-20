<?
class parse { 

    public  $dir = '.'; 
    public  $template = null; 
    public  $copy_template = null; 
    public  $data = array(); 
    public  $block_data = array(); 
    public  $result = array('info' => '', 'content' => ''); 
    public  $template_parse_time = 0; 

    public function set($name , $var) { 
        if (is_array($var) && count($var)) { 
            foreach ($var as $key => $key_var) { 
                $this->set($key , $key_var); 
            } } else $this->data[$name] = $var; 
    } 
    public function block($name , $var) { 
        if (is_array($var) && count($var)) { 
            foreach ($var as $key => $key_var) { 
                $this->block($key , $key_var); 
            } } else $this->block_data[$name] = $var; 
    } 

    public function loadText($tpl_name) { 
    $time_before = $this->get_real_time(); 
       $this->template = $tpl_name;
        $this->copy_template = $this->template; 
    $this->template_parse_time += $this->get_real_time() - $time_before; 
    return true; 
    }

    public function loadFile($tplsetting, $design = null) {
        $time_before = $this->get_real_time();
        if($design !== null){
        $tpl = DIR.'/view/template/'.$design.'/'.$tplsetting;
        }else{
        $tpl = DIR.'/view/template/'.$tplsetting;
        }
        $exites = pathinfo($tpl, PATHINFO_EXTENSION);
        $tpl_name = ($exites == '') ? $tpl.'.tpl' : $tpl;
        if ($tpl_name == '' || !file_exists($tpl_name)) { show_error("Невозможно загрузить шаблон: ". $tpl_name); return false;} 
        $template = file_get_contents($tpl_name);
        $this->template = $template;
        $this->copy_template = $this->template;
        $this->template_parse_time += $this->get_real_time() - $time_before;
        return true;
    } 

    public function _clear() { 
    $this->data = array(); 
    $this->block_data = array(); 
    $this->copy_template = $this->template; 
    } 

    public function clear() { 
    $this->data = array(); 
    $this->block_data = array(); 
    $this->copy_template = null; 
    $this->template = null; 
    } 

    public function global_clear() { 
    $this->data = array(); 
    $this->block_data = array(); 
    $this->result = array(); 
    $this->copy_template = null; 
    $this->template = null; 
    } 

    public function compile($tpl) {
    $time_before = $this->get_real_time();
    $find = array();
    $replace = array();
    $find_preg = array();
    $replace_preg = array();
    foreach ($this->data as $key_find => $key_replace) { 
                $find[] = $key_find; 
                $replace[] = $key_replace; 
            } 
    $result = str_replace($find, $replace, $this->copy_template); 
    if (count($this->block_data)) { 
        foreach ($this->block_data as $key_find => $key_replace) { 
                $find_preg[] = $key_find; 
                $replace_preg[] = $key_replace; 
                } 
    $result = preg_replace($find_preg, $replace_preg, $result); 
    } 

    if (isset($this->result[$tpl])) $this->result[$tpl] .= $result; else $this->result[$tpl] = $result;

    $this->_clear(); 
    $this->template_parse_time += $this->get_real_time() - $time_before; 
    }

    public function result($name){
    echo $this->result[$name];
    }

    public function get_real_time() 
    { 
        list($seconds, $microSeconds) = explode(' ', microtime()); 
        return ((float)$seconds + (float)$microSeconds); 
    } 
} 

?>