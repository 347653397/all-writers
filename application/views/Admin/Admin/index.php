<div class="x_panel">
	<div class="x_title">
		<h2><?=$current['title']??''?></h2>
		<a class="btn btn-primary pull-right" href="add" role="button">添加用户</a>
		<div class="clearfix"></div>
	</div>
	<div class="x_content">
		<table class="table table-striped">
			<thead>
			<tr>
				<th>id</th>
				<th>用户名</th>
				<th>性别</th>
                <th>角色</th>
				<th>电话</th>
				<th>邮箱</th>
				<th>注册时间</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<?php if(!empty($admins)) :?>
				<?php foreach ($admins as $admin) :?>
					<tr>
						<th scope="row"><?= $admin['id'];?></th>
						<td><?= $admin['username'];?></td>
						<td><?= $admin['sex'];?></td>
						<td><?= $admin['role_name'];?></td>
						<td><?= $admin['phone'];?></td>
						<td><?= $admin['email']??'';?></td>
						<td><?= date('Y-m-d',$admin['reg_time']);?></td>
						<td>
							<a href="edit?id=<?= $admin['id'];?>" class="btn btn-primary btn-xs" title="修改">
								<li class="fa fa-pencil"></li>
							</a>
							<a href="javascript:void(0);" data-url="/Admin/Admin/del?id=<?= $admin['id'];?>" class="btn btn-danger btn-xs del" title="删除">
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