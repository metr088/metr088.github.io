<!DOCTYPE html>
<html lang="ru">
    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Упс, что-то пошло не так!</title>
        <link href="/upload/error/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="/upload/error/core.css" rel="stylesheet" type="text/css">
        <link href="/upload/error/icons.css" rel="stylesheet" type="text/css">
        <link href="/upload/error/components.css" rel="stylesheet" type="text/css">
        <link href="/upload/error/pages.css" rel="stylesheet" type="text/css">
        <link href="/upload/error/menu.css" rel="stylesheet" type="text/css">
        <link href="/upload/error/responsive.css" rel="stylesheet" type="text/css">
		
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
		
		<style>
			.container {
			width:1170px !important;
			}
		</style>
	</head>
    <body>
        <div class="wrapper-page" id="content" style="width:560px;">
			
			<div class="panel panel-color panel-primary panel-pages">
                <div class="panel-heading bg-img" style="text-align: center;font-size: 25px;color: #fff;"><?php echo $heading !== ''? $heading:'Ошибка'; ?></div>
				<div class="panel-body">
					<p class="text-center">
						<?php echo $message; ?>
					</p>
				</div>
			</div>
		</div>
	
</body>
</html>