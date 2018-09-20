<? $logs = $this->db->select('manager', '*', ['category' => $manager_id, 'ORDER' => ['id' => 'DESC']]); $i=0; ?>
<div class="box box-solid flat">
    <div class="box-header with-border">
       <h3 class="box-title"><? echo $cat_name; ?></h3>
       <div class="btn-group pull-right">
       <button type="button" data-toggle="modal" data-target="#SettingData" class="btn btn-default-outline btn-xs">Настройки</button>
       <? if($manager_id != 0): ?>
       <button type="button" data-id="<?=$manager_id;?>" id="deleteCat" data-placement="right" data-toggle="tooltip" title="Удалить папку" class="btn btn-danger-outline btn-xs"><i class="fa fa-trash"></i></button>
      <? endif; ?>
    </div>
    </div>
<div class="box-body" style="padding: 0px;">

<input type="text" class="form-control" style="float:right;" placeholder="Поиск" id="myInputTextField"/>

<table class="table table-striped table-xs" id="datatablet" data-cat-id="<?=$manager_id;?>">
<thead>
<tr>
  <th style="font-size: 12px;width: 10px;" data-orderable="false"></th>
  <th style="font-size: 12px;width: 10px;">#</th>
  <th style="font-size: 12px;width: 10px;">Название</th>
  <th style="font-size: 12px;width: 10px;" id="hidden_tb">Владелец</th>
  <th style="font-size: 12px;width: 10px;" id="hidden_tb">Страна</th>
  <th style="font-size: 12px;width: 10px;" id="hidden_tb">Дата</th>
  <th style="font-size: 12px;width: 10px;" data-orderable="false">Инструменты</th>
</tr>
</thead>
  <tbody>
<? foreach($logs as $log){ $jsonUP = $this->input->toJson($log['result']);  ?>
<tr id="define_<?=$i;?>">
<td><input type="checkbox" data-ul="<?=$i;?>" value="0"></td>
<td><? echo $log['id']; ?></td>
<td><p style="word-break: break-all;"><? echo $log['name']; ?> <? echo $log['view'] ? '<i class="fa fa-check" aria-hidden="true"></i>':''; ?></p></td>
<td id="hidden_tb"><? echo !$log['user_id'] ? 'Нету':$this->db->get('users', ['login'], ['id' => $log['user_id']])['login']; ?></td>
<td id="hidden_tb"><? echo $jsonUP['country']; ?></td>
<td id="hidden_tb"><? echo date("d.m.Y", $jsonUP['time']); ?></td>
<td>
<button type="button" data-toggle="tooltip" title="Отметить" class="btn btn-default-outline btn-xs" onclick="slupid(<?=$log['id'];?>);"><i class="fa fa-<? echo $log['view'] ? 'check':'times'; ?>"></i></button>
<button type="button" data-id="<?=$log['id'];?>" id="infoGet" data-toggle="tooltip" title="Информация" class="btn btn-default-outline btn-xs" style="width: 20px;"><i class="fa fa-info"></i></button>
<button type="button" data-id="<?=$log['id'];?>" id="deleteFile" data-toggle="tooltip" title="Удалить" onclick="deleteFile(this);" class="btn btn-danger-outline btn-xs"><i class="fa fa-trash"></i></button>
</td>
</tr>
<? $i++; } ?>
</tbody>
</table>


</div>
</div>



        <div class="modal fade" id="InfoDataTable">
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



        <div class="modal fade" id="SettingData">
          <div class="modal-dialog ">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Настройки</h4>
              </div>
              <div class="modal-body">

             <table style="width:100%;" class="table table-striped">
                <tbody>


                    <tr>
                        <td style="width: 30%;border-top: none;">Переместить в:</td>
                        <td style="border-top: none;">
                            <select id="selectCatAll" data-id="<? echo $manager_id; ?>" class="form-control">
                                <option value="-1"></option>
                                <option value="0" <? echo $manager_id == 0 ? 'selected':''; ?>>Обычная</option>
                                <? $category = $this->db->select('category', '*'); ?>
                                <? foreach ($category as $cat) { ?>
                                <option value="<? echo $cat['id']; ?>" <? echo $manager_id == $cat['id'] ? 'selected':''; ?>><? echo $cat['name']; ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border-top: none;">Передать файл</td>
                        <td style="border-top: none;">
                            <select id="UserNewsAll" style="width: 100%;" class="form-control">
                                <option value=""></option>
                                <option value="0">Никто</option>
                                <? $category = $this->db->select('users', '*'); ?>
                                <? foreach ($category as $cat) { if($cat['id'] == 1) continue; ?>
                                <option value="<? echo $cat['id']; ?>"><? echo $cat['login']; ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>



                </tbody>
            </table>

              </div>
            </div>
          </div>
        </div>