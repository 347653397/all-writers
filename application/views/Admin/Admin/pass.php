<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
				<form class="form-horizontal form-label-left" action="changePass" method="post" id="changepwdForm" enctype="multipart/form-data">
					<span class="section">修改密码</span>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="old_pass">原密码 <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input id="old_pass" class="form-control col-md-7 col-xs-12" name="old_pass" type="password" required>
						</div>
					</div>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_pass">新密码 <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input id="new_pass" class="form-control col-md-7 col-xs-12" name="new_pass" type="password" required>
						</div>
					</div>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="confirm_pass">确认密码 <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input id="confirm_pass" class="form-control col-md-7 col-xs-12" name="confirm_pass" type="password" required>
						</div>
					</div>
					<div class="item form-group">
						<div class="col-md-6 col-md-offset-3">
							<button id="send" type='submit' class="btn btn-success" onclick="">提交</button>
							<button id="reset" type='button' class="btn btn-defalut">重置</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>