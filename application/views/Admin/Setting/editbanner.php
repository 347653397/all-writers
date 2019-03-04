<?php
/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/8/2
 * Time: 20:53
 */
?>
<link rel="stylesheet" href="/public/css/bootstrap-fileupload.css">
<script src="/public/js/bootstrap-fileupload.js"></script>
<link rel="stylesheet" href="/public/css/validate.css">
<link href="/public/assets/bootstrap-daterangepicker/daterangepicker.css?ver=0.6" rel="stylesheet" type="text/css" />
<script src="/public/assets/moment/min/moment.min.js?ver=0.6"></script>
<script src="/public/assets/bootstrap-daterangepicker/bootstrap-datepicker.zh-CN.js?ver=0.6" type="text/javascript"></script>
<script src="/public/assets/bootstrap-daterangepicker/daterangepicker.js?ver=0.6" type="text/javascript"></script>
<style>
    .icheckbox_flat-green{
        margin-top: -2px;
    }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form class="form-horizontal form-label-left select_from" action="editBanner" method="post" enctype="multipart/form-data" id="autoForm" style="font-size: 15px;font-weight: normal">
                    <span class="section">编辑Banner</span>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banner_pic">Banner图 (建议尺寸750X320)
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="col-sm-3" style="margin: 0px -10px;">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="height:auto">
                                        <img src="<?= $banner['banner_pic']??'/public/img/no_image.png'?>" alt="">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail">
                                    </div>
                                    <div>
                                        <span class="btn btn-white btn-file" style="border-color: rgba(150,160,180,0.3)">
                                            <span class="fileupload-new">
                                                <i class="fa fa-paper-clip"></i> 选择图片
                                            </span>
                                            <span class="fileupload-exists">
                                                <i class="fa fa-undo"></i> 重置
                                            </span>
                                                <input type="file" class="default" name="banner_pic">
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="bt control-label col-md-3 col-sm-3 col-xs-12" for="first-name">开始/结束时间</label>
                    <div class="gz col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <button type="button" class="btn btn-default pull-right" id="daterange-btn" value="">
                                <i class="fa fa-calendar"></i>
                                <span id="datatime"><?php echo $banner['start_time']??'';?> 至 <?php echo $banner['end_time']??'';?></span>
                                <input type="hidden" id = 'start_time' name='start_time' value="<?=$banner['start_time']??''?>" >
                                <input type="hidden" id = 'end_time' name='end_time' value="<?=$banner['end_time']??''?>" >
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">跳转地址 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" name="jump_url" value="<?=$banner['jump_url']??''?>" type="text" required>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">排序 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" name="sort" value="<?=$banner['sort']??''?>" type="number" required>
                        </div>
                    </div>
                    <div class="itme form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">显示状态</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                                <label>
                                    <input name="status" type="checkbox" class="js-switch" <?=$banner['status'] == '1'?'checked':''?> data-switchery="true" style="display: none;"><small style="left: 12px; transition: background-color 0.4s, left 0.2s; background-color: rgb(255, 255, 255);">
                                </label>
                        </div>
                    </div>
                    <div class="item form-group">
                        <div class="col-md-6 col-md-offset-3">
                            <input type="hidden" name="id" value="<?=$_GET['id']??0?>">
                            <button id="send" type='submit' class="btn btn-success">提交</button>
                            <button id="reset" type="button" class="btn btn-defalut">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $datatime = ($banner['start_time'].' 至 '. $banner['end_time']);?>
<?php if($banner['start_time']): ?>
   <script>
        $(document).ready(function() {
            $('#datatime').html("<?= $datatime ?>");
            $('#start_time').html("<?= $banner['start_time'] ?>");
            $('#end_time').html("<?= $banner['end_time'] ?>");
        });
    </script>
<?php endif; ?>
