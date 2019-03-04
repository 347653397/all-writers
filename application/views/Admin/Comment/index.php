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
                    <label class="control-label col-md-1">用户ID</label>
                    <div class="col-md-2">
                        <input class="form-control" value="<?php echo $_GET['user_id'] ?? ''; ?>"
                               name="user_id" type="text" placeholder="用户ID">
                    </div>

                    <label class="control-label col-md-1" for="first-name">选择时间</label>
                    <div class="col-md-4">
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
                <th>编号</th>
                <th>用户ID</th>
                <th>昵称</th>
                <th>课程</th>
                <th>内容</th>
                <th>评论时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($data)) : ?>
                <?php foreach ($data as $val) : ?>
                    <tr>
                        <td><?= $val['id']; ?></td>
                        <td><a style="color: #8a6d3b;" href="/Admin/User/index?id=<?= $val['user_id']; ?>"><?= $val['user_id']; ?></a></td>
                        <td><a style="color: #8a6d3b;" href="/Admin/User/index?id=<?= $val['user_id']; ?>"><?php echo emoji_to_string($val['nickname']); ?></a></td>
                        <td><?= mb_strimwidth($val['title'],0,50,'...','UTF-8'); ?></td>
                        <td><?= mb_strimwidth($val['content'],0,100,'...','UTF-8')?></td>
                        <td><?= date("Y-m-d H:i:s", $val['created_at']); ?></td>
                        <td>
                            <?php if($val['status'] == '1') :?>
                                <a href="javascript:void;" data-url="/Admin/Comment/update?status=2&id=<?= $val['id'];?>" class="btn btn-primary btn-xs yc" title="隐藏">
                                    隐藏
                                </a>
                            <?php else:?>
                                <a href="javascript:void;" data-url="/Admin/Comment/update?status=1&id=<?= $val['id'];?>" class="btn btn-primary btn-xs xs" title="隐藏">
                                     显示
                                </a>
                            <?php endif;?>

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
        $('.yc').on('click', function () {
            var url = $(this).attr('data-url');
            layer.confirm('确定要隐藏该评论吗？', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                window.location.href = url;
            });
        })
    });
    $(function () {
        $('.xs').on('click', function () {
            var url = $(this).attr('data-url');
            layer.confirm('确定要显示该评论吗？', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                window.location.href = url;
            });
        })
    })
</script>
