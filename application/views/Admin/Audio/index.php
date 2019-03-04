<link href="/public/assets/bootstrap-daterangepicker/daterangepicker.css?ver=0.6" rel="stylesheet" type="text/css" />
<script src="/public/assets/moment/min/moment.min.js?ver=0.6"></script>
<script src="/public/assets/bootstrap-daterangepicker/bootstrap-datepicker.zh-CN.js?ver=0.6" type="text/javascript"></script>
<script src="/public/assets/bootstrap-daterangepicker/daterangepicker.js?ver=0.6" type="text/javascript"></script>
<style media="screen" type="text/css">
    .bt{
        width: 85px;
    }
    .gz{
        width: 250px;
    }
</style>
<div class="x_panel">

		<div class="x_title">
			<h2><?=$current['title']??''?></h2>
            <a class="btn btn-primary pull-right" href="add" cust="button">新增音频</a>
			<div class="x_content">
                <form class="form-horizontal form-label-left select_from" action="index" method="get" id="autoForm" style="font-size: 15px;font-weight: normal">
                    <div class="item form-group">
                        <!--       所属课程             -->
<!--                         <label class="bt control-label col-md-3 col-sm-3 col-xs-12" for="icon">所属课程</label>
                        <div class="gz col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control valid" name="course_id" aria-invalid="false">
                                <option value="" >请选择</option>
                                <?php if(!empty($courses)) :?>
                                    <?php foreach ($courses as $key1 => $item1) :?>
                                        <option <?php if($key1 == ($request['course_id']??'')):?> selected = 'selected'; <?php endif;?> value="<?= $key1??'';?>" ><?= $item1??'';?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div> -->
                        <!--       连载/单集             -->
      <!--                   <label class="bt control-label col-md-3 col-sm-3 col-xs-12" for="icon">连载/单集</label>
                        <div class="gz col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control valid" name="update_status" aria-invalid="false">
                                <option value="" >请选择</option>
                                    <option value="1" >连载</option>
                                    <option value="2" >单集</option>
                            </select>
                        </div> -->
                        <!--       收费模式             -->
                        <label class="bt control-label col-md-3 col-sm-3 col-xs-12" for="icon">收费模式</label>
                        <div class="gz col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control valid" name="type" aria-invalid="false">
                                <option value="" >请选择</option>
                                    <option value="1" >收费</option>
                                    <option value="2" >试听</option>
                            </select>
                        </div>
                    </div>

                    <div class="item form-group">
                        <!--       时间             -->
                        <label class="bt control-label col-md-3 col-sm-3 col-xs-12" for="first-name">选择时间</label>
                        <div class="gz col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <button type="button" class="btn btn-default pull-right" id="daterange-btn" value="">
                                <i class="fa fa-calendar"></i>
                                <span><?php echo $request['start_time']??'';?> 至 <?php echo $request['end_time']??'';?></span>
                                <input type="hidden" id = 'start_time' name='start_time' value="<?php echo $request['start_time']??'';?>" >
                                <input type="hidden" id = 'end_time' name='end_time' value="<?php echo $request['end_time']??'';?>" >
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>
                    </div>
                    <div class="item form-group" >
                        <div class="col-md-6 col-md-offset-3">
                            <button id="send" type='submit' class="btn btn-success">提交</button>
                            <button id="reset" type="button" class="btn btn-defalut">重置</button>
                            <button id="export" type='button' class="btn btn-primary">导出</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>
		<div class="x_content">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>id</th>
                    <th>缩略图</th>
                    <th>音频</th>
                    <th>标题</th>
                    <th>价格</th>
                    <th>所属课程</th>
                    <th>收费模式</th>
                    <th>是否原创</th>
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
                                    <?php if (empty($audio['audio_pic'])) :?>
                                        <td><img src="/public/img/no_image.png" height="45" width="45"></td>
                                    <?php else :?>
                                        <td><img src="<?=$audio['audio_pic'] ?>" height="45" width="45"/></td>
                                    <?php endif;?>
                                    <td style="width: 200px"><audio src="<?=$audio['audio_addr'];?>" controls="controls"></audio></td>
                                    <td><?=$audio['title'];?></td>
                                    <td><?=$audio['price'];?></td>
                                    <td><?=$audio['course_title'];?></td>
                                    <td><?=$audio['type'];?></td>
                                     <td><?=$audio['is_original'];?></td>
                                    <td><?=$audio['create_time'];?></td>
                                    <td><?=$audio['status'];?></td>
									<td>
										<a href="edit?id=<?= $audio['id'];?>" class="btn btn-primary btn-xs" title="修改">
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
								</tr>
							<?php endforeach;?>
						<?php endif;?>
				</tbody>
			</table>
            <div style="float: left; margin-bottom: 5px;"><?= "共{$count}条";?></div>
            <?= $page;?>
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