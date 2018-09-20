<style>
.table.general > thead > tr > th, .table.general > tbody > tr > th, .table.general > tfoot > tr > th, .table.general > thead > tr > td, .table.general > tbody > tr > td, .table.general > tfoot > tr > td {
    border-top: 0;
}
</style>

<div class="box box-solid flat">
    <div class="box-header with-border">
       <h3 class="box-title">Настройки</h3>
    </div>

    <div class="box-body">
<form method="post" accept-charset="utf-8" enctype="multipart/form-data">
<? echo $error; ?>
<table class="table general">
	<tbody>
	<tr>
		<td style="font-size: 13px;">Название сайта:</td>
		<td><input type="text" name="title" value="<? echo $this->config->item('title'); ?>" class="form-control"></td>
	</tr>
	<tr>
		<td style="font-size: 13px;">Иконка сайта:</td>
		<td>
			<input type="text" name="icon" value="<? echo $this->config->item('icon'); ?>" class="form-control">
			<p style="font-size: 10px;word-break: break-all;">Так же работает data:image/png;base64</p>
		</td>
	</tr>
	<tr>
		<td style="font-size: 13px;">Иконка авторизации:</td>
		<td>
			<input type="text" name="login_icon" value="<? echo $this->config->item('login_icon'); ?>" class="form-control">
			<p style="font-size: 10px;word-break: break-all;">Так же работает data:image/png;base64</p>
		</td>
	</tr>
	<tr>
		<td style="font-size: 13px;">Время запроса (Минуты):</td>
		<td>
			<input type="number" name="gate_time" value="<? echo $this->config->item('gate_time'); ?>" class="form-control">
			<p style="font-size: 10px;word-break: break-all;">Через сколько можно отправить запрос повторно с одно IP поставте 0 если хотите отключить</p>
		</td>
	</tr>
	<tr>
		<td style="font-size: 13px;">Кол-во заросов:</td>
		<td>
			<input type="number" name="gate_count" value="<? echo $this->config->item('gate_count'); ?>" class="form-control">
			<p style="font-size: 10px;word-break: break-all;">Ограничение на кол-во запросов с одного IP поставте 0 если хотите отключить</p>
		</td>
	</tr>
	<tr>
		<td style="font-size: 13px;">Темы:</td>
		<td>
			<?
			$theme = array('skin-black' => 'Белый', 'skin-blue' => 'Синий', 'skin-yellow' => 'жёлтый', 'skin-green' => 'Зелёный', 'skin-purple' => 'Пурпурный', 'skin-red' => 'Красный');
			?>
			<? echo form_dropdown('theme', $theme, $this->config->item('theme'), 'class="form-control"'); ?>
		</td>
	</tr>
	<tr>
		<td style="font-size: 13px;">Дополнения к темам:</td>
		<td>
			<?
			$theme_grad = array('nogradient' => 'Без дополнений', 'gradient1' => 'Персик', 'gradient3' => 'Синий', 'gradient2' => 'Пурпур', 'gradient4' => 'Аква');
			?>
			<? echo form_dropdown('theme_grad', $theme_grad, $this->config->item('theme_grad'), 'class="form-control"'); ?>
		</td>
	</tr>

	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Сохранить" class="btn btn-flat btn-success-outline pull-right btn-block"></td>
	</tr>
</tbody>
</table>
</form>
</div>
</div>