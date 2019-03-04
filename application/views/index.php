<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>97Admin Power by Symphp</title>
    <!-- CSS -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="/public/assets/bootstrap-3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/assets/build/css/custom.min.css">
    <link rel="stylesheet" href="/public/css/form-elements.css">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/assets/fancybox/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/public/css/gallery.css">
    <link rel="stylesheet" href="/public/css/jquery.fancybox.css">
    <!-- JS -->
    <script src="/public/assets/jquery/dist/jquery.min.js"></script>
<!--    <script src="/public/assets/fancybox/jquery.fancybox.min.js"></script>-->
    <script src="/public/js/jquery.fancybox.js"></script>
    <script src="/public/js/modernizr.custom.js?v=1"></script>
    <style>
        .cleBorder {
            border:10px solid rgba(255,255,255,0.3);
            display: inline-block;
            border-radius:30px;
        }
        body {
            background: white;
        }
    </style>
</head>
<body>
    <div>
        <section class="wrapper m-bot-none m-t-0 p-0">
            <!-- BEGIN ROW  -->
            <div class="row">
                <div class="profile-nav">
                    <section class="panel" style="border: 0px;">
                        <div class="user-heading round" style="background: #207a94;color: white;">
                            <a href="/Admin/Login" class="cleBorder" target="_blank">
                                <img src="/public/img/logo_150.png" alt="97Admin">
                            </a>
                            <h1 style="color: white"><strong>欢迎使用97Admin后台管理系统</strong></h1>
                            <b>安装说明</b>
                            <p class="text-center">
                                1.97admin.sql文件导入到数据库中。
                                <br> 2.修改文件App/config/database.php。
                                <br> 3.填写正确的数据库配置信息。
                                <br> 4.帐号：admin，密码：admin888。
                            </p>
                            <h4>后台地址：<a href="/Admin/Login" class="cleBorder" style="padding: 5px;">进入97Admin后台管理系统</a></h4>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel text-left"  style="border-radius:5px;">
            <div class="x_title">
                <h4><span style="color: black">展示相册</span></h4>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="grid cs-style-3" style="margin-left: -35px;">
                    <li>
                        <figure>
                            <img src="/public/img/photo/login.png" alt="登录页">
                            <figcaption>
                                <h3>1.登录页</h3>
                                <span> 97Admin </span>
                                <a class="fancybox" data-fancybox-group="group" href="/public/img/photo/login.png">Read</a>
                            </figcaption>
                        </figure>
                    </li>
                    <li>
                        <figure>
                            <img src="/public/img/photo/01.png" alt="控制台页">
                            <figcaption>
                                <h3>2.控制台页</h3>
                                <span> 97Admin </span>
                                <a class="fancybox" data-fancybox-group="group" href="/public/img/photo/01.png">Read</a>
                            </figcaption>
                        </figure>
                    </li>
                    <li>
                        <figure>
                            <img src="/public/img/photo/02.png" alt="菜单列表">
                            <figcaption>
                                <h3>3.菜单列表</h3>
                                <span> 97Admin </span>
                                <a class="fancybox" data-fancybox-group="group" href="/public/img/photo/02.png">Read</a>
                            </figcaption>
                        </figure>
                    </li>
                    <li>
                        <figure>
                            <img src="/public/img/photo/03.png" alt="网站设置">
                            <figcaption>
                                <h3>4.网站设置</h3>
                                <span> 97Admin </span>
                                <a class="fancybox" data-fancybox-group="group" href="/public/img/photo/03.png">Read</a>
                            </figcaption>
                        </figure>
                    </li>
                    <li>
                        <figure>
                            <img src="/public/img/photo/04.png" alt="编辑角色">
                            <figcaption>
                                <h3>5.编辑角色</h3>
                                <span> 97Admin </span>
                                <a class="fancybox" data-fancybox-group="group" href="/public/img/photo/04.png">Read</a>
                            </figcaption>
                        </figure>
                    </li>
                    <li>
                        <figure>
                            <img src="/public/img/photo/success.png" alt="编辑角色">
                            <figcaption>
                                <h3>6.操作成功</h3>
                                <span> 97Admin </span>
                                <a class="fancybox" data-fancybox-group="group" href="/public/img/photo/success.png">Read</a>
                            </figcaption>
                        </figure>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    $(function() {
        jQuery(".fancybox").fancybox();
    });
</script>
