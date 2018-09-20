<?
if(!$this->session['user_id']){
 $this->session->destroy();
 redirect('/login');
}
$user = $this->db->get('users', '*', ['id' => $this->session['user_id']]);

if($this->session['user_password'] != $user['password'] or $this->session['user_login'] != $user['login']){
 $this->session->destroy();
 redirect('/login');
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <title><? echo $this->config->item('title') ? $this->config->item('title'):'Название'; ?> - админ панель</title>
  <link rel="shortcut icon" href="<? echo $this->config->item('icon'); ?>">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- style -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="https://unpkg.com/ionicons@4.3.0/dist/css/ionicons.min.css" rel="stylesheet">
  <link href="/upload/css/adminlte.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/skins/_all-skins.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/upload/css/chartist.min.css" />
  <link rel="stylesheet" href="/upload/css/chartist-init.css" />
  <link rel="stylesheet" href="/upload/css/chartist-tooltip.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/css/dataTables.bootstrap.min.css" />
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" /> -->
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.7/css/select.dataTables.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />
  <link href="/upload/css/main.css" rel="stylesheet">
  <link href="/upload/css/gradient.css" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>


.<? echo $this->config->item('theme'); ?> .main-header .navbar .navbar-nav>li>a {
    border-right: 0px solid #eee;
}
.<? echo $this->config->item('theme'); ?> .main-header .navbar .navbar-custom-menu .navbar-nav>li>a, .<? echo $this->config->item('theme'); ?> .main-header .navbar .navbar-right>li>a {
    border-left: 0px solid #eee;
    border-right-width: 0;
}
.<? echo $this->config->item('theme'); ?> .main-header .navbar-brand {
    color: #333;
    border-right: 0px solid #eee;
}
.bg-aqua, .callout.callout-info, .alert-info, .label-info, .modal-info .modal-body {
    background-color: #009efb !important;
}
.control-sidebar {
    padding-top: 45px;
}
.control-sidebar-light, .control-sidebar-light + .control-sidebar-bg {
    background: #f9fafc;
    border-left: none;
}
ul.control-sidebar-menu li {
padding: 5px;
}
.control-sidebar-light .control-sidebar-menu {
    margin-left: -15px;
}
.table td, .bootstrap-table .table td, .fixed-table-body .table td {
    height: auto;
}
@media screen and (min-width: 0px) and (max-width: 599px){
#hidden_tb{
  display:none;
}
}

#loading {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url('/upload/img/ajax-loader.gif') 
                50% 50% 
                no-repeat;
}

.dropdown-toggle .caret {
    display: inline-block;
}
.control-sidebar-menu{
    height: auto;
    overflow: auto;
}
.form-control:disabled {
    background-color: #ffffff00;
    border: none;
}
.form-control[disabled], fieldset[disabled] .form-control {
    cursor: auto;
}
#datatablet_filter {
display: none;
}
.info-box-text {
    font-size: 12px;
    text-transform: uppercase;
}


.btn-edition {
    position: relative;
    display: inline-flex;
    vertical-align: middle;
}
  </style>


</head>
<body class="<? echo $this->config->item('theme'); ?> layout-top-nav">
  <div class="wrapper">
    <header class="main-header">
   <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" style="float: left;">
            <i class="fa fa-bars"></i> Меню
          </button>
        </div>

        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="/">Главная</a></li>
            
            <? if($this->session['user_id'] == 1){ ?>
            <li class="dropdown">
              <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Папки <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="/logs">Обычная</a></li>
                <? $category = $this->db->select('category', '*'); ?>
                <? foreach ($category as $cat) { ?>
                  <li><a href="/logs/<? echo $cat['id']; ?>"><? echo $cat['name']; ?></a></li>
                <? } ?>
              </ul>
            </li>
            <? }else{ ?>
              <li><a href="/logs">Логи</a></li>
            <? } ?>

          <? if($this->session['user_id'] == 1): ?>
            <li><a href="/users">Пользователи</a></li>
            <li><a href="/data/config">Настройки</a></li>
          <? endif; ?>
           <!--  <li><a href="javascript:void(0)">Пример #2</a></li> -->

          </ul>
        </div>

      <div class="navbar-custom-menu" style="right: 0px;">
        <ul class="nav navbar-nav">
          <li><a data-toggle="control-sidebar" href="#"><? echo $user['login']; ?> <i class="fa fa-gear"></i></a></li>
           <li><a onclick="return confirm('Вы уверены что хотите выйти?')" href="/logout"><i class="fa fa-sign-out"></i> Выйти</a></li>
        </ul>
      </div>
      </div>
    </nav>


    </header>



    <div class="content-wrapper" style="height: auto;overflow:hidden;">
      <div class="container">
      <section class="content">
      <? $this->load->view($content); ?>

      </section>
      </div>
    </div>

    <aside class="control-sidebar control-sidebar-light">
      <div class="tab-content">
        <ul class="control-sidebar-menu">
     <? if($this->session['user_id'] == 1){ ?>
          <li class="header">Требуют пароль</li>
          <li><input type="password" id="PasswordHasher" class="form-control" placeholder="Пароль"></li>
          <li><button class="btn btn-flat btn-sm btn-danger-outline btn-block" id="deleteAllb">Удалить все логи</button></li>
          <li><button class="btn btn-flat btn-sm btn-danger-outline btn-block" id="deleteAllLog">Удалить логи ошибок</button></li>

          <li class="header">Добавить папку</li>
          <li><input type="text" id="catNameHasher" class="form-control" placeholder="Название"></li>
          <li><button class="btn btn-flat btn-sm btn-success-outline btn-block" id="addCat">Добавить</button></li>
     <? } ?>
          <li class="header">Сменить пароль</li>
          <li><input type="password" id="changePass1Hasher" class="form-control" placeholder="Текущий пароль"></li>
          <li><input type="password" id="changePass2Hasher" class="form-control" placeholder="Новый пароль"></li>
          <li><button class="btn btn-flat btn-sm btn-success-outline btn-block" id="changePassword">Сменить</button></li>

          <li class="header">Сменить Логин</li>
          <li><input type="text" id="changeNameHasher" class="form-control" value="<? echo $this->session['user_login']; ?>" placeholder="Логин"></li>
          <li><input type="password" id="changePassHasher" class="form-control" placeholder="Пароль"></li>
          <li><button class="btn btn-flat btn-sm btn-success-outline btn-block" id="changeLogin">Сменить</button></li>

        </ul>
      </div>
    </aside>
    <div class="control-sidebar-bg"></div>
  </div>

<div id="loading"></div> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script> 
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/js/adminlte.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.11.0/chartist.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
  <script src="/upload/js/chartist.tooltip.min.js"></script>
  <script src="/app.js"></script>
</body>
</html>