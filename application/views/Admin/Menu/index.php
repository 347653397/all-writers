<!--
 Created by PhpStorm.
 User: symphp symphp@foxmail.com
 Date: 2017/7/25
 Time: 15:32
 -->
	<div class="x_panel">
		<div class="x_title">
			<h2><?=$current['title']??''?></h2>
				<a class="btn btn-primary pull-right" href="add" role="button">添加菜单</a>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>id</th>
					<th>菜单名称</th>
					<th>Controller/Function</th>
					<th>排序</th>
                    <th>提示</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
						<?php if(!empty($menus)) :?>
							<?php foreach ($menus as $menu) :?>
								<tr>
									<th scope="row"><?=$menu['auth_id'];?></th>
									<td><?=$menu['title'];?></td>
									<td><?=$menu['name'];?></td>
									<td><?=$menu['sort'];?></td>
                                    <td><?= $menu['auth_explain']??'';?></td>
									<td>
										<a href="edit?id=<?= $menu['auth_id'];?>" class="btn btn-primary btn-xs" title="修改">
											<li class="fa fa-pencil"></li>
										</a>
										<a href="javascript:void(0);" data-url="/Admin/Menu/del?id=<?= $menu['auth_id'];?>" class="btn btn-danger btn-xs del" title="删除">
											<li class="fa fa-trash-o"></li>
										</a>
									</td>
								</tr>
								<?php if(isset($menu['children'])) :?>
									<?php foreach ($menu['children'] as $child) :?>
										<tr>
											<th scope="row"><?= $child['auth_id'];?></th>
											<td>┗━<?= $child['title'];?></td>
											<td><?= $child['name'];?></td>
											<td><?= $child['sort'];?></td>
                                            <td><?= $child['auth_explain']??'';?></td>
											<td>
												<a href="edit?id=<?= $child['auth_id'];?>" class="btn btn-primary btn-xs" title="修改">
													<li class="fa fa-pencil"></li>
												</a>
												<a href="javascript:void(0);" data-url="/Admin/Menu/del?id=<?= $child['auth_id'];?>" class="btn btn-danger btn-xs del" title="删除">
													<li class="fa fa-trash-o"></li>
												</a>
											</td>
										</tr>
                                        <?php if(isset($child['children'])) :?>
                                            <?php foreach ($child['children'] as $childd) :?>
                                                <tr>
                                                    <th scope="row"><?= $childd['auth_id'];?></th>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━<?= $childd['title'];?></td>
                                                    <td><?= $childd['name'];?></td>
                                                    <td><?= $childd['sort'];?></td>
                                                    <td><?= $childd['auth_explain']??'';?></td>
                                                    <td>
                                                        <a href="edit?id=<?= $childd['auth_id'];?>" class="btn btn-primary btn-xs" title="修改">
                                                            <li class="fa fa-pencil"></li>
                                                        </a>
                                                        <a href="javascript:void(0)" data-url="/Admin/Menu/del?id=<?= $childd['auth_id'];?>" class="btn btn-danger btn-xs del" title="删除">
                                                            <li class="fa fa-trash-o"></li>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach;?>
                                        <?php endif;?>
									<?php endforeach;?>
								<?php endif;?>
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
            layer.confirm('确定要删除此菜单吗？', {
                btn: ['确定', '取消'] //按钮
            }, function() {
                window.location.href = url;
            });
        })
    })
</script>