<link href="/public/assets/bootstrap-daterangepicker/daterangepicker.css?ver=0.6" rel="stylesheet" type="text/css"/>
<script src="/public/assets/moment/min/moment.min.js?ver=0.6"></script>
<script src="/public/assets/bootstrap-daterangepicker/bootstrap-datepicker.zh-CN.js?ver=0.6" type="text/javascript"></script>
<script src="/public/assets/bootstrap-daterangepicker/daterangepicker.js?ver=0.6" type="text/javascript"></script>
<div class="x_panel">
    <div class="x_title">
        <h2><?= $current['title'] ?? '' ?></h2>
        <div class="x_content">
            <form class="form-horizontal form-label-left select_from" action="index" method="get" style="font-size: 15px;font-weight: normal">
                <div class="item form-group">
                    <label class="control-label col-md-1">昵称</label>
                    <div class="col-md-2">
                        <input class="form-control" value="<?php echo $_GET['nickname'] ?? ''; ?>"
                               name="nickname" type="text" placeholder="昵称">
                    </div>

                    <label class="control-label col-md-1" for="first-name">选择时间</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <button type="button" class="btn btn-default pull-right" id="daterange-btn" value="">
                                <i class="fa fa-calendar"></i>
                                <span><?php echo $_GET['start_time']??'';?> 至 <?php echo $_GET['end_time']??'';?></span>
                                <input type="hidden" id = 'start_time' name='start_time' value="<?php echo $_GET['start_time']??'';?>" >
                                <input type="hidden" id = 'end_time' name='end_time' value="<?php echo $_GET['end_time']??'';?>" >
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>

                    <label class="bt control-label col-md-1" for="icon">状态</label>
                    <div class="col-md-1">
                        <select class="form-control valid" name="status" aria-invalid="false">
                            <option value="" >请选择</option>
                            <option <?php if(($_GET['status']??'') == 1):?> selected = 'selected'; <?php endif;?> value="1" >待解决</option>
                            <option <?php if(($_GET['status']??'') == 2):?> selected = 'selected'; <?php endif;?> value="2" >已解决</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type='submit' class="btn btn-success">提交</button>
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
                <th>ID</th>
                <th>昵称</th>
                <th>手机号</th>
                <th>内容</th>
                <th>状态</th>
                <th>备注</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($data)) : ?>
                <?php foreach ($data as $val) : ?>
                    <tr>
                        <td><?= $val['id']; ?></td>
                        <td><?php echo emoji_to_string($val['nickname']); ?></td>
                        <td><?= $val['mobile']; ?></td>
                        <td><?= $val['content']; ?></td>
                        <td><?= $val['status'] == 1 ? '待解决' : '已解决';?></td>
                        <td><?= $val['remark'] ?></td>
                        <td><?= date("Y-m-d H:i:s", $val['created_at']); ?></td>
                        <td>
                            <?php if($val['status'] == 1):?>
                                <a data-id="<?= $val['id'];?>" href="javascript:void;" class="btn btn-primary btn-xs deal">
                                    处理
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <div style="float: left; margin-bottom: 5px;"><?= "共{$count}条"; ?></div>
        <?= $page; ?>
    </div>
</div>
<script>
    $(function () {
        $('.deal').on('click', function () {
            var id = $(this).attr('data-id');
            layer.prompt({title: '操作备注', formType: 2}, function (text, index) {
                $.ajax({
                    url: '/Admin/Feedback/deal',
                    type: 'post',
                    dataType: 'json',
                    data: {'remark': text,'id':id},
                    success: function (res) {
                        if(res.status){
                            layer.close(index);
                            layer.msg(res.msg);
                            window.location.href = '/Admin/Feedback/index';
                        }else{
                            layer.msg(res.msg);
                        }
                    }
                });
            });
        })
    })
</script>
