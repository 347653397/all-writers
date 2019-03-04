<link href="/public/assets/bootstrap-daterangepicker/daterangepicker.css?ver=0.6" rel="stylesheet" type="text/css"/>
<script src="/public/assets/moment/min/moment.min.js?ver=0.6"></script>
<script src="/public/assets/bootstrap-daterangepicker/bootstrap-datepicker.zh-CN.js?ver=0.6" type="text/javascript"></script>
<script src="/public/assets/bootstrap-daterangepicker/daterangepicker.js?ver=0.6" type="text/javascript"></script>
<div class="x_panel">
    <div class="x_title">
        <h2><?= $current['title'] ?? '' ?></h2>
        <div class="x_content">
            <form class="form-horizontal form-label-left select_from" action="/Admin/Order/courseList" method="get" style="font-size: 15px;font-weight: normal">
                <div class="item form-group">
                    <label class="control-label col-md-1">订单编号</label>
                    <div class="col-md-2">
                        <input class="form-control" value="<?php echo $_GET['order_num'] ?? ''; ?>"
                               name="order_num" type="text" placeholder="订单编号">
                    </div>

                    <label class="control-label col-md-1">用户昵称</label>
                    <div class="col-md-2">
                        <input class="form-control" value="<?php echo $_GET['nickname'] ?? ''; ?>"
                               name="nickname" type="text" placeholder="用户昵称">
                    </div>


                    <label class="bt control-label col-md-1" for="icon">订单状态</label>
                    <div class="gz col-md-2">
                        <select class="form-control valid" name="pay_status" aria-invalid="false">
                            <option value="" >请选择</option>
                                <?php foreach (pay_status() as $key => $val) :?>
                                    <option <?php if($key == ($_GET['pay_status']??'')):?>
                                        selected = 'selected'; <?php endif;?> value="<?= $key??'';?>" ><?= $val??'';?></option>
                                <?php endforeach;?>
                        </select>
                    </div>
                </div>

                <div class="item form-group">
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
                <th>ID</th>
                <th>订单编号</th>
                <th>昵称</th>
                <th>课程标题</th>
                <th>订单状态</th>
                <th>金额</th>
                <th>下单时间</th>
                <th>支付时间</th>
                <th>交易号</th>
<!--                <th>操作</th>-->
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($data)) : ?>
                <?php foreach ($data as $val) : ?>
                    <tr>
                        <td><?= $val['id']; ?></td>
                        <td><?= $val['order_num']; ?></td>
                        <td><?= emoji_to_string($val['nickname']); ?></td>
                        <td><?= mb_strimwidth($val['course_title'],0,20,'...','UTF-8'); ?></td>
                        <td><?php echo pay_status()[$val['pay_status']];?></td>
                        <td><?= $val['pay_fee']; ?></td>
                        <td><?= date("Y-m-d H:i:s", $val['created_at']); ?></td>
                        <td><?= date("Y-m-d H:i:s", $val['payment_at']); ?></td>
                        <td><?= $val['transaction_id']; ?></td>
<!--                        <td>-->
<!--                            <a href="javascript:void;" data-url="/Admin/Order/course_detail?id=--><?//= $val['id'];?><!--" class="btn btn-primary btn-xs block" title="加入黑名单">-->
<!--                                查看详情-->
<!--                            </a>-->
<!--                        </td>-->
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
            layer.confirm('确定要把此人拉入加入黑名单吗？', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                window.location.href = url;
            });
        })
    })
</script>
