<? $logs = $this->db->select('manager', '*', ['user_id' => $this->session['user_id'], 'ORDER' => ['id' => 'DESC']]); ?>
<div class="box box-solid flat">
    <div class="box-header with-border">
       <h3 class="box-title">Логи</h3>
    </div>
<div class="box-body" style="padding: 0px;">
<input type="text" class="form-control" style="float:right;" placeholder="Поиск" id="myInputTextField">
<table class="table table-striped table-xs" id="datatablet">
<thead>
<tr>
  <th style="font-size: 12px;width: 10px;">#</th>
  <th style="font-size: 12px;width: 10px;">Название</th>
  <th style="font-size: 12px;width: 10px;" id="hidden_tb">Страна</th>
  <th style="font-size: 12px;width: 10px;" id="hidden_tb">Дата</th>
  <th style="font-size: 12px;width: 10px;" data-orderable="false">Инструменты</th>
</tr>
</thead>
  <tbody id="defineAll">
<? foreach($logs as $log){ $jsonUP = $this->input->toJson($log['result']);  ?>
<tr id="define_<?=$log['id'];?>">
<td><? echo $log['id']; ?></td>
<td><p style="word-break: break-all;"><? echo $log['name']; ?> <? echo $log['view'] ? '<i class="fa fa-check" aria-hidden="true"></i>':''; ?></p></td>
<td id="hidden_tb"><? echo $jsonUP['country']; ?></td>
<td id="hidden_tb"><? echo date("d.m.Y", $jsonUP['time']); ?></td>
<td>
<button type="button" data-toggle="tooltip" title="Отметить" class="btn btn-default-outline btn-xs" onclick="slupid(<?=$log['id'];?>);"><i class="fa fa-<? echo $log['view'] ? 'check':'times'; ?>"></i></button>
<button type="button" data-id="<?=$log['id'];?>" id="infoGet" data-toggle="tooltip" title="Информация" class="btn btn-default-outline btn-xs" style="width: 20px;"><i class="fa fa-info"></i></button>
</td>
</tr>
<? } ?>
</tbody>
</table>
</div>
</div>



        <div  class="modal fade" id="InfoDataTable">
          <div class="modal-dialog ">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Информация</h4>
              </div>
              <div class="modal-body">
              </div>
            </div>
          </div>
        </div>