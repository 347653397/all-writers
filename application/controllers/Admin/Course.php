<?php

/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 10/17
 * Time: 13:40
 * @property Course_model Course
 */
class Course extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Course_model','Course');
        $this->load->model('CourseItem_model','CourseItem');
        $this->load->model('ContentItems_model','ContentItems');
        $this->load->model('Category_model','Category');
        $this->load->model('Audio_model','Audio');
        $this->load->library('Coslib');
    }

    /**
     * 课程列表
     */
    public function index()
    {
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : $this->page;
        $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] :$this->pageSize;
        //参数
        $course_title = trim($this->input->get('course_title'));    //标题
        $author = trim($this->input->get('author'));    //作者
        $cid = trim($this->input->get('cid'));    //分类ID
        $up_type = trim($this->input->get('up_type'));    //手机号

        //条件
        $params = [];
        if($course_title){
            $params['course_title'] = $course_title;  //标题
        }
        if($author){
            $params['author'] = $author;  //作者
        }
        if($cid){
            $params['cid'] = $cid;  //分类ID
        }
        if($up_type){
            $params['up_type'] = $up_type;//类型
        }
        $params['up_type'] = '2';//投稿类型
        $order = ['course.id'=>'desc'];  //排序
        $limit = ['page'=>$page,'count'=>$pageSize];            //条数
        $courses = $this->Course->_get('*',$params,false,$order,$limit);
        $data['count'] = $this->Course->_count('*',$params);
        //分类
        $category = $this->Category->_get('cat_name,id');
        $category = array_column($category,'cat_name','id');
        $data['category'] = $category;
        //更新状态
        foreach ($courses as &$course){
            if($course['up_type'] == '1'){
                $info = $this->CourseItem->_getOne('audio_pic as course_pic,title as course_title,author',['course_id'=>$course['id']]);
                $course['course_pic'] = $info['course_pic'];
                $course['course_title'] = $info['course_title'];
                $course['author'] = $info['author'];
            }
            $course['up_type'] = up_type()[$course['up_type']]??'';
            $course['category'] = isset($category[$course['cid']])?$category[$course['cid']]:'未分类';
            $course['course_pic'] = !empty($course['course_pic'])?IMG_URL.$course['course_pic']:'';
        }
        $data['courses'] = $this->changeMsg($courses);  
        //生成分页
        $data['page'] = $this->pageInit($data['count'], $this->pageSize);
        $this->display('Course/index',$data);
    }

    /**
     * 课程列表
     */
    public function index2()
    {
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : $this->page;
        $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] :$this->pageSize;
        //参数
        $course_title = trim($this->input->get('course_title'));    //标题
        $author = trim($this->input->get('author'));    //作者

        $pgtype = trim($this->input->get('pgtype'))??'tg'; //查看类型 tg=投稿 jp=竞拍

        //条件
        $params = [];
        if($course_title){
            $params['title'] = $course_title;  //标题
        }
        if($author){
            $params['author'] = $author;  //作者
        }
        $params['is_tg'] = '2';  //投稿类型
        if($pgtype =='jp'){
            $params['auction_status !='] = '1';  //竞拍
        }
        $order = ['id'=>'desc'];  //排序
        $limit = ['page'=>$page,'count'=>$pageSize];            //条数
        $courses = $this->CourseItem->_get('*',$params,false,$order,$limit);
        $data['count'] = $this->CourseItem->_count($params);
        //分类
        $category = $this->Category->_get('cat_name,id');
        $category = array_column($category,'cat_name','id');
        $data['category'] = $category;
        //更新状态
        foreach ($courses as &$course){
            $info = $this->Course->_getOne('up_type,cid',['id'=>$course['course_id']]);
            $course['up_type'] = up_type()[$info['up_type']]??'';
            $course['course_title'] = $course['title'];
            $course['category'] = isset($category[$info['cid']])?$category[$info['cid']]:'未分类';
            $course['course_pic'] = !empty($course['audio_pic'])?IMG_URL.$course['audio_pic']:'';
            $course['up_type'] = up_type()[$info['up_type']]??'';
            if($course['audit_status'] == '2'){
                $course['audit_status'] ='审核成功';
            }elseif($course['audit_status'] == '3'){
                $course['audit_status'] ='审核失败';
            }else{
                $course['audit_status'] ='待审核';
            }
        }
        $data['courses'] = $this->changeMsg($courses);
        //生成分页
        $data['page'] = $this->pageInit($data['count'], $this->pageSize);

        $this->display('Course/index2',$data);
    }

    /**
     * 添加课程
     */
    public function add()
    {
        if (IS_POST) {
            $pdata = $this->input->post();
            $pdata['status']    = $this->input->post('status') == 'on'?1:2;    //是否上架
            $pdata['create_time']  = time();    //创建时间
            $pdata['update_time']  = time();    //更新时间
            if(!empty($_FILES)){
                $imgarr = $this->coslib->cos_upload($_FILES);  //cos图片上传
                $pdata = array_merge($pdata,$imgarr);
            }
            $res = $this->Course->addCourse($pdata);    //添加课程
            if ($res == false) {
                $error['msg'] = '添加课程失败';
                return $this->error($error);
            } else {
                $success['msg'] = '添加课程成功';
                $success['url'] = 'index';
                return $this->success($success);
            }
        } else {
            //分类
            $category = $this->Category->_get('cat_name,id',['status'=>'1']);
            $data['category'] = array_column($category,'cat_name','id');
            $this->display('Course/add',$data);
        }
    }

    /**
     * 删除课程
     */
    public function del()
    {
        $id = $this->input->get('id')??0;
        if ($id < 1) {
            $error['msg'] = '参数不正确，删除失败！';
            return $this->error($error);
        }
        $res = $this->Course->delCourse($id);
        if ($res == false) {
            $error['msg'] = '删除课程失败';
            return $this->error($error);
        } else {
            $success['msg'] = '删除课程成功';
            $success['url'] = 'index';
            return $this->success($success);
        }
    }

    /**
     * 编辑课程
     */
    public function edit()
    {

        if (IS_POST) {
            $pdata = $this->input->post();
            $pdata['status']    = $this->input->post('status') == 'on'?1:2;    //是否上架
            $pdata['update_time']  = time();    //更新时间
            if(!empty($_FILES)){
                $imgarr = $this->coslib->cos_upload($_FILES);  //cos图片上传
                $pdata = array_merge($pdata,$imgarr);
            }
            //自动修改排序值
            $course = $this->Course->_getOne('sort',['id'=>$pdata['id']]);
            if(!$course && $course['sort'] != $pdata['sort']){
                $course_sort = $this->Course->_getOne('sort',['sort'=>$pdata['sort'],'cid'=>$pdata['cid']]);
                if($course_sort){
                    $this->db->query("update t_course set sort = sort - 1 where sort <= {$pdata['sort']} and cid = {$pdata['cid']}");
                }
            }
            $res = $this->Course->editCourse($pdata,['id'=>$pdata['id']]);    //编辑课程
            if ($res == false) {
                $error['msg'] = '编辑课程失败';
                return $this->error($error);
            } else {
                $success['msg'] = '编辑课程成功';
                $success['url'] = 'index';
                return $this->success($success);
            }
        } else {
            $id = $this->input->get('id')??0;
            $course = $this->Course->_getOne('*',['id'=>$id]);    //课程信息
            if ($course == false) {
                $error['msg'] = '编辑课程失败';
                return $this->error($error);
            }
            $course['big_pic'] = !empty($course['big_pic'])?IMG_URL.$course['big_pic']:'';
            $course['course_pic'] = !empty($course['course_pic'])?IMG_URL.$course['course_pic']:'';

            $data['course'] = $course;
            //分类
            $category = $this->Category->_get('cat_name,id');
            $data['category'] = array_column($category,'cat_name','id');
//            dump($data);exit;
            $this->display('Course/edit',$data);
        }
    }

    /**
     * 推荐课程
     */
    public function recommend()
    {
        $id = $this->input->get('id')??0;
        $is_recommend = $this->input->get('is_recommend')??1;
        if ($id < 1) {
            $error['msg'] = '参数不正确，删除失败！';
            return $this->error($error);
        }
        $time =time();
        $res = $this->Course->_update(['recommend_time'=>$time,'is_recommend'=>$is_recommend],['id'=>$id]);
        if ($res == false) {
            $error['msg'] = '操作失败';
            return $this->error($error);
        } else {
            $success['msg'] = '操作成功';
            $success['url'] = 'index';
            return $this->success($success);
        }
    }

    /**
     * 课程音频列表
     */
    public function audioList()
    {
        //参数
        $course_id = trim($this->input->get('course_id'));    //标题
        $kc = trim($this->input->get('kc'));    //空课程音频
        //条件
        $params = [];
        if($course_id){
            $params['course_id'] = $course_id;  //课程ID
        }
        if($kc){
            $params['course_id'] = '';  //课程ID
        } 
        $order = ['sort'=>'desc'];  //排序
        $audios = $this->Audio->_get('*',$params,false,$order);
        //课程列表
        $courses = $this->Course->_get('id,course_title', ['status' => '1']);
        $courses = array_column($courses, 'course_title', 'id');
        foreach ($audios as &$audio){
            $audio['create_time'] = date('Y-m-d H:i:s',$audio['create_time']);
            $audio['course_title'] = isset($courses[$audio['course_id']]) ? $courses[$audio['course_id']] : '';
        }
        $data['audios'] = $this->changeMsg($audios);
        $this->display('Course/audio',$data);
    }


    /**
     * 音频绑定到课程
     */
    public function bind()
    {
        $audio_id = $this->input->get('audio_id')??0;
        $course_id = $this->input->get('course_id')??0;
        if ($audio_id < 1 || $course_id < 1) {
            $error['msg'] = '参数不正确，删除失败！';
            return $this->error($error);
        }

        $res = $this->Audio->_update(['course_id'=>$course_id],['id'=>$audio_id]);
        if ($res == false) {
            $error['msg'] = '绑定失败';
            return $this->error($error);
        } else {
            $success['msg'] = '绑定成功';
            $success['url'] = 'index';
            return $this->success($success);
        }
    }

    /**
     * 审核投稿
     */
    public function verify()
    {
        $id = $this->input->post('id')??0;
        $remark = $this->input->post('remark')??'';
        $type = $this->input->post('type')??'';
        if ($id < 1) {
            $error['msg'] = '参数不正确，删除失败！';
            return $this->error($error);
        }
        if($type == '2'){
            $auction_status = '3';
            $status = '2';
        }else{
            $auction_status = '2';
            $status = '1';
        }
        $res = $this->CourseItem->_update(['audit_fail'=>$remark,'status'=>$status,'audit_status'=>$auction_status],['id'=>$id]);
        if ($res == false) {
            $this->responseToJson(0, '操作失败');
        } else {
            $course = $this->CourseItem->_getOne('course_id',['id'=>$id]);
            $this->Course->_update(['status'=>$status],['id'=>$course['course_id']]);
            $this->responseToJson(1, '操作成功');
        }
    }

    /**
     * 审核拍卖
     */
    public function verifyAuction()
    {
        $id = $this->input->post('id')??0;
        $remark = $this->input->post('remark')??'';
        $type = $this->input->post('type')??'';
        if ($id < 1) {
            $error['msg'] = '参数不正确，删除失败！';
            return $this->error($error);
        }
        if($type == '2'){
            $auction_status = '3';
        }else{
            $auction_status = '4';
        }
        $res = $this->CourseItem->_update(['audit_fail'=>$remark,'auction_status'=>$auction_status],['id'=>$id]);
        if ($res == false) {
            $this->responseToJson(0, '操作失败');
        } else {
            $this->responseToJson(1, '操作成功');
        }
    }


    /**
     *拍卖信息
     */
    public function auction()
    {
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : $this->page;
        $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] :$this->pageSize;
        //参数
        $course_title = trim($this->input->get('course_title'));    //标题
        $author = trim($this->input->get('author'));    //作者

        //条件
        $params = [];
        if($course_title){
            $params['title'] = $course_title;  //标题
        }
        if($author){
            $params['author'] = $author;  //作者
        }
        $params['is_tg'] = '2';  //分类ID

        $params['auction_status !='] = '1';  //竞拍

        $order = ['id'=>'desc'];  //排序
        $limit = ['page'=>$page,'count'=>$pageSize];            //条数
        $courses = $this->CourseItem->_get('*',$params,false,$order,$limit);
        $data['count'] = $this->CourseItem->_count($params);
        //分类
        $category = $this->Category->_get('cat_name,id');
        $category = array_column($category,'cat_name','id');
        $data['category'] = $category;
        //更新状态
        foreach ($courses as &$course){
            $info = $this->Course->_getOne('up_type,cid',['id'=>$course['course_id']]);
            $course['up_type'] = up_type()[$info['up_type']]??'';
            $course['course_title'] = $course['title'];
            $course['category'] = isset($category[$info['cid']])?$category[$info['cid']]:'未分类';
            $course['course_pic'] = !empty($course['audio_pic'])?IMG_URL.$course['audio_pic']:'';
            $course['up_type'] = up_type()[$info['up_type']]??'';
            switch ($course['auction_status']){
                case 2:
                    $course['auction_status'] = '待审核';
                    break;
                case 3:
                    $course['auction_status'] = '审核失败';
                    break;
                case 4:
                    $course['auction_status'] = '竞拍中';
                    break;
                case 5:
                    $course['auction_status'] = '竞拍结束';
                    break;
                default:
                    break;
            }
        }
        $data['courses'] = $this->changeMsg($courses);
        //生成分页
        $data['page'] = $this->pageInit($data['count'], $this->pageSize);

        $this->display('Course/index3',$data);
    }



}


