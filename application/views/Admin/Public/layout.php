<!DOCTYPE html>
<html lang="en">
<!--header-->
<?= $this->load->view('Public/header','',true);?>
<body class="nav-md">
<div class="container body">
	<div class="main_container">
		<div class="col-md-3 left_col">
			<div class="left_col scroll-view">
				<div class="clearfix"></div>
				<!-- menu profile quick info -->
				<div class="profile clearfix">
					<div class="profile_pic">
						<img src="<?= $admin['head_pic']??'/public/img/no_image.png';?>" alt="..." class="img-circle profile_img">
					</div>
					<div class="profile_info">
						<span>Welcome,</span>
						<h2><?=$admin['username']??'';?></h2>
					</div>
				</div>
				<!-- /menu profile quick info -->
                <br />
                <!-- sidebar menu -->
				<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <ul class="nav side-menu">
                            <?php if(!empty($menus)) :?>
                                <?php foreach ($menus as $menu) :?>
                                    <li <?php if($menu['auth_id'] == 1) {echo "style='border-right:0px solid #1ABB9C'";} ?>>
                                        <a <?php if($menu['auth_id'] == 1) {echo "href='/Admin/index'";} ?>><i class="<?=$menu['icon']??''?>"></i> <?=$menu['title']??''?> <span class="<?=$menu['icon']??''?>"></span></a>
										<?php if(isset($menu['children'])) :?>
                                            <ul class="nav child_menu">
                                                <?php foreach ($menu['children'] as $child) :?>
                                                        <li><a href="/Admin/<?=$child['name']??''?>"><?=$child['title']??''?></a></li>
                                                <?php endforeach;?>
                                            </ul>
										<?php endif;?>
                                    </li>
                                <?php endforeach;?>
                            <?php endif;?>
                        </ul>
                    </div>
				</div>
				<!-- /sidebar menu -->
			</div>
		</div>

		<!-- top navigation -->
        <div class="nav_menu" style="background: #72d0eb">
            <nav>
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars" style="color: #FFF"></i></a>
                </div>
                <div style="float: left;padding-top: 11px;font-size:24px;">
                    <a href="" style="color: #FFF;margin-left:-15px;">人人编剧</a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li class="">
                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="<?= $admin['head_pic'] ?>" alt="">
                            <?= $admin['username'] ?>
                            <span class=" fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                            <li>
                                <a href="/Admin/Admin/info">
                                    <span>设置</span>
                                </a>
                            </li>
                            <li><a href="/Admin/Login/logout"><i class="fa fa-sign-out pull-right"></i> 退出</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
		<!-- /top navigation -->
		<!-- page content -->
        <div class="right_col" role="main">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <ul class="breadcrumb" style="background-color:#c9cccf;margin-bottom:10px;border-radius:6px;">
                            <li>
                                <a href="/Admin/Index/"><i class="fa fa-home"></i>首页</a>
                            </li>
                            <?php if(!empty($current)) :?>
                                <?php if($current['ptitle']) :?>
                                    <li>»</li>
                                    <li>
                                        <a href="<?php if(empty($current['pname']))
                                            {echo 'javascript:void(0);';}
                                        else
                                            {echo "/Admin/{$current['pname']}";}
                                            ;?>">
                                            <?=$current['ptitle']?>
                                            "
                                        </a>
                                    </li>
								<?php endif;?>
                                <li>»</li>
                                <li class="active"><?=$current['title']?></li>
                            <?php endif;?>
                        </ul>
                        <?php if(!empty($current['explain'])) :?>
                            <div class="alert alert-success alert-dismissible fade in" style="background-color:#dff0d8;color:#3c763d;border: 0px solid transparent;margin-bottom:15px;" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">
                                    <i class="fa fa-times"></i>
                                </button>
                                <span style="color: #3c763d"><?= $current['explain']??'';?></span>
                            </div>
						<?php endif;?>
                    </div>
                </div>
			<?= $content??'';?>
        </div>
		<footer style="background: #5b6e84;color: white">
			<div class="text-center">
				<?= $setting['footer']['value']??'Copyright © 2017 <a href="" target="_blank" style="color: #72d0eb;font-size: 16px;">97Admin</a> design by symphp'; ?>
			</div>
			<div class="clearfix"></div>
		</footer>
		<!-- /footer content -->
		<!-- /page content -->
<!--		footer-->
		<?= $this->load->view('Public/footer','',true);?>
</body>
</html>