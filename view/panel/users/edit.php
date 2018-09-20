<div class="box box-solid flat">
    <div class="box-header with-border">
       <h3 class="box-title">
     <? echo $users['id'] ? 'Изменить пользователя: '.$users['login']:'Добавить пользователя'; ?></h3>
    </div>
<div class="box-body">
<? if($error_post): ?><?=$error_post;?> <? endif; ?>
<form method="POST">
<b>Логин:</b><br>
<input type="text" name="login" class="form-control" value="<? echo $users['login']; ?>" />
<br>
<b>Пароль:</b><br>
<input type="password" name="password" class="form-control" />
<br>
<input type="submit" name="submit" class="btn btn-flat btn-success-outline" value="Сохранить" />
</form>
</div>
</div>