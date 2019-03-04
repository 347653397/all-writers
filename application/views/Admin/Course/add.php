<?php
/**
 * Created by PhpStorm.
 * User: huzhenping 
 * Date: 2018/7/17
 * Time: 20:53
 */
?>
<script src="/public/assets/bootstrap-daterangepicker/daterangepicker.js?ver=0.6" type="text/javascript"></script>
<link rel="stylesheet" href="/public/css/bootstrap-fileupload.css">
<script src="/public/js/bootstrap-fileupload.js"></script>
<link rel="stylesheet" href="/public/css/validate.css">
<style>
    .icheckbox_flat-green{
        margin-top: -2px;
    }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form class="form-horizontal form-label-left select_from" action="add" method="post"  enctype="multipart/form-data" id="autoForm" style="font-size: 15px;font-weight: normal">
                    <span class="section">新增课程</span>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">课程类型</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="up_type" class="form-control" name="up_type" required="">
                                <option value="">请选择..</option>
                                <?php if(!empty(up_type())) :?>
                                    <?php foreach (up_type() as $key => $tag) :?>
                                        <option value="<?= $key;?>"><?= $tag??'';?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                    </div>
                     <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">所属标签</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="cid" class="form-control" name="cid" required="">
                                <option value="">请选择..</option>
                                <?php if(!empty($category)) :?>
                                    <?php foreach ($category as $key =>$tag) :?>
                                        <option value="<?= $key;?>" ><?= $tag??'';?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">标题 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="title" class="form-control col-md-7 col-xs-12" name="course_title" type="text" required>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">排序</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="sort" class="form-control col-md-7 col-xs-12" name="sort" type="number" required>
                        </div>
                    </div>
                    <div class="item form-group lz">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="author">作者 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="author" class="form-control col-md-7 col-xs-12" name="author" type="text" required>
                        </div>
                    </div>
                    <div class="item form-group lz">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">课程简介</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea rows="4" class="resizable_textarea form-control" name="course_brief" placeholder="" data-parsley-id="16"></textarea>
                        </div>
                    </div>
    <!--                 <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">正文详情</label>
                        <div id="div1" class="col-md-6 col-sm-6 col-xs-12"> </div>
                        <textarea id="text1" name="content" style="width:100%; height:200px;" class="col-md-6 col-sm-6 col-xs-12 hidden" ></textarea>
                    </div> -->
                    <div class="item form-group lz">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="big_pic">详情图 (建议尺寸414*198)
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="col-sm-3" style="margin: 0px -10px;">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="height:auto">
                                        <img src="/public/img/no_image.png">
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
                                                <input type="file" class="default" name="big_pic">
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item form-group lz">
                        <div>
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="course_pic">缩略图 (建议尺寸208*180)
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="col-sm-3" style="margin: 0px -10px;">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="height:auto">
                                            <img src="/public/img/no_image.png">
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
                                                    <input type="file" class="default" name="course_pic">
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="itme form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">上架状态</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label>
                                <input name="status" type="checkbox" class="js-switch" checked="" data-switchery="true" style="display: none;"><small style="left: 12px; transition: background-color 0.4s, left 0.2s; background-color: rgb(255, 255, 255);">
                            </label>
                        </div>
                    </div>
                    <div class="item form-group">
                        <div class="col-md-6 col-md-offset-3">
                            <button id="send" type='submit' class="btn btn-success">提交</button>
                            <button id="reset" type="button" class="btn btn-defalut">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
        $("#up_type").change(function(){
            var options=$("#up_type option:selected");  //获取选中的项
            var val = options.val();
            if(val == '1'){
                var url = '/Admin/Audio/add';
                window.location.href=url;
                //$('.lz').hide();
                //$('.select_from').validate({ignore: ":hidden"});      
            }else{
                $('.lz').show(); 
            }
        });
</script>