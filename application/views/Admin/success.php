<body class="nav-md">
<div class="container body">
	<div class="main_container">
		<!-- page content -->
		<div class="col-xs-12">
			<div class="col-middle">
				<div class="text-center" style="margin-top: 10%;">
					<img src="/public/img/success.png"/>
					<h3 style="color: white;"><?= $success['msg']??'操作成功'?></h3>
                    <div style="font-size: 1.3em;">
                        <span>页面自动</span>
                        <a href="<?= $success['url']??$_SERVER['HTTP_REFERER'];?>" style="color: white;">跳转</a>
                        <span>等待时间：</span>
<!--                        <span class="second" style="color: white">--><?//= $success['wait']??3;?><!--</span>-->
                        <span class="second" style="color: white"><?= 1?></span>
                    </div>
				</div>
			</div>
		</div>
		<!-- /page content -->
	</div>
    <!-- BASIC JQUERY LIB. JS -->
    <script type="text/javascript">
        $(function() {
            var wait = $(".second").html();
            timeOut();
            /**
             * 实现倒计时
             */
            function timeOut() {
                if(wait != 0) {
                    setTimeout(function() {
                        $('.second').text(--wait);
                        timeOut();
                    }, 1000);
                } else {
                    window.location.href = "<?= $success['url']??$_SERVER['HTTP_REFERER'];?>";
                }
            }
        });
    </script>
    <!-- END JS -->
</div>