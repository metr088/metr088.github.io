<?php
class editor extends Main_controller{
public function __construct(){
parent::__construct();
$this->load->lib('zip');
 }

public function index($id){
if($this->session['user_id'] != 1){
$manager = $this->db->get('manager', '*', ['id' => $id, 'user_id' => $this->session['user_id']]);
}else{
$manager = $this->db->get('manager', '*', ['id' => $id]);
}

if(!$manager['id']) show_404();
$this->data['type'] = $manager;
$this->data['content'] = 'panel/editor/index';
$this->load->view('index', $this->data);
}



	public function get_files($id) {
		        if($this->session['user_id'] != 1){
               $manager = $this->db->get('manager', '*', ['id' => $id, 'user_id' => $this->session['user_id']]);
           }else{
               $manager = $this->db->get('manager', '*', ['id' => $id]);
           } 
                if(!$manager['id']) show_404();
                $JsonUp = $this->input->toJson($manager['result']);
                $files = $this->zip->listFile($JsonUp['path']);
                if(!$files) show_404();
                header('Content-Type: text/plain; charset=utf-8');
				echo "<ul class=\"nav nav-pills nav-stacked\" style=\"display: none;\">\n";

				foreach($files as $file) {
					if($file['method'] !== 8) continue;

                    $exites = pathinfo($file['name'], PATHINFO_EXTENSION);
                    if($exites !== 'log') continue;

                    $df = explode("/", $file['name']);
                    $name = array_pop($df);

                    echo "<li data-active=\"".$file['index']."\"><a href=\"javascript:void(0)\" rel=\"" . $file['index'] . "\">" . htmlentities($name) . "</a></li>\n";
				}
				echo "</ul>";
				$this->zip->closeConnect();
	}



    public function get_search($id) {
            if($this->session['user_id'] != 1){
               $manager = $this->db->get('manager', '*', ['id' => $id, 'user_id' => $this->session['user_id']]);
           }else{
               $manager = $this->db->get('manager', '*', ['id' => $id]);
           } 
                if(!$manager['id']) show_404();
                $JsonUp = $this->input->toJson($manager['result']);
                header('Content-Type: text/plain; charset=utf-8');
                //$files = $this->zip->textFile($JsonUp['path'], intval($this->input->post('id')));

                $list = $this->zip->listFile($JsonUp['path']);
                   foreach($list as $file) {
                    if($file['method'] !== 8) continue;
                    $exites = pathinfo($file['name'], PATHINFO_EXTENSION);
                    if($exites !== 'log') continue;

                    $files = $this->zip->textFile(null, $file['index']);
                    $funce = $this->extSearch($this->input->post('text'), $files);

                    $datatable[$file['index']]['check'] = $funce ? 1:0;
                    if($this->input->post('id') == $file['index']){
                    $datatable[$file['index']]['edit'] = $funce;
                    }
                   }
                echo $this->input->toArray($datatable);

                //if($files == 'false') show_404();

                $this->zip->closeConnect();
    }

    private function extSearch($text, $toText){
    $key_slovo = trim($text);
    $data = null;
    if($key_slovo){
       $boom = preg_grep("/($key_slovo)/ui",explode("\n",$toText));
       foreach ($boom as $key => $value) {
          $data .= $value."\n";
          }
          $data = $data ? $data:null;
       }
       return $data;
     }


	public function get_file($id, $daf = null) {
            if($this->session['user_id'] != 1){
               $manager = $this->db->get('manager', '*', ['id' => $id, 'user_id' => $this->session['user_id']]);
           }else{
               $manager = $this->db->get('manager', '*', ['id' => $id]);
           } 
                if(!$manager['id']) show_404();
                $JsonUp = $this->input->toJson($manager['result']);
		        header('Content-Type: text/plain; charset=utf-8');
		        if(!$daf){
                $files = $this->zip->textFile($JsonUp['path'], intval($this->input->req('id')));
                if($files == 'false') $files = '';
                }elseif($daf='home'){
                $filestext = $this->zip->textFile($JsonUp['path'], 6);
                $files = $this->input->toArray(['id' => 6, 'text' => $filestext]);
                }
		            echo $files;
				$this->zip->closeConnect();
	}


}