
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><? echo $this->config->item('title') ? $this->config->item('title'):'Название'; ?> - Вход</title>
    <link rel="shortcut icon" href="<? echo $this->config->item('icon'); ?>">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
    <link href="/upload/css/adminlte.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/skins/_all-skins.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/upload/css/login.main.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/upload/css/main.css">
</head>
<body>

    <div class="page-center btn-float" style="display: none;">
        <div class="page-center-in">
            <div class="container-fluid">
                <form method="POST" class="sign-box">
                 <div class="sign-avatar">
                        <img src="<? echo $this->config->item('login_icon') ? $this->config->item('login_icon'):"/upload/img/avatar-sign.png"; ?>">
                    </div>
                    <header class="sign-title">Авторизация</header>
<? if($error): ?><div class="alert alert-danger" role="alert"><?=$error;?></div><? endif; ?>
<? if($error_post): ?><?=$error_post;?> <? endif; ?>
                    <div class="form-group">
                        <input type="text" class="form-control" name="login" value="<? echo $post_data['login']; ?>" placeholder="Логин"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Пароль"/>
                    </div>

                    <button type="submit" class="btn btn-primary-outline">Войти</button>
                </form>
            </div>
        </div>
    </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js"></script>
    <script>
        $(function() {
            $('.page-center').matchHeight({
                target: $('html')
            });

            $(window).resize(function(){
                setTimeout(function(){
                    $('.page-center').matchHeight({ remove: true });
                    $('.page-center').matchHeight({
                        target: $('html')
                    });
                },100);
            });
            $('.page-center').css('display', 'table');
        });
    </script>
</body>
</html>