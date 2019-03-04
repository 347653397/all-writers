<link href="/public/assets/bootstrap-daterangepicker/daterangepicker.css?ver=0.6" rel="stylesheet" type="text/css" />
<script src="/public/assets/moment/min/moment.min.js?ver=0.6"></script>
<script src="/public/assets/bootstrap-daterangepicker/daterangepicker.js?ver=0.6" type="text/javascript"></script>
<script src="/public/assets/bootstrap-daterangepicker/bootstrap-datepicker.zh-CN.js?ver=0.6" type="text/javascript"></script>
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
            <div class="x_content">
                <form class="form-horizontal form-label-left select_from" action="index" method="get" id="autoForm" style="font-size: 15px;font-weight: normal">
                    <input type="hidden"  name='pgtype' value="<?php echo $_GET['pgtype']??'';?>" >
                    <div class="item form-group">
                        <!--       标题             -->
                        <label class="bt control-label col-md-3 col-sm-3 col-xs-12" for="icon">标题</label>
                        <div class="gz col-md-6 col-sm-6 col-xs-12">
                            <input id="course_title" class="form-control col-md-7 col-xs-12" value="<?php echo $request['course_title']??'';?>" name="course_title" type="text">
                        </div>
                        <!--       所属分类             -->
                        <label class="bt control-label col-md-3 col-sm-3 col-xs-12" for="icon">所属分类</label>
                        <div class="gz col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control valid" name="cid" aria-invalid="false">
                                <option value="" >请选择</option>
                                <?php if(!empty($category)) :?>
                                    <?php foreach ($category as $key1 => $item1) :?>
                                        <option <?php if($key1 == ($request['category']??'')):?> selected = 'selected'; <?php endif;?> value="<?= $key1??'';?>" ><?= $item1??'';?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                        <!--       连载/单集             -->
                        <label class="bt control-label col-md-3 col-sm-3 col-xs-12" for="icon">连载/单集</label>
                        <div class="gz col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control valid" name="up_type" aria-invalid="false">
                                <option value="" >请选择</option>
                                    <option value="1" >连载</option>
                                    <option value="2" >单集</option>
                            </select>
                        </div>
                    </div>

                    <div class="item form-group">
                        <!--       作者             -->
                        <label class="bt control-label col-md-3 col-sm-3 col-xs-12" for="icon">作者</label>
                        <div class="gz col-md-6 col-sm-6 col-xs-12">
                            <input id="author" class="form-control col-md-7 col-xs-12" value="<?php echo $request['author']??'';?>" name="author" type="text">
                        </div>
                        <!--       时间             -->
<!--                         <label class="bt control-label col-md-3 col-sm-3 col-xs-12" for="first-name">选择时间</label>
                        <div class="gz col-md-6 col-sm-6 col-xs-12">
                            <div class="input-group">
                                <button type="button" class="btn btn-default pull-right" id="daterange-btn" value="">
                                    <i class="fa fa-calendar"></i>
                                    <span><?php echo $request['start_time']??'';?> 至 <?php echo $request['end_time']??'';?></span>
                                    <input type="hidden" id = 'start_time' name='start_time' value="" >
                                    <input type="hidden" id = 'end_time' name='end_time' value="" >
                                    <i class="fa fa-caret-down"></i>
                                </button>
                            </div>
                        </div> -->
                    </div>
                    <div class="item form-group" >
                        <div class="col-md-6 col-md-offset-3">
                            <button id="send" type='submit' class="btn btn-success">提交</button>
                            <button id="reset" type="button" class="btn btn-defalut">重置</button>
