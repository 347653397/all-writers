<link href="/public/assets/bootstrap-daterangepicker/daterangepicker.css?ver=0.6" rel="stylesheet" type="text/css"/>
<script src="/public/assets/moment/min/moment.min.js?ver=0.6"></script>
<script src="/public/assets/bootstrap-daterangepicker/bootstrap-datepicker.zh-CN.js?ver=0.6"
        type="text/javascript"></script>
<script src="/public/assets/bootstrap-daterangepicker/daterangepicker.js?ver=0.6" type="text/javascript"></script>
<div class="x_panel">
    <div class="x_title">
        <h2><?= $current['title'] ?? '' ?></h2>
        <div class="x_content">
            <form class="form-horizontal form-label-left select_from" action="index" method="get"
                  style="font-size: 15px;font-weight: normal">
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
                                <span><?php echo $_GET['start_time'] ?? ''; ?>
                                    至 <?php echo $_GET['end_time'] ?? ''; ?></span>
                                <input type="hidden" id='start_time' name='start_time' value="<?php echo $_GET['start_time']??'';?>">
                                <input type="hidden" id='end_time' name='end_time' value="<?php echo $_GET['end_time']??'';?>">
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>

                    <label class="bt control-label col-md-1" for="icon">提现状态</label>
                    <div class="gz col-md-2">
                        <select class="form-control valid" name="status" aria-invalid="false">
                            <option value="">请选择</option>
                            <?php foreach (withdraw_status() as $key => $val) : ?>
                                <option <?php if ($key == ($_GET['status'] ?? '')): ?>
                                    selected='selected'; <?php endif; ?>
                                        value="<?= $key ?? ''; ?>"><?= $val ?? ''; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
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
                <th>姓名</th>
                <th>手机号</th>
                <th>金额</th>
                <th>提现时间</th>
                <th>提现状态</th>
                <th>备注</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($data)) : ?>
                <?php foreach ($data as $val) : ?>
                    <tr>
                        <td><?= $val['id']; ?></td>
                        <td><?php echo emoji_to_string($val['nickname']); ?></td>
                        <td><?= $val['name']; ?></td>
                        <td><?= $val['mobile']; ?></td>
                        <td><?= $val['money']; ?></td>
                        <td><?= date("Y-m-d H:i:s", $val['created_at']); ?></td>
                        <td><?= withdraw_status()[$val['status']]; ?></td>
                        <td><?= $val['remark'] ?></td>
                        <td>
                            <?php if ($val['status'] == 1): ?>
                                <a href="javascript:void;" data-status="2" data-id="<?= $val['id']; ?>" class="btn btn-primary btn-xs deal"
                                   title="提现完成">
                                    提现完成
                                </a>
                                <a href="javascript:void;" data-status="3" data-id="<?= $val['id']; ?>" class="btn btn-primary btn-xs deal"
                                   title="不给提现">
                                    不给提现
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
            var status = $(this).attr('data-status');
            var id = $(this).attr('data-id');
            layer.prompt({title: '提现备注', formType: 2}, function (text, index) {
                $.ajax({
                    url: '/Admin/Withdraw/deal',
                    type: 'post',
                    dataType: 'json',
                    data: {'status': status, 'remark': text,'id':id},
                    success: function (res) {
                        if(res.status){
                            layer.close(index);
                            layer.msg(res.msg);
                            window.location.href = '/Admin/Withdraw/index';
                        }else{
                            layer.msg(res.msg);
                        }
                    }
                });
            });
        })
    })
</script>
