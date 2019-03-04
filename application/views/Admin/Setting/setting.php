<?php
/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/7/20
 * Time: 23:45
 */
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form class="form-horizontal form-label-left select_from" action="setting" method="post" id="autoForm">
                    <span class="section">网站设置</span>
                    <?php if($settings) :?>
                        <?php foreach ($settings as $setting) :?>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?=$setting['note']??'';?> <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="title" class="form-control col-md-7 col-xs-12" name="<?=$setting['key']??'';?>" type="text" value="<?=$setting['value']??'';?>" required>
                                </div>
                            </div>
                        <?php endforeach;?>
                    <?php endif;?>
                    <div class="item form-group">
                        <div class="col-md-6 col-md-offset-3">
                            <button id="send" type='submit' class="btn btn-success">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>