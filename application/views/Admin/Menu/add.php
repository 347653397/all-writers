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
                <form class="form-horizontal form-label-left select_from" action="add" method="post" id="autoForm">
                    <span class="section">添加菜单</span>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">菜单名称 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="title" class="form-control col-md-7 col-xs-12" name="title" type="text" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">上级菜单<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" name="pid">
                                    <option value="0">顶级分类</option>
                                    <?php if(!empty($menus)) :?>
                                        <?php foreach ($menus as $menu) :?>
                                            <option value="<?= $menu['auth_id'];?>"><?= $menu['title']??'';?></option>
                                            <?php if(isset($menu['children'])) :?>
                                                <?php foreach ($menu['children'] as $child) :?>
                                                        <option value="<?php echo $child['auth_id'];?>">&nbsp;&nbsp;&nbsp;┗━
                                                            <?= $child['title'];?>
                                                        </option>
                                                <?php endforeach;?>
                                            <?php endif;?>
										<?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Controller/Function</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="icon">菜单icon图标
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="icon" name="icon"  placeholder="fa fa-tachometer" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">排序 <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="sort" name="sort" required="required"  class="form-control col-md-7 col-xs-12">
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
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">页面提示</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                             <textarea class="resizable_textarea form-control" name="explain" placeholder="" data-parsley-id="16"></textarea>
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