<link href="/public/assets/bootstrap-daterangepicker/daterangepicker.css?ver=0.6" rel="stylesheet" type="text/css" />
<script src="/public/assets/moment/min/moment.min.js?ver=0.6"></script>
<script src="/public/assets/bootstrap-daterangepicker/bootstrap-datepicker.zh-CN.js?ver=0.6" type="text/javascript"></script>
<script src="/public/assets/bootstrap-daterangepicker/daterangepicker.js?ver=0.6" type="text/javascript"></script>
<style media="screen" type="text/css">
    .bt{
        width: 80px;
    }
    .gz{
        width: 250px;
    }
</style>
<div class="x_panel">

		<div class="x_title">
			<h2><?=$current['title']??''?></h2>
            <a class="btn btn-primary pull-right" href="/Admin/Audio/add" cust="button">新增音频</a>
            <a class="btn btn-primary pull-right" href="?kc=1&course_id=<?= $_GET['course_id']?>" cust="button">选择绑定音频</a>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>id</th>
                    <th>音频</th>
                    <th>标题</th>
                    <th>所属课程</th>
                    <th>上传时间</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
						<?php if(!empty($audios)) :?>
							<?php foreach ($audios as $audio) :?>
								<tr>
									<th scope="row"><?=$audio['id'];?></th>
                                    <td style="width: 300px"><audio src="<?=$audio['audio_addr'];?>" controls="controls"></audio></td>
                                    <td><?=$audio['title'];?></td>
                                    <td><?=$audio['course_title'];?></td>
                                    <td><?=$audio['create_time'];?></td>
                                    <td><?=$audio['status'];?></td>
                                    <?php if($audio['course_id'] != '0') :?>
    									<td>
    										<a href="/Admin/Audio/edit?id=<?= $audio['id'];?>" class="btn btn-primary btn-xs" title="修改">
    											修改
    										</a>
                                            <?php if($audio['status'] == '显示') :?>
                                                <a href="javascript:void;" data-url="/Admin/Audio/del?id=<?= $audio['id'];?>" class="btn btn-danger btn-xs del" title="删除">
                                                    <li class="fa fa-trash-o"> 删除</li>
                                                </a>
                                            <?php else:?>
                                                <a href="javascript:void(0)" class="btn btn-default btn-xs" title="不操作">
                                                    <li class="fa fa-ban"></li>
                                                </a>
                                            <?php endif;?>

    									</td>
                                    <?php else :?>
                                        <td>
                                            <a href="javascript:void;" data-url="/Admin/Course/bind?course_id=<?= $_GET['course_id'];?>&audio_id=<?= $audio['id'];?>" class="btn btn-primary btn-xs bind" title="绑定到本课程">
                                                <li class="fa fa-trash-o"> 绑定到本课程</li>
                                            </a>
                                        </td>
                                    <?php endif;?>
								</tr>
							<?php endforeach;?>
                        <?php else:?>
                            <tr>
                                <td colspan="7" style="text-align:center; font-size: 20px;">暂无未绑定的音频</td>
                            </tr>
						<?php endif;?>
				</tbody>
			</table>
		</div>
	</div>
<script>
    $(function(){
        $('.bind').on('click',function(){
            var url  = $(this).attr('data-url');
            layer.confirm('确定要绑定到本课程吗？', {
                btn: ['确定', '取消'] //按钮
            },function() {
                window.location.href = url;
            });
        })
    })
    $(function(){
        $('.del').on('click',function(){
            var url  = $(this).attr('data-url');
            layer.confirm('确定要删除此课程吗？', {
                btn: ['确定', '取消'] //按钮
            },function() {
                window.location.href = url;
            });
        })
    });
</script>