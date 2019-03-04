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
                    <label class="control-label col-md-1">手机号</label>
                    <div class="col-md-2">
                        <input class="form-control" value="<?php echo $_GET['mobile'] ?? ''; ?>"
                               name="mobile" type="text" placeholder="手机号">
                    </div>

                    <label class="control-label col-md-1">昵称</label>
                    <div class="col-md-2">
                        <input class="form-control" value="<?php echo $_GET['nickname'] ?? ''; ?>"
                               name="nickname" type="text" placeholder="昵称">
                    </div>

                    <label class="bt control-label col-md-1" for="icon">用户状态</label>
                    <div class="col-md-1">
                        <select class="form-control valid" name="status" aria-invalid="false">
                            <option value="" >请选择</option>
                            <option <?php if(($_GET['status']??'') == 1):?> selected = 'selected'; <?php endif;?> value="1" >正常</option>
                            <option <?php if(($_GET['status']??'') == 2):?> selected = 'selected'; <?php endif;?> value="2" >黑名单</option>
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
                <th>用户ID</th>
                <th>姓名</th>
                <th>性别</th>
                <th>昵称</th>
                <th>手机号</th>
                <th>用户状态</th>
                <th>账户余额</th>
                <th>注册时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($user)) : ?>
                <?php foreach ($user as $val) : ?>
                    <tr>
                        <td><?= $val['id']; ?></td>
                        <td><?= $val['name']; ?></td>
                        <td><?php
                            switch ($val['sex']) {
                                case 1:
                                    echo '男';
                                    break;
                                case 2:
                                    echo '男';
                                    break;
                                default:
                                    echo '未知';
                            }
                            ?>
                        </td>
                        <td><?php echo emoji_to_string($val['nickname']); ?></td>
                        <td><?= $val['mobile']; ?></td>
                        <td><?= $val['status'] == 1 ? '正常' : '黑名单';; ?></td>
                        <td><?= $val['cash_balance'] ?></td>
                        <td><?= date("Y-m-d H:i:s", $val['created_at']); ?></td>
                        <td>
                            <?php if($val['status'] == 1):?>
                                <a href="javascript:void;" data-url="/Admin/User/block?status=2&id=<?= $val['id'];?>" class="btn btn-primary btn-xs block">
                                    加入黑名单
                                </a>
                            <?php else:?>
                                <a href="javascript:void;" data-url="/Admin/User/block?status=1&id=<?= $val['id'];?>" class="btn btn-primary btn-xs block">
                                    移除黑名单
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
        $('.block').on('click', function () {
            var url = $(this).attr('data-url');
            layer.confirm('确定要进行该操作吗？', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                window.location.href = url;
            });
        })
    })
</script>
