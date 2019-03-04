<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>人人编剧后台管理系统</title>

	<!-- CSS -->
<!--	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">-->
	<link rel="stylesheet" href="/public/assets/bootstrap-3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="/public/assets/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="/public/css/form-elements.css">
	<link rel="stylesheet" href="/public/css/style.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Favicon and touch icons -->
	<link rel="shortcut icon" href="/public/assets/ico/logo_48.png">
</head>

<body>
<!-- Top content -->
<div class="top-content">
	<div class="inner-bg">
		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2 text">
					<h1><strong>人人编剧后台管理系统</strong>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3 form-box">
					<div class="form-top">
						<div class="form-top-left">
							<h3>Login to system</h3>
							<p>Enter your username and password to log on:</p>
						</div>
						<div class="form-top-right">
							<i class="fa fa-key"></i>
						</div>
					</div>
					<div class="form-bottom">
						<form role="form" action="" method="post" class="login-form">
							<div class="form-group">
								<label class="sr-only" for="form-username">Username</label>
								<input type="text" name="form-username" placeholder="Username..." class="form-username form-control" id="form-username">
							</div>
							<div class="form-group">
								<label class="sr-only" for="form-password">Password</label>
								<input type="password" name="form-password" placeholder="Password..." class="form-password form-control" id="form-password">
							</div>
                            <div class="form-group col-xs-8" style="padding-left:0px;">
                                <label class="sr-only" for="form-verify_code">VerifyCode</label>
                                <span><input type="text" class="form-control" name="verify_code" placeholder="verify_code" id="form-verify_code"></span>
                            </div>
                            <div class="col-sm-4">
                                <img src="/Admin/Login/verifyCode" style="position: absolute;cursor: pointer;" onclick=this.src="/Admin/Login/verifyCode/"+Math.random() title='看不清？点击更换验证码'>
                            </div>
                            <div class="form-group col-xs-6" style="padding-left:0px;">
<!--                                <label for="form-checkbox" style="font-weight: normal;position: absolute;margin-left: 15px;margin-top: -5px;">Remember me</label>-->
<!--                                <input type="checkbox" id="form-checkbox" name="remember" style="float: left" >-->
                            </div>
                            <button type="button" class="btn" onclick="verify()">Sign in!</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Javascript -->
<script src="/public/js/jquery.js?v=1"></script>
<script src="/public/assets/bootstrap-3.3.7/js/bootstrap.min.js"></script>
<script src="/public/js/jquery.backstretch.min.js"></script>
<script src="/public/js/scripts.js?v=1"></script>
<script src="/public/assets/layer/layer.js"></script>

<!--[if lt IE 10]>
<script src="/public/js/placeholder.js"></script>
<![endif]-->

<script>
        function verify() {
            var username = $('#form-username').val();
            var password = $('#form-password').val();
            var verify_code = $('#form-verify_code').val();
            var remeber = $('#form-checkbox').is(':checked');
            if(!username) {
                layer.msg('用户名不能为空！');
                return false;
            } else if (!password) {
                layer.msg('请输入密码');
                return false;
            } else if (!verify_code) {
                layer.msg('请输入验证码');
                return false;
            }
            $.ajax({
                url : '/Admin/Login/handleLogin',
                type : 'POST',
                data : {
                    'username' : username,
                    'password' : password,
                    'verify_code' : verify_code,
                    'remeber' : remeber
                },
                success : function (data) {
                    layer.msg(data.msg,{time:1000},function () {
                        if (data.status == 1) {
                            window.location.href = data.url;
                        } else {
                            window.location.reload();
                        }
                    });
                }
            });
        }
</script>
</body>
</html>