<?php
/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 2018/07/16
 * Time: 下午9:28
 */


class Course extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Course_model', 'Course');
        $this->load->model('CourseItem_model', 'CourseItem');
        $this->load->model('ContentComment_model', 'Comment');
        $this->load->model('ContentItems_model', 'ContentItems');
        $this->load->model('CourseOrderDetails_Model', 'CourseOrderDetails');
        $this->load->model('CourseOrder_Model', 'CourseOrder');
        $this->load->model('TakeAuction_model', 'TakeAuction');

        $this->load->library('Redislib');
    }

    //获取课程列表
    public function getCourseList()
    {

        $pdata = $this->input->post();
        $page = isset($pdata['page']) ? $pdata['page'] : $this->page;//查询页数
        $pageSize = isset($pdata['pageSize']) ? $pdata['pageSize'] : $this->pageSize;//数量
        $cid = isset($pdata['cid']) ? $pdata['cid'] : '';//分类

        if ($cid == '1') {
            $params = ['course.status' => '1', 'course.is_recommend' => '2']; //条件
            $order = ['course.recommend_time' => 'desc'];
        } elseif ($cid == '5') {
            $datatime = date('Y-m-d');
            $bkey = "RANK_{$datatime}";
            $star = (($page - 1) * $pageSize);
            $end = $pageSize * $page;
            $rank = $this->redislib->zRevRange($bkey, $star, $end);
            $params = ['course.status' => '1', 'course.id' => $rank, 'LENGTH(ti.id) >=' => '1'];
        } else {
            $params = ['course.status' => '1', 'course.cid' => $cid, 'LENGTH(ti.id) >=' => '1'];                        //条件
            $order = ['ti.sort'=>'desc','course.sort' => 'desc'];
        }
        $filed = 'course.sort,course.up_type,course.author,course.id as course_id,course_title,course_brief,course_pic,course.create_time'; //查询字段                      //排序
        $limit = ['page' => $page, 'count' => $pageSize];            //条数
        $join =  ['course_items as ti' => 'ti.course_id = course.id'];  //连表
        $group = ['course.id'];                                     //分组
        //获取课程列表
        $courses = $this->Course->_get($filed, $params, false, $order, $limit, $join, 'left', $group);
        //统计数量
        $count = $this->Course->_count($params, false, '1', $join, 'left');
        $num = 0;
        $comment_count = 0;
        foreach ($courses as $key => &$course) {
            $key = $this->config->item('redis_keys')['playPrex'] . $course['course_id'];
            $courseItem = $this->CourseItem->_getOne('sort,id as item_id,title,audio_pic,author,audio_brief', ['course_id' => $course['course_id']]);
            $course['item_id'] = $courseItem['item_id'];
            if ($course['up_type'] == '1') {
                $course['course_brief'] = $courseItem['audio_brief'];
                $course['course_title'] = $courseItem['title'];
                $course['author'] = $courseItem['author'];
                $course['course_pic'] = $courseItem['audio_pic'];
                $key = $this->config->item('redis_keys')['playPrex'] . $course['item_id'];
                $course['item_sort'] = $courseItem['sort'];
            }
            //播放放数量 单集取音频播放量，连载取课程播放量
            $num = $this->redislib->get($key);
            $course['play_num'] = $num ? $num : '0';//播放数量
            $course['total_duration'] = '6:00';//播放总时长
            $course['total_people'] = $num ? $num : '0';//播放总人数

            $comment_count = $this->Comment->_count('1', ['course_items_id' => $course['item_id'], 'status' => '1']);
            $course['comment_count'] = $comment_count ? $comment_count : '0';

            $course['course_pic'] = isset($course['course_pic']) ? IMG_URL . $course['course_pic'] : '';
            //是否购买
            $total_amount = $this->CourseItem->_getOne('sum(price) as total_amount', ['course_id' => $course['course_id'], 'type' => '1']);
            $course['total_amount'] = $total_amount['total_amount'] ? $total_amount['total_amount'] : '0';//全集金额

        }
        $data['courses'] = $courses;
        $data['count'] = $count;
        /* TODO
        *1 待更新播放数量-得用redis
        */

        $this->responseToJson(200, '请求成功', $data);
    }

    //获取课程详情
    public function getCourseInfo()
    {
        $pdata = $this->input->post();

        if (empty($pdata['course_id'])) {
            $this->responseToJson(502, '参数错误');
        }
        $params = ['id' => $pdata['course_id'], 'status' => '1'];

        //获取课程详情
        $course = $this->Course->_getOne('id as course_id,big_pic,course_title,course_brief,content', $params);
        if (!$course) {
            $this->responseToJson(502, '获取课程信息失败');
        }
        $course['big_pic'] = isset($course['big_pic']) ? IMG_URL . $course['big_pic'] : ''; //拼接图片URL
        //获取课程章节
        $courseItem = $this->CourseItem->_get('audio_brief,auction_money,audio_file_id,auction_status,content,audio_pic as course_pic,audio_duration,sort,price,id as item_id,title,type,course_id', ['course_id' => $pdata['course_id']]);

        $user_id = $this->user_info['id'];
        $course['total_amount'] = 0;
        foreach ($courseItem as &$item) {
            $item['have_audio'] = 1;
            if (!$item['audio_file_id']) { 
                $item['have_audio'] = 2;
            }
            //是否购买
            $oneCourse = $this->CourseOrderDetails->_getOne('id,pay_status', ['course_items_id' => $item['item_id'], 'user_id' => $user_id, 'pay_status' => '2']);
            if (isset($oneCourse['pay_status']) && $oneCourse['pay_status'] == 2) {
                $item['is_buy'] = 2;
            } else {
                $item['is_buy'] = 1;
            }
            $item['course_pic'] = isset($item['course_pic']) ? IMG_URL . $item['course_pic'] : '';
            //计算课程总价格
            if ($item['type'] == '1' && $item['price'] != '0' && $item['is_buy'] == '1') {
                $course['total_amount'] += $item['price'];
            }
        }
        $course['total_amount'] = sprintf("%.2f", $course['total_amount']);
        $data = ['course' => $course, 'course_item' => $courseItem];
        $this->responseToJson(200, '请求成功', $data);
    }

    //获取课程音频播放地址
    public function getCourseAddr()
    {
        $pdata = $this->input->post();
        if (empty($pdata['item_id'])) {
            $this->responseToJson(502, '参数错误');
        }
        if (isset($this->user_info)) {
            $user_id = $this->user_info['id'];
        } else {
            $this->responseToJson(502, '用户信息错误，请重新授权');
        }

        //获取课程章节
        $courseItem = $this->CourseItem->_getOne('type,id as item_id,audio_addr', ['id' => $pdata['item_id']]);
        if ($courseItem['type'] == '1') {
            //未购买不能获取音频地址
            $oneCourse = $this->CourseOrderDetails->_getOne('id,pay_status', ['pay_status' => '2', 'course_items_id' => $pdata['item_id'], 'user_id' => $user_id]);
            if (!$oneCourse) {
                $this->responseToJson(502, '你未购买此音频，请前往购买');
            }
        }
        //生成防盗链url
//        $key = "F77Ri0udBPLpuwYCbmvZ";
        $key = "N99GCeQbX2ncofjXDbow";
        //DIR
        $usr = parse_url($courseItem['audio_addr']);

        $uuu = "http://audio.startechsoft.cn";

        $dir = substr($usr['path'], 0, strrpos($usr['path'], '/') + 1);
        //t
        $t = dechex(time() + (600));
        //EXPER
        $exper = '';
        //US
        $us = rand(1, 1000);
        $sign = md5($key . $dir . $t . $exper . $us);

        $courseItem['audio_addr'] = $uuu . $usr['path'] . "?t={$t}&exper=&us={$us}&sign={$sign}";

//        $courseItem['audio_addr'] = $courseItem['audio_addr']."?t={$t}&exper=&us={$us}&sign={$sign}";

        $this->responseToJson(200, '请求成功', $courseItem);
    }

    //获取音频单集列表
    public function getAudiolist()
    {
        $pdata = $this->input->post();

        $page = isset($pdata['page']) ? $pdata['page'] : $this->page;//查询页数
        $pageSize = isset($pdata['pageSize']) ? $pdata['pageSize'] : $this->pageSize;//数量

        //获取单集列表
        $filed = 'id as item_id,author,price,title as course_title,audio_pic as course_pic,create_time,content'; //查询字段
        $params = ['status' => '1', 'is_recommend' => '2']; //条件
        $order = ['course_items.sort' => 'desc'];                       //排序
        $limit = ['page' => $page, 'count' => $pageSize];            //条数
        //获取单集课程列表
        $audios = $this->Course->_get($filed, $params, false, $order, $limit);
        //统计数量
        $count = $this->CourseItem->_count($params);
        foreach ($audios as $key => &$audio) {
            $key = $this->config->item('redis_keys')['playPrex'] . $audio['item_id'];
            $audio['play_num'] = $this->redislib->get($key);;//播放数量
            $comment_count = $this->Comment->_count('1', ['course_items_id' => $audio['item_id']]);
            $audio['comment_count'] = $comment_count ? $comment_count : '0';
            $audio['course_pic'] = isset($audio['course_pic']) ? IMG_URL . $audio['course_pic'] : '';
        }

        $data['courses'] = $audios;
        $data['count'] = $count;

        $this->responseToJson(200, '请求成功', $data);

    }

    //获取音频单集详情
    public function getAudioInfo()
    {
        $item_id = $this->input->post('item_id');
        $in_type = $this->input->post('in_type');

        if (empty($item_id)) {
            $this->responseToJson(502, '参数错误');
        }

        if ($in_type == 2) {
            $params = ['id' => $item_id, 'user_id' => $this->user_info['id']];
        } else {
            $params = ['id' => $item_id, 'status' => '1'];
        }

        //获取音频详情
        $audio = $this->CourseItem->_getOne('auction_money,audio_file_id,auction_status,audio_brief,is_original,author,type,price,audio_duration,id as item_id,audio_pic,title,content', $params);


        if (!$audio) {
            $this->responseToJson(502, '获取课程信息失败');
        }
        $audio['audio_pic'] = isset($audio['audio_pic']) ? IMG_URL . $audio['audio_pic'] : ''; //拼接图片URL

        $audio['have_audio'] = 1;
        if (!$audio['audio_file_id']) {
            $audio['have_audio'] = 2;
        }

        $user_id = $this->user_info['id'];
        //是否购买
        $oneCourse = $this->CourseOrderDetails->_getOne('id,pay_status', ['course_items_id' => $audio['item_id'], 'user_id' => $user_id, 'pay_status' => '2']);

        if (isset($oneCourse['pay_status']) && $oneCourse['pay_status'] == 2) {
            $audio['is_buy'] = 2;
        } else {
            $audio['is_buy'] = 1;
        }
        //播放量
        $key = $this->config->item('redis_keys')['playPrex'] . $audio['item_id'];
        $audio['play_num'] = $this->redislib->get($key);//播放数量
        //购买金额
        $key = $this->config->item('redis_keys')['audioToReward'] . $audio['item_id'];
        $reward_amount = $this->redislib->get($key);
        $audio['reward_amount'] = $reward_amount ? $reward_amount : 0;
        $audio['buy_num'] = $this->CourseOrderDetails->_count(['course_items_id' => $audio['item_id'], 'pay_status' => 2]);

        $this->responseToJson(200, '请求成功', ['audio' => $audio]);
    }

    //发布文章投稿
    public function pushArticle()
    {
        $pdata = $this->input->post();
        $pdata['type'] = '2'; //收费模式
        if ($pdata['price'] > 0) {
            $pdata['type'] = '1';
        }
        $pdata['is_tg'] = 2;//投稿文章
        $pdata['status'] = 2;    //是否上架
        $pdata['create_time'] = time();    //创建时间
        $pdata['update_time'] = time();    //更新时间
        $pdata['user_id'] = $this->user_info['id']; //获取用户ID
        $pdata['audit_status'] = 1;    //文章审核状态
        $pdata['auction_status'] = 1;    //竞拍状态
        $pdata['sort'] = 1;    //排序

        $parme = ['update_time'=>time(),'create_time'=>time(),'is_tg'=>'2','course_title' => $pdata['title'], 'up_type' => '1', 'status' => '2', 'sort' => '1', 'cid' => '4'];

        if ($pdata['item_id'] && $pdata['item_id'] > 1) {
            $item_id = $pdata['item_id'];
            unset($pdata['item_id']);

            $iteminfo = $this->CourseItem->_getOne('*', ['id' => $item_id]);
            $this->Course->editCourse($parme, ['id' => $iteminfo['course_id']]);    //编辑课程
            $this->CourseItem->editCourseItem($pdata,['id' => $item_id]);

            $this->responseToJson(200, '编辑文章成功');
        } else {
            unset($pdata['item_id']);
            $pdata['course_id'] = $this->Course->addCourse($parme);    //添加课程
            $this->CourseItem->addCourseItem($pdata);
            $this->responseToJson(200, '发布文章成功');
        }

    }

    /**
     * 我的投稿及拍卖
     */
    public function myCourse()
    {
        //分页
        $page = $this->input->post('page');
        $pageSize = $this->input->post('pageSize');
        $type = $this->input->post('type') ?? '1';

        if (!$page || !$pageSize || !$type) {
            $this->responseToJson(502, '参数错误');
        }
        //条件
        $params = [];
        if ($type == 2) {
            $params['auction_status !='] = 1;
        }
        $params['user_id'] = $this->user_info['id'];

        $order = ['id' => 'desc'];  //排序
        $limit = ['page' => $page, 'count' => $pageSize];            //条数
        $courses = $this->CourseItem->_get('*', $params, false, $order, $limit);
        $data['count'] = $this->CourseItem->_count($params);
        foreach ($courses as &$course) {
            $course['up_type'] = '1';
            if ($course['audio_file_id'] == '') {
                $course['audio_duration'] = 0;
            }
            $course['audio_pic'] = !empty($course['audio_pic']) ? IMG_URL . $course['audio_pic'] : '';
        }
        $data['courses'] = $courses;

        //红点处理
        $key = $this->config->item('redis_keys')['contributeDotPrex'] . $this->user_info['id'];
        if ($type == 2) {
            $key = $this->config->item('redis_keys')['auctionDotPrex'] . $this->user_info['id'];
        }
        $this->redislib->del($key);

        $this->responseToJson(200, '获取成功', $data);
    }

    //删除我的投稿
    public function delCourse()
    {
        $pdata = $this->input->post();
        $id = $pdata['item_id'] ?? '0';
        if ($id < 1) {
            $this->responseToJson(502, '参数错误');
        }

        $audioInfo = $this->CourseItem->_getOne('*', ['user_id' => $this->user_info['id'], 'id' => $id]);
        if (!$audioInfo) {
            $this->responseToJson(502, '该记录不存在');
        }
        $res = $this->CourseItem->delAudio($id);
        if ($res == false) {
            $this->responseToJson(502, '删除音频失败');
        } else {
            $this->Course->delCourse($audioInfo['course_id']);
            $this->responseToJson(200, '删除音频成功');
        }
    }

    //申请竞拍
    public function applyAuction()
    {
        $item_id = $this->input->post('item_id');
        if (!$item_id) {
            $this->responseToJson(502, '参数错误');
        }

        $audioInfo = $this->CourseItem->_getOne('*', [
            'user_id' => $this->user_info['id'], 'id' => $item_id
        ]);
        if (!$audioInfo) {
            $this->responseToJson(502, '该记录不存在');
        } elseif ($audioInfo['audit_status'] != 2) {
            $this->responseToJson(502, '该文章未审核通过');
        }

        if ($this->CourseItem->_update(['auction_status' => 2, 'update_time' => time()],
            ['id' => $item_id])) {
            $this->responseToJson(200, '操作成功');
        } else {
            $this->responseToJson(502, '操作失败');
        }
    }

    //参与竞拍
    public function takeAuction()
    {
        $item_id = $this->input->post('item_id');
        if (!$item_id) {
            $this->responseToJson(502, '参数错误');
        }

        $audioInfo = $this->CourseItem->_getOne('*', [
            'user_id' => $this->user_info['id'], 'id' => $item_id
        ]);
        if (!$audioInfo) {
            $this->responseToJson(502, '该记录不存在');
        } elseif ($audioInfo['audit_status'] != 2) {
            $this->responseToJson(502, '该文章未审核通过');
        }
        $params = ['user_id'=>$this->user_info['id'],'course_items_id'=>$item_id,'created_at'=>time()];
        if ($this->TakeAuction->_add($params)){
            $this->responseToJson(200, '参与竞拍成功');
        } else {
            $this->responseToJson(502, '操作失败');
        }
    }

}