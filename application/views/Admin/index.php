<style>
    .left{
        margin-left: -10px;
    }
</style>
<div class="col-lg-4 left">
    <div class="panel panel-success">
        <div class="panel-heading">
            系统介绍
            <span class="tools pull-right" style="display: block">
				<a href="javascript:;" class="fa fa-chevron-down"></a>
			</span>
        </div>
        <div class="panel-body" style="display: block;">
            <h4>人人编剧后台管理系统</h4>
<!--            <p>人人编剧后台管理系统是基于Codeigniter框架和Gentelella后台模版进行开发！</p>-->
            <p>php版本：<?= PHP_VERSION;?></p>
            <p>服务器：<?= php_uname('s');?></p>
            <p>PHP运行方式：<?= php_sapi_name();?></p>
            <p>服务器IP：<?= GetHostByName($_SERVER['SERVER_NAME']);?></p>
        </div>
    </div>
</div>
<!--<div class="col-lg-4" style="margin-left: 10px;">-->
<!--    <div class="panel panel-warning">-->
<!--        <div class="panel-heading">-->
<!--            联系方式-->
<!--            <span class="tools pull-right" style="display: block">-->
<!--				<a href="javascript:;" class="fa fa-chevron-down"></a>-->
<!--			</span>-->
<!--        </div>-->
<!--        <div class="panel-body" style="display: block;">-->
<!--            <h4>TEAM</h4>-->
<!--            <p>开源是一种精神！为中国的互联网行业发展献出一份小小的力量。</p>-->
<!--            <p>作者：symphp</p>-->
<!--            <p>开发者邮箱：symphp@foxmail.com</p>-->
<!--            <p>博客地址：<a href="http://www.symphp.com" target="_blank">www.symphp.com</a></p>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--<div class="col-lg-4 left" style="float: right;margin-right: -10px;">-->
<!--    <div class="panel panel-danger">-->
<!--        <div class="panel-heading">-->
<!--            官方消息-->
<!--            <span class="tools pull-right" style="display: block">-->
<!--				<a href="javascript:;" class="fa fa-chevron-down"></a>-->
<!--			</span>-->
<!--        </div>-->
<!--        <div class="panel-body" style="display: block;">-->
<!--            <h4>OFFICIAL</h4>-->
<!--            <p>技术交流</p>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<div class="x_panel">
    <div class="x_title">
        <h2>日志列表</h2>
        <div class="clearfix">
        </div>
    </div>
    <div class="x_content">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>id</th>
                <th>操作管理员</th>
                <th>时间</th>
                <th>ip</th>
                <th>描述</th>
            </tr>
            </thead>
            <tbody>
			<?php if(!empty($logs)) :?>
				<?php foreach ($logs as $log) :?>
                    <tr>
                        <th scope="row"><?= $log['id'];?></th>
                        <td><?= $log['username']??'';?></td>
                        <td><?= $log['time'];?></td>
                        <td><?= $log['ip'];?></td>
                        <td><?= $log['log'];?></td>
                    </tr>
				<?php endforeach;?>
			<?php endif;?>
            </tbody>
        </table>
			<?= $page;?>
    </div>
</div>