<!--                            <button id="export" type='button' class="btn btn-primary">导出</button>-->
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
                    <th>课程类型</th>
					<th>缩略图</th>
                    <th>标签</th>
                    <th>标题</th>
                    <th>作者</th>
					<th>上线时间</th>
                    <th>排序</th>
					<th>状态</th>
                    <th>审核状态</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
						<?php if(!empty($courses)) :?>
							<?php foreach ($courses as $course) :?>
								<tr>
									<th scope="row"><?=$course['id'];?></th>
                                    <th scope="row"><?=$course['up_type'];?></th>
                                    <?php if (empty($course['course_pic'])) :?>
                                        <td><img src="/public/img/no_image.png" height="45" width="45"></td>
                                    <?php else :?>
                                        <td><img src="<?=$course['course_pic'] ?>" height="45" width="45"/></td>
                                    <?php endif;?>
                                    <td><?=$course['category'];?></td>
                                    <td><?=$course['course_title'];?></td>
                                    <td><?=$course['author'];?></td>
									<td><?=date("Y-m-d H:i:s",$course['create_time']);?></td>
                                    <td><?=$course['sort'];?></td>
                                    <td><?=$course['status'];?></td>
                                    <td><?=$course['audit_status'];?></td>
									<td>
										<a href="/Admin/Audio/edit?id=<?= $course['id'];?>" class="btn btn-primary btn-xs" title="修改">
											修改
										</a>
                                        <a href="javascript:void(0)" data-id="<?= $course['id']; ?>"  data-url="/Admin/Course/verify?id=<?= $course['id'];?>&type=1" class="btn btn-primary btn-xs verify" title="审核">
                                            审核
                                        </a>
                                        <?php if(empty($course['is_recommend']) || $course['is_recommend'] =='1') :?>
    				                        <a href="javascript:void(0)" data-url="/Admin/Course/recommend?id=<?= $course['course_id'];?>&is_recommend=2;?>" class="btn btn-primary btn-xs recommend" title="推荐">
    											推荐
    										</a>
                                        <?php else :?>
                                            <a href="javascript:void(0)" data-url="/Admin/Course/recommend?id=<?= $course['course_id'];?>&is_recommend=1;?>" class="btn btn-primary btn-xs recommend" title="推荐">
                                                取消推荐
                                            </a>
                                        <?php endif?>
										<a href="/Admin/Order/courseList?course_id=<?= $course['course_id'];?>" class="btn btn-primary btn-xs" title="购买信息">
											购买信息
										</a>
										<a href="/Admin/Comment/index?course_id=<?= $course['course_id'];?>" class="btn btn-primary btn-xs" title="评论列表">
											评论列表
										</a>
                                        <?php if($course['status'] == '显示') :?>
                                            <a href="javascript:void;" data-url="/Admin/Course/del?id=<?= $course['course_id'];?>" class="btn btn-danger btn-xs del" title="删除">
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
        $('.verify').on('click',function(){
            var id = $(this).attr('data-id');
            layer.confirm('文章审核', {
                btn: ['审核成功', '审核失败'] //按钮
            },function() {
                $.ajax({
                    url: '/Admin/Course/verify',
                    type: 'post',
                    dataType: 'json',
                    data: {'id':id,'type':1},
                    success: function (res) {
                        if(res.status){
                            layer.msg(res.msg);
                            setInterval(function () {
                                window.location.href = '/Admin/Course/index2';
                            },2000)
                        }else{
                            layer.msg(res.msg);
                        }
                    }
                });
            }, function(){
                layer.prompt({title: '审核失败备注', formType: 2}, function (text, index) {
                    $.ajax({
                        url: '/Admin/Course/verify',
                        type: 'post',
                        dataType: 'json',
                        data: {'remark': text,'id':id,'type':'2'},
                        success: function (res) {
                            if(res.status){
                                layer.close(index);
                                layer.msg(res.msg);
                                setInterval(function () {
                                    window.location.href = '/Admin/Course/index2';
                                },2000)
                            }else{
                                layer.msg(res.msg);
                            }
                        }
                    });
                });
            });
        })
    });
    //推荐
    $(function(){
        $('.recommend').on('click',function(){
            var url  = $(this).attr('data-url');
            layer.confirm('确定要推荐此课程吗？', {
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
