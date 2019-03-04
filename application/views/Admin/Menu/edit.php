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
                <form class="form-horizontal form-label-left select_from" action="edit" method="post" id="autoForm">
                    <span class="section">编辑菜单</span>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">菜单名称 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="title" class="form-control col-md-7 col-xs-12" name="title" type="text" value="<?=$auth['title']??'';?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">上级菜单<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control" name="pid">
                                    <option value="0" <?php if($auth['pid'] == 0) {echo 'selected="selected"';} ?>>顶级分类</option>
                                    <?php if(!empty($menus)) :?>
                                        <?php foreach ($menus as $menu) :?>
                                            <option <?php if($menu['auth_id'] == $auth['pid']) {echo 'selected="selected"';} ?> value="<?= $menu['auth_id'];?>"><?= $menu['title']??'';?></option>
                                            <?php if($menu['children']) :?>
                                                <?php foreach ($menu['children'] as $child) :?>
                                                        <option <?php if($child['auth_id'] == $auth['pid']) {echo 'selected="selected"';} ?> value="<?php echo $child['auth_id'];?>">&nbsp;&nbsp;&nbsp;┗━
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
                            <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="<?= $auth['name'];?>">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="icon">菜单icon图标
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="icon" name="icon"  value="<?= $auth['icon']??'';?>"  placeholder="fa fa-tachometer" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">排序 <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="sort" name="sort" value="<?= $auth['sort']??'';?>" required="required"  class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="itme form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">显示状态</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="radio" name="status" id="status1" value="1" <?php echo $auth['status'] == 1?"checked":'';?> >显示
                            <input type="radio" name="status" id="status2" value="2">不显示
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="icon" class="control-label col-md-3 col-sm-3 col-xs-12">页面提示</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                             <textarea class="resizable_textarea form-control" name="explain" placeholder="" data-parsley-id="16"><?= trim($auth['explain'])??'';?></textarea>
                        </div>
                    </div>
                    <div class="item form-group">
                        <div class="col-md-6 col-md-offset-3">
                            <input type="hidden" name="id" value="<?=$_GET['id']??''?>">
                            <button id="send" type='submit' class="btn btn-success">编辑</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>