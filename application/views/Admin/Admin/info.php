<link rel="stylesheet" href="/public/css/bootstrap-fileupload.css">
<script src="/public/js/bootstrap-fileupload.js"></script>
<link rel="stylesheet" href="/public/css/validate.css">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
				<form class="form-horizontal form-label-left select_from" action="info" method="post" id="autoForm" enctype="multipart/form-data">
					<span class="section">个人资料</span>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">用户名 <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input id="title" class="form-control col-md-7 col-xs-12" name="username" disabled="disabled" type="text" value="<?=$admin['username']??'';?>" required>
						</div>
					</div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="head_pic">头像
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="col-sm-3" style="margin: 0px -10px;">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="height:auto">
                                        <img src="<?= $admin['head_pic']??'/public/img/no_image.png'?>" alt="">
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
							<input type="number" id="phone" name="phone" value="<?= $admin['phone']??'';?>" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">邮箱
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="email" id="email" name="email" value="<?= $admin['email']??'';?>" class="form-control col-md-7 col-xs-12">
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