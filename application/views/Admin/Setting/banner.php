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
            <a class="btn btn-primary pull-right" href="addBanner" cust="button">新增Banner</a>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>id</th>
                    <th>Banner图</th>
                    <th>跳转地址</th>
                    <th>开始时间</th>
                    <th>结束时间</th>
                    <th>排序(越大越靠前)</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
						<?php if(!empty($banners)) :?>
							<?php foreach ($banners as $banner) :?>
								<tr>
									<th scope="row"><?=$banner['id'];?></th>
                                    <td><img src="<?=$banner['banner_pic'];?>" height="45" width="45"/></td>
                                    <td><?=$banner['jump_url'];?></td>
                                    <td><?=$banner['start_time'];?></td>
                                    <td><?=$banner['end_time'];?></td>
                                    <td><?=$banner['sort'];?></td>
                                    <td><?=$banner['status'];?></td>
									<td>
										<a href="editBanner?id=<?= $banner['id'];?>" class="btn btn-primary btn-xs" title="修改">
											修改
										</a>
                                        <?php if($banner['status'] == '显示') :?>
                                            <a href="javascript:void;" data-url="/Admin/Setting/delBanner?id=<?= $banner['id'];?>" class="btn btn-danger btn-xs del" title="删除">
                                                <li class="fa fa-trash-o"> 删除</li>
                                            </a>
                                        <?php else:?>
                                            <a href="javascript:void(0)" class="btn btn-default btn-xs" title="不操作">
                                                <li class="fa fa-ban"></li>
                                            </a>
                                        <?php endif;?>

									</td>
								</tr>
							<?php endforeach;?>
						<?php endif;?>
				</tbody>
			</table>
		</div>
	</div>
<script>
    $(function(){
        $('.del').on('click',function(){
            var url  = $(this).attr('data-url');
            layer.confirm('确定要删除此课程吗？', {
                btn: ['确定', '取消'] //按钮
            },function() {
                window.location.href = url;
            });
        })
    })
</script>
<script>
    //导出
    $('#export').on('click', function () {
        if (confirm("确定导出所有符合条件的数据(请不要超过1000条)?")) {
            location.href = '/Admin/GloryCard/index?type=1&' + $("form").serialize();
        }
    });
</script>