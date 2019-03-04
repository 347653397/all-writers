<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//后台管理模块
$route['admin'] = 'Admin/Index';


//用户模块
$route['sendSms']['post'] = 'Api/User/sendSms';                  //获取手机号验证码
$route['bindingMobile']['post'] = 'Api/User/bindingMobile';      //用户绑定手机号
$route['userCenter'] = 'Api/User/userCenter';                    //用户个人中心
$route['applyWithdraw']['post'] = 'Api/User/applyWithdraw';      //用户申请提现
$route['submitFeedback']['post'] = 'Api/User/submitFeedback';    //用户提交建议
$route['toRewardDetails'] = 'Api/User/toRewardDetails';           //被打赏明细
$route['myWallet'] = 'Api/User/myWallet';                          //我的钱包

//订单模块
$route['buyAudio']['post'] = 'Api/Order/buyAudio';    //购买音频
$route['myOrderlist'] = 'Api/Order/myOrderlist';          //我的订单列表
$route['deleteFailOrder'] = 'Api/Order/deleteFailOrder';  //删除未支付成功的订单
$route['reward'] = 'Api/Order/reward';  //对评论或课程打赏

//分享/上传模块
$route['weixinShareConfig'] = 'Api/Weixin/weixinShareConfig';   //微信分享模块
$route['getUploadSign'] = 'Api/Common/getUploadSign';           //文件上传签名-v1.1.0

//评论模块
$route['getContentItems']['post'] = 'Api/Comment/getContentItems';          //获取内容评分项
$route['comment']['post'] = 'Api/Comment/comment';                          //发表评论及打分
$route['likeComment']['post'] = 'Api/Comment/likeComment';                  //评论点赞及取消
$route['myComment']['post'] = 'Api/Comment/myComment';                      //我的评论
$route['audioComment']['post'] = 'Api/Comment/audioComment';                //音频的评论
$route['getCommentReplyList']['post'] = 'Api/Comment/getCommentReplyList';    //获取评论回复详情列表-v1.1.0
$route['commentReply']['post'] = 'Api/Comment/commentReply';                    //评论回复-v1.1.0
$route['commentLikeList']['post'] = 'Api/Comment/commentLikeList';        //获取评论点赞列表-v1.1.0
$route['commentRewardList']['post'] = 'Api/Comment/commentRewardList';    //获取评论打赏列表-v1.1.0

//课程模块
$route['getCourseList']['post'] = 'Api/Course/getCourseList';           //获取课程列表
$route['getCourseInfo']['post'] = 'Api/Course/getCourseInfo';           //获取课程详情
$route['getAudioInfo']['post'] = 'Api/Course/getAudioInfo';             //获取课程详情
$route['savePlayNum']['post'] = 'Api/Setting/savePlayNum';              //保存播放数量
$route['getCourseAddr']['post'] = 'Api/Course/getCourseAddr';           //获取音频播放地址
$route['pushArticle']['post'] = 'Api/Course/pushArticle';               //去投稿
$route['myCourse']['post'] = 'Api/Course/myCourse';                     //我的投稿
$route['delCourse']['post'] = 'Api/Course/delCourse';                   //删除投稿
$route['applyAuction']['post'] = 'Api/Course/applyAuction';             //申请竞拍
$route['takeAuction']['post'] = 'Api/Course/takeAuction';             //参与竞拍

//系统模块
$route['getCategoryList']['post'] = 'Api/Setting/getCategoryList';     //获取首页分类标签
$route['getBannerList']['post'] = 'Api/Banner/getBannerList';     //获取首页广告列表

//数据较正
$route['updateDashangToRedis'] = 'Api/data/updateDashangToRedis';  //更新评论打赏金额到redis

