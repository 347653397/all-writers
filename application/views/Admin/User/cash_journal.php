<link href="/public/assets/bootstrap-daterangepicker/daterangepicker.css?ver=0.6" rel="stylesheet" type="text/css"/>
<script src="/public/assets/moment/min/moment.min.js?ver=0.6"></script>
<script src="/public/assets/bootstrap-daterangepicker/daterangepicker.js?ver=0.6" type="text/javascript"></script>
<script src="/public/assets/bootstrap-daterangepicker/bootstrap-datepicker.zh-CN.js?ver=0.6" type="text/javascript"></script>
<div class="x_panel">
    <div class="x_title">
        <h2><?= $current['title'] ?? '' ?></h2>
        <div class="x_content">
            <form class="form-horizontal form-label-left select_from" action="cashJournal" method="get" style="font-size: 15px;font-weight: normal">
                <div class="item form-group">
                    <label class="control-label col-md-1">用户ID</label>
                    <div class="col-md-1">
                        <input class="form-control" value="<?php echo $_GET['user_id'] ?? ''; ?>"
                               name="user_id" type="text" placeholder="用户ID">
                    </div>

                    <label class="control-label col-md-1" for="first-name">交易时间</label>
                    <div class="col-md-2">
                        <div class="input-group">
                            <button type="button" class="btn btn-default pull-right" id="daterange-btn" value="">
                                <i class="fa fa-calendar"></i>
                                <span><?php echo $_GET['start_time']??'';?> 至 <?php echo $_GET['end_time']??'';?></span>
                                <input type="hidden" id='start_time' name='start_time' value="<?php echo $_GET['start_time']??'';?>" >
                                <input type="hidden" id='end_time' name='end_time' value="<?php echo $_GET['end_time']??'';?>" >
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>

                    <label class="bt control-label col-md-1" for="icon">交易类型</label>
                    <div class="col-md-1">
                        <select class="form-control valid" name="trade_type" aria-invalid="false">
                            <option value="" >请选择</option>
                            <?php foreach (trade_type() as $key => $val) :?>
                                <option <?php if($key == ($_GET['trade_type']??'')):?>
                                    selected = 'selected'; <?php endif;?> value="<?= $key??'';?>" ><?= $val??'';?></option>
                            <?php endforeach;?>
                        </select>
                    </div>

                    <label class="bt control-label col-md-1" for="icon">交易方式</label>
                    <div class="col-md-1">
                        <select class="form-control valid" name="type" aria-invalid="false">
                            <option value="" >请选择</option>
                            <?php foreach (journal_type() as $key => $val) :?>
                                <option <?php if($key == ($_GET['type']??'')):?>
                                    selected = 'selected'; <?php endif;?> value="<?= $key??'';?>" ><?= $val??'';?></option>
                            <?php endforeach;?>
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
                <th>用户ID</th>
                <th>昵称</th>
                <th>交易类型</th>
                <th>交易方式</th>
                <th>交易前个人账户余额</th>
                <th>交易金额</th>
                <th>交易后个人账户余额</th>
                <th>交易时间</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($list)) : ?>
                <?php foreach ($list as $val) : ?>
                    <tr>
                        <td><?= $val['id']; ?></td>
                        <td><?= $val['user_id']; ?></td>
                        <td><?php echo emoji_to_string($val['nickname']); ?></td>
                        <td><?= trade_type()[$val['trade_type']]??''; ?></td>
                        <td><?= journal_type()[$val['type']]??'' ?></td>
                        <td><?= $val['original_balance'] ?></td>
                        <td><?= $val['money'] ?></td>
                        <td><?= $val['current_balance'] ?></td>
                        <td><?= date("Y-m-d H:i:s", $val['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <div style="float: left; margin-bottom: 5px;"><?= "共{$count}条"; ?></div>
        <?= $page; ?>
    </div>
</div>

