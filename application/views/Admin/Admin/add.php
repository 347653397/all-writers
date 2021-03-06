
<link rel="stylesheet" href="/public/css/bootstrap-fileupload.css">
<script src="/public/js/bootstrap-fileupload.js"></script>
<link rel="stylesheet" href="/public/css/validate.css">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
				<form class="form-horizontal form-label-left" action="add" method="post" id="addUser" enctype="multipart/form-data">
					<span class="section">添加用户</span>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">用户名 <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input id="username" class="form-control col-md-7 col-xs-12" name="username" type="text"  required>
						</div>
					</div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">密码 <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="password" class="form-control col-md-7 col-xs-12" name="password" type="password"  required>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_pic">头像
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
												<i class="fa fa-paper-clip"></i> 选择头像
											</span>
											<span class="fileupload-exists">
												<i class="fa fa-undo"></i> 重置
											</span>
											    <input type="file" class="default" name="head_pic">
											</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="icon">角色</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control valid" name="role_id" aria-invalid="false">
								<?php if(!empty($roles)) :?>
									<?php foreach ($roles as $role) :?>
                                        <option value="<?= $role['role_id']??'';?>" ><?= $role['role_name']??'';?></option>
									<?php endforeach;?>
								<?php endif;?>
                            </select>
                        </div>
                    </div>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="icon">性别
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control valid" name="sex" aria-invalid="false">
                                    <?php if(!empty($selects)) :?>
                                        <?php foreach ($selects as $select) :?>
                                            <option value="<?= $select['status']??'';?>" <?php if($admin['sex'] == $select['status']) {echo 'selected="selected"';} ?>><?= $select['msg']??'';?></option>
										<?php endforeach;?>
									<?php endif;?>
                                </select>
						</div>
					</div>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">手机
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="number" id="phone" name="phone" value="" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">邮箱
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="email" id="email" name="email" value="" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
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