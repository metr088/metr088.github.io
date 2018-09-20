<?

$all = $this->db->select('history', [
	'timeout'
], [
	'ORDER' => [
		'timeout' => 'DESC'
	 ]
   ]);

foreach ($all as $row) {
$onedate[date("d.m.Y", $row['timeout'])] = 0;
}
foreach ($all as $row) {
$onedate[date("d.m.Y", $row['timeout'])] += 1;
}

$data = $this->input->req(['day', 'date']);
$interval = intval($data['day']);
$interval = $interval ? $interval:5;
$interval = in_array($interval, [5,10,20,30,60]) ? $interval:5;

$one = $this->db->get('history', ['timeout'], ['ORDER' => ['timeout' => 'ASC']]);
$from = date("d.m.Y", $one['timeout']);
$to   = date("d.m.Y", strtotime('+'. $interval .' days', time()));


$date1 = preg_match("/([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4})/", $data['date'][0]);
$date2 = preg_match("/([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4})/", $data['date'][1]);

$from = ($data['date'][0] and $date1) ? new DateTime($data['date'][0]) : new DateTime($from);
$to = ($data['date'][1] and $date2) ? new DateTime($data['date'][1]) : new DateTime($to);


$period = new DatePeriod($from, new DateInterval('P1D'), $to);

$arrayOfDates = array_map(
    function($item){return $item->format('d.m.Y');},
    iterator_to_array($period)
);
$dateCount = null;
$x = 0;
$interval = ($data['date'][0] and $data['date'][1]) ? 0:$interval;
foreach ($arrayOfDates as $key => $row) {
	if(($x <= $interval or $interval == 0)){
	$findate = isset($onedate[$row])? $onedate[$row]:0;
  $dateCount .= "{meta: '$row', value: $findate},\n";
	}else{
	break; 
	}
	$x++;
}
$dateCount = substr($dateCount,0,-2);

?>
<div class="row">
<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="ion ion-ios-infinite"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Сколько было логов</span>
              <span class="info-box-number"><? echo $this->db->count('history'); ?></span>
            </div>
          </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="ion ion-ios-infinite"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Сколько логов осталось</span>
              <span class="info-box-number"><? echo $this->db->count('manager'); ?></span>
            </div>
          </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="ion ion-ios-infinite"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Отмечаные</span>
              <span class="info-box-number">Да: <? echo $this->db->count('manager', ['view' => 1]); ?></span>
              <span class="info-box-number">Нет: <? echo $this->db->count('manager', ['view' => 0]); ?></span>
            </div>
          </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="ion ion-ios-infinite"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Пользователей всего</span>
              <span class="info-box-number"><? echo $this->db->count('users', ['id[!]' => 1]); ?></span>
            </div>
          </div>
</div>
</div>


<div class="box box-solid flat">
    <div class="box-header with-border">
        <h3 class="box-title">График Логов</h3>
        <div class="btn-group" style="float: right;">
<a class="btn btn-xs btn-flat btn-<? echo ($data['date'][0] and $data['date'][1]) ? 'success':'danger'; ?>" href="javascript:void(0)" data-toggle="modal" data-target="#pereut">Свой период</a>
        </div>
    </div>
    <div class="box-body" style="padding: 0px;">
        <div class="user-analytics chartist-chart" style="height: 350px;"></div>
    </div>
    <div class="box-footer">
        <div class="btn-group" style="float: right;">
        <a class="btn btn-xs btn-flat btn-<? echo ((($data['day'] == 5) or (!$data['day']) or ($data['day'] > 60)) and  !$data['date'][0] and !$data['date'][1]) ? 'success':'primary'; ?>" href="/">5 дней</a>
        <a class="btn btn-xs btn-flat btn-<? echo ($data['day'] == 10 and !$data['date'][0] and !$data['date'][1]) ? 'success':'primary'; ?>" href="/?day=10">10 дней</a>
        <a class="btn btn-xs btn-flat btn-<? echo ($data['day'] == 20 and !$data['date'][0] and !$data['date'][1]) ? 'success':'primary'; ?>" href="/?day=20">20 дней</a>
        <a class="btn btn-xs btn-flat btn-<? echo ($data['day'] == 30 and !$data['date'][0] and !$data['date'][1]) ? 'success':'primary'; ?>" href="/?day=30">30 дней</a>
        <a class="btn btn-xs btn-flat btn-<? echo ($data['day'] == 60 and !$data['date'][0] and !$data['date'][1]) ? 'success':'primary'; ?>" href="/?day=60">60 дней</a>
        </div>
    </div>
</div>

<?
$info = array('p1' => 0, 'p2' => 0, 'p3' => 0, 'p4' => 0, 'p5' => 0, 'p6' => 0, 'p7' => 0);
$inform = $this->db->select('manager', ['result']);
foreach ($inform as $row) {
$jsonUP = $this->input->toJson($row['result']);
$info['p1'] += $jsonUP['p1'];
$info['p2'] += $jsonUP['p2'];
$info['p3'] += $jsonUP['p3'];
$info['p4'] += $jsonUP['p4'];
$info['p5'] += $jsonUP['p5'];
$info['p6'] += $jsonUP['p6'];
$info['p7'] += $jsonUP['p7'];
}

//var_dump($info);

?>

<div class="row">

<div class="col-md-4 col-sm-12 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="fa fa-steam"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Всего Steam</span>
              <span class="info-box-number"><? echo $info['p5']; ?></span>
            </div>
          </div>
</div>


<div class="col-md-4 col-sm-12 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="ion ion-ios-wallet"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Всего Wallets</span>
              <span class="info-box-number"><? echo $info['p6']; ?></span>
            </div>
          </div>
</div>


<div class="col-md-4 col-sm-12 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="fa fa-telegram"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Всего Telegram</span>
              <span class="info-box-number"><? echo $info['p7']; ?></span>
            </div>
          </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="ion ion-ios-infinite"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Всего паролей</span>
              <span class="info-box-number"><? echo $info['p1']; ?></span>
            </div>
          </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="ion ion-ios-infinite"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Всего куки</span>
              <span class="info-box-number"><? echo $info['p2']; ?></span>
            </div>
          </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="ion ion-ios-infinite"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Всего CC</span>
              <span class="info-box-number"><? echo $info['p3']; ?></span>
            </div>
          </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua <? echo $this->config->item('theme_grad'); ?>"><i class="ion ion-ios-infinite"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Всего Forms</span>
              <span class="info-box-number"><? echo $info['p4']; ?></span>
            </div>
          </div>
</div>

</div>



<div class="box box-solid flat">
    <div class="box-header with-border">
       <h3 class="box-title">Логи ошибок</h3>
    </div>
<div class="box-body" style="padding: 0px;">
<table class="table table-striped table-xs">
<thead>
<tr>
  <th style="font-size: 12px;width: 1px;">Дата</th>
  <th style="font-size: 12px;">Сообщение</th>
</tr>
</thead>
  <tbody id="defineLog">
<? $logs = $this->db->select('logs', '*', ['ORDER' => ['id' => 'DESC'], 'LIMIT' => 20]); ?>
<? $logs_count = $this->db->count('logs'); ?>
<? if($logs_count > 0): foreach($logs as $log){ ?>
<tr>
<td><? echo date('d.m.Y', $log['timeout']); ?></td>
<td><p style="word-break: break-all;"><? echo $log['text']; ?></p></td>
</tr>
<? } else: ?>
<tr>
<td colspan="2"><center>Логи отсутствуют</center></td>
</tr>
<? endif; ?>
</tbody>
</table>
</div>
</div>
        <div data-backdrop="false" class="modal fade" id="pereut">
          <div class="modal-dialog ">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Период</h4>
              </div>
              <form action="/" method="POST">
              <div class="modal-body">
              	     <small>От</small>
                     <input type="text" class="form-control" id="dateP1" name="date[0]" value="<? echo $data['date'][0]; ?>" style="width: 100%;" AUTOCOMPLETE="off">
                     <br>
                     <small>До</small>
                     <input type="text" class="form-control" id="dateP2" name="date[1]" value="<? echo $data['date'][1]; ?>" style="width: 100%;" AUTOCOMPLETE="off">


              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-flat btn-sm btn-primary" style="float: right;">Просмотреть</button>
              </div>
             </form>
            </div>
          </div>
        </div>

<script type="text/javascript">
$(document).ready( function() {
var defaultOptions = {
  currency: 'Логов: '
};
new Chartist.Line('.user-analytics', {
series: [
[
<? echo $dateCount; ?>

]
      ]
}, {
showArea: false,
lineSmooth: Chartist.Interpolation.simple({
  divisor: 3
}),
fullWidth: true,
chartPadding: {
  top: 20
},
plugins: [
  Chartist.plugins.tooltip(defaultOptions)
]
});


  $('#dateP1').datetimepicker({
 timepicker:false,
 format:'d.m.Y'
   });
  $('#dateP2').datetimepicker({
 timepicker:false,
 format:'d.m.Y'
   });

});
</script>