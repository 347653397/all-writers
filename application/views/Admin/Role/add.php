<?php
/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/8/2
 * Time: 20:53
 */
?>
<style>
    .icheckbox_flat-green{
        margin-top: -2px;
    }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form class="form-horizontal form-label-left select_from" action="add" method="post" id="autoForm" style="font-size: 15px;font-weight: normal">
                    <span class="section">添加角色</span>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">角色名称 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="title" class="form-control col-md-7 col-xs-12" name="role_name" type="text" required>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">角色说明</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea class="resizable_textarea form-control" name="explain" placeholder="" data-parsley-id="16"></textarea>
                        </div>
                    </div>
                    <div class="itme form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">显示状态</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                                <label>
                                    <input name="status" type="checkbox" class="js-switch" checked="" data-switchery="true" style="display: none;"><small style="left: 12px; transition: background-color 0.4s, left 0.2s; background-color: rgb(255, 255, 255);">
                                </label>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">权限选择</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php if($menus) :?>
                                <?php foreach ($menus as $menu) :?>
                                    <div class="panel panel-success">
                                        <div class="panel-heading form-inline">
                                            <label style="margin-left: 15px;" class="father">
                                                <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" value="<?= $menu['auth_id']??0;?>" name="role[]" class="flat father" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                                <span class="lbl"> <?= $menu['title']??'';?> </span>
                                            </label>
                                            <span class="tools pull-right">
										        <a href="javascript:;" class="fa fa-chevron-down"></a>
									        </span>
                                        </div>
                                        <?php if(isset($menu['children'])) :?>
                                            <div class="panel-body panel-border">
                                                <?php foreach ($menu['children'] as $child) :?>
                                                        <label style="margin-left: 15px;" class="son">
                                                            <div class="icheckbox_flat-green hover" style="position: relative;"><input value="<?= $child['auth_id']??0;?>" name="role[]" type="checkbox" class="flat children" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                                            <span class="lbl"> <?= $child['title']??'';?></span>
                                                        </label>
                                                        <?php if(isset($child['children'])) :?>
                                                            <?php foreach ($child['children'] as $chi) :?>
                                                            <label style="margin-left: 15px;" class="son">
                                                                <div class="icheckbox_flat-green hover" style="position: relative;"><input value="<?= $chi['auth_id']??0;?>" name="role[]" type="checkbox" class="flat children" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                                                <span class="lbl"> <?= $chi['title']??'';?></span>
                                                            </label>
                                                            <?php endforeach;?>
                                                        <?php endif;?>
                                                <?php endforeach;?>
                                            </div>
                                        <?php endif;?>
                                    </div>
                                <?php endforeach;?>
                            <?php endif;?>
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