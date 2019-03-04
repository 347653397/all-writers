<?php
/**
 * Created by PhpStorm.
 * User: huzhenping 
 * Date: 2018/7/17
 * Time: 20:53
 */
?>
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
                    <span class="section">新增音频</span>
                    <div class="item form-group">
                        <div>
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="audio_pic">缩略图 (建议尺寸208*180)
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
                                                    <input type="file" class="default" name="audio_pic">
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="audio_file">音频文件(注意，此处不能上传图片文件)</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="col-sm-3" style="margin: 0px -10px;">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="height: 50px; width: 300px;">
                                        <img src="/public/img/audio.png">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="height: 50px; width: 300px;">
                                    </div>
                                        <span class="btn btn-white btn-file" style="border-color: rgba(150,160,180,0.3)">
                                            <span class="fileupload-new">
                                                <i class="fa fa-paper-clip"></i> 选择文件
                                            </span>
                                            <span class="fileupload-exists">
                                                <i class="fa fa-undo"></i> 重置
                                            </span>
                                                <input type="file" class="default" name="audio_file">
                                        </span>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">所属标签</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="cid" class="form-control" name="cid">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">音频标题 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="title" class="form-control col-md-7 col-xs-12" name="title" type="text" required>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="author">作者 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="author" class="form-control col-md-7 col-xs-12" name="author" type="text" required>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">是否原创</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="is_original" class="form-control" name="is_original" required="">
                                <option value="">请选择..</option>
                                <?php if(!empty(is_original())) :?>
                                    <?php foreach (is_original() as $key => $tag) :?>
                                        <option value="<?= $key;?>"><?= $tag??'';?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">是否试听</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select  id ='type' class="form-control" name ="type" required="">
                                <option value="">请选择..</option>
                                <option value="1" >收费</option>
                                <option value="2" >试听</option>
                            </select>
                        </div>
                    </div>
                    <div class="item form-group dj">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">单价 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input  class="form-control col-md-7 col-xs-12" name="price" value="<?=$audio['price']??''?>" type="text" required>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">音频简介</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea rows="4" class="resizable_textarea form-control" name="audio_brief" placeholder="" data-parsley-id="16"></textarea>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">正文详细</label>
                        <div id="div1" class="col-md-6 col-sm-6 col-xs-12"> </div>
                        <textarea id="text1" name="content" style="width:100%; height:200px;" class="col-md-6 col-sm-6 col-xs-12 hidden" ></textarea>
                    </div>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">排序</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="sort" class="form-control col-md-7 col-xs-12" name="sort" type="number" required>
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
<script type="text/javascript" src="/public/js/wangEditor.min.js"></script>
<script type="text/javascript">
        var E = window.wangEditor
        var editor = new E('#div1')
        var $text1 = $('#text1')
        editor.customConfig.onchange = function (html) {
            // 监控变化，同步更新到 textarea
            $text1.val(html)
        }
        //配置图片服务器端地址
        editor.customConfig.uploadImgServer = '/Admin/Audio/editorUpload';
        editor.customConfig.uploadFileName = 'upload';
        editor.customConfig.uploadImgHooks = {
            before: function (xhr, editor, files) {},
            success: function (xhr, editor, result) {},
            fail: function (xhr, editor, result) {},
            error: function (xhr, editor) {},
            timeout: function (xhr, editor) {},
            customInsert: function (insertImg, result) {
                console.log(result);
                if(result.status){
                    var url = '<?php echo IMG_URL; ?>'+result.data;
                    insertImg(url);
                }
            }
        }

        editor.create()
        // 初始化 textarea 的值
        $text1.val(editor.txt.html())
</script>
<script type="text/javascript">
        $("#type").change(function(){
            var options=$("#type option:selected");  //获取选中的项
            var val = options.val();
            if(val == '2'){
                $('.dj').hide();
                $('.select_from').validate({ignore: ":hidden"});      
            }else{
                $('.dj').show(); 
            }
        });
</script>