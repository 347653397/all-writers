<div class="x_panel">
		<div class="x_title">
			<h2><?=$current['title']??''?></h2>
				<a class="btn btn-primary pull-right" href="add" role="button">添加角色</a>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>id</th>
					<th>角色名称</th>
					<th>状态</th>
					<th>描述</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
						<?php if(!empty($roles)) :?>
							<?php foreach ($roles as $role) :?>
								<tr>
									<th scope="row"><?=$role['role_id'];?></th>
									<td><?=$role['role_name'];?></td>
									<td><?=$role['explain'];?></td>
									<td><?=$role['status'];?></td>
									<td>
										<a href="edit?id=<?= $role['role_id'];?>" class="btn btn-primary btn-xs" title="修改">
											<li class="fa fa-pencil"></li>
										</a>
                                        <?php if($role['status'] == '显示') :?>
                                            <a href="javascript:void;" data-url="/Admin/Role/del?id=<?= $role['role_id'];?>" class="btn btn-danger btn-xs del" title="删除">
                                                <li class="fa fa-trash-o"></li>
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
            layer.confirm('确定要删除此角色吗？', {
                btn: ['确定', '取消'] //按钮
            },function() {
                window.location.href = url;
            });
        })
    })
</script>