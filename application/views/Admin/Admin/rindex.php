<?php
 $msg = array('1' => '未激活','2' => '激活');
?>
<div class="x_panel">
	<div class="x_title">
		<h2><?=$current['title']??''?></h2>
		<div class="clearfix"></div>
	</div>
	<div class="x_content">
		<table class="table table-striped">
			<thead>
			<tr>
				<th>id</th>
				<th>来源</th>
				<th>手机</th>
                <th>注册时间</th>
				<th>激活状态</th>
				<th>邮箱</th>
			</tr>
			</thead>
			<tbody>
			<?php if(!empty($users)) :?>
				<?php foreach ($users as $user) :?>
					<tr>
						<th scope="row"><?= $user['id'];?></th>
						<td><?= $user['from'];?></td>
						<td><?= $user['user_phone'];?></td>
						<td><?= $user['create_time'];?></td>
						<td><?= $msg[$user['status']];?></td>
						<td><?= $user['email']??'';?></td>
						<td>
							<a href="edit?id=<?= $user['id'];?>" class="btn btn-primary btn-xs" title="修改">
								<li class="fa fa-pencil"></li>
							</a>
							<a href="javascript:void(0);" data-url="/Admin/User/del?id=<?= $user['id'];?>" class="btn btn-danger btn-xs del" title="删除">
								<li class="fa fa-trash-o"></li>
							</a>
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
            layer.confirm('确定要删除此账号吗？', {
                btn: ['确定', '取消'] //按钮
            }, function() {
                window.location.href = url;
            });
        })
    })
</script>