<?php
class zip extends ZipArchive{

protected $opendata;

public function listFile($str){
$this->opendata = $this->opendata ? $this->opendata:$this->open($str);
if($this->opendata !== TRUE) return false;
$setup = array();
$count = $this->numFiles;
for ($i = 0; $i < $count; $i++){
$stat = $this->statIndex($i);
$setup[] = [
'name' => $stat['name'],
'index' => $stat['index'],
'method' => $stat['comp_method']
];
}
 return $setup;
}

public function textFile($str, $id){

$this->opendata = $this->opendata ? $this->opendata:$this->open($str);

if($this->opendata !== TRUE) return false;

//$data = iconv('windows-1251', 'utf-8', $this->getFromIndex($id));

$data = $this->getFromIndex($id);

 return str_replace("\r\n", "\n", $data);
}


public function closeConnect(){
	$this->close();
}

}