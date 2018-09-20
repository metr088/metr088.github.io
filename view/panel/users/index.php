<div class="box box-solid flat">
    <div class="box-header with-border">
       <h3 class="box-title">Пользователи</h3>
       <a href="/users/edit" data-toggle="tooltip" data-placement="left" title="Добавить" class="btn btn-default-outline btn-xs pull-right"><i class="fa fa-edit"></i></a>
    </div>
<div class="box-body" style="padding: 0px;">
<table class="table table-striped table-xs">
<thead>
<tr>
  <th style="font-size: 12px;width: 10px;">#</th>
  <th style="font-size: 12px;width: 10px;">Логин</th>
  <th style="font-size: 12px;width: 10px;" data-orderable="false">Инструменты</th>
</tr>
</thead>
  <tbody>
<? foreach($users as $log){ ?>
<tr>
<td><? echo $log['id']; ?></td>
<td><p style="word-break: break-all;"><? echo $log['login']; ?></p></td>
<td>
<a href="/users/edit/<? echo $log['id']; ?>" data-toggle="tooltip" title="Изменить" class="btn btn-default-outline btn-xs"><i class="fa fa-edit"></i></a>
<a onclick="return confirm('Вы уверены что хотите удалить? \nВместе с Пользователем удалятся и его логи!')" href="/users/delete/<? echo $log['id']; ?>" data-toggle="tooltip" title="Удалить" class="btn btn-danger-outline btn-xs"><i class="fa fa-trash"></i></a>
</td>
</tr>
<? } ?>
</tbody>
</table>
</div>
</div>