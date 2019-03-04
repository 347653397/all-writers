<?php

/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 10/17
 * Time: 13:40
 * @property Audio_model Audio
 */

class Audio extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Audio_model', 'Audio');
        $this->load->model('Course_model', 'Course');
        $this->load->model('CourseOrderDetails_Model', 'CourseOrderDetails');
        $this->load->library('Coslib');
        $this->load->model('Category_model','Category');
        $this->load->library('Vodlib');
    }

    /**
     * 音频列表
     */
    public function index()
    {
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : $this->page;
        $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : $this->pageSize;
        //参数
        $course_id = $this->input->get('course_id');    //课程ID
        $type = trim($this->input->get('type'));    //收费类型
        $start_time = trim($this->input->get('start_time'));    //开始时间
        $end_time = trim($this->input->get('end_time'));    //结束时间

        //条件
        $parmes = [];
        if ($course_id) {
            $parmes['course_id'] = $course_id;
        }
        if ($type) {
            $parmes['type'] = $type;
        }
        if ($start_time) {
            $parmes['create_time >='] = strtotime($start_time . '00:00:00');
        }
        if ($end_time) {
            $parmes['create_time <='] = strtotime($end_time . '23:59:59');
        }
        $order = ['id' => 'desc'];  //排序
        $limit = ['page' => $page, 'count' => $pageSize];  //条数

        $audios = $this->Audio->_get('*', $parmes, false, $order, $limit);

        $data['count'] = $this->Audio->_count('*');

        //课程列表
        $courses = $this->Course->_get('id,course_title', ['status' => '1']);
        $courses = array_column($courses, 'course_title', 'id');
        $data['courses'] = $courses;
        foreach ($audios as &$audio) {
            $audio['buy_num'] = $this->CourseOrderDetails->_count(['course_items_id' => $audio['id'], 'pay_status' => 2]);
            $audio['create_time'] = date('Y-m-d H:i:s', $audio['create_time']);
            $audio['is_original'] = is_original()[$audio['is_original']] ?? '';
            $audio['type'] = ($audio['type'] == '1') ? '收费' : '试听';
            $audio['course_title'] = isset($courses[$audio['course_id']]) ? $courses[$audio['course_id']] : '';
            $audio['audio_pic'] = !empty($audio['audio_pic'])?IMG_URL.$audio['audio_pic']:'';
        }

        $data['audios'] = $this->changeMsg($audios);
        $data['page'] = $this->pageInit($data['count'], $this->pageSize);
        $this->display('Audio/index', $data);
    }

    /**
     * 添加音频
     */
    public function add()
    {
        if (IS_POST) {
            $pdata = $this->input->post();
            $pdata['status'] = $this->input->post('status') == 'on' ? 1 : 2;    //是否上架
            $pdata['create_time'] = time();


            // echo '<pre>'; print_r($pdata);print_r($_FILES);exit;
            if (!empty($_FILES)) {
                if ($_FILES['audio_pic']['error'] == '0') {
                    $imgarr = $this->coslib->cos_upload($_FILES);  //cos图片上传
                    $pdata = array_merge($pdata, $imgarr);
                }
                if ($_FILES['audio_file']['error'] == '0') {

                    //先上传到服务器
                    $upload = $this->fileUpload('audio_file', 'public/file/audio/');
                    $result = $this->vodlib->applyUpload($upload['data']);
                    if ($result['message'] == 'success') {
                        $pdata['audio_file_id'] = $result['data']['fileId'];
                        $pdata['audio_addr'] = $result['data']['video']['url'];
                    }
                    //获取视频基本信息
                    $getId3 = new getID3();
                    $fileinfo = $getId3->analyze($upload['data']);
                    $pdata['audio_duration'] = (integer)$fileinfo['playtime_seconds'];
                    //删除本地文件
                    if (file_exists($upload['data'])) {
                        unlink($upload['data']);
                    }
                }
            }
            $parme = array(
                    'course_title'=>$pdata['title'],'up_type'=>'1','cid'=>$pdata['cid'],'status'=>'1',
                    'create_time'=>time(),'update_time'=>time(),'sort'=>'1','is_tg'=>'2'
                );
            $course_id = $this->Course->addCourse($parme);    //添加音频
            $pdata['course_id'] = $course_id;
            unset($pdata['cid']);
            $res = $this->Audio->addAudio($pdata);    //添加音频
            if ($res == false) {
                $error['msg'] = '添加音频失败';
                return $this->error($error);
            } else {
                $success['msg'] = '添加音频成功';
                $success['url'] = 'index';
                return $this->success($success);
            }
        } else {
            $category = $this->Category->_get('cat_name,id',['status'=>'1']);
            $data['category'] = array_column($category,'cat_name','id');
            $data['action'] = 'add';
            $this->display('Audio/add', $data);
        }
    }

    /**
     * 删除角色（逻辑删除）
     */
    public function del()
    {
        $id = $this->input->get('id') ?? 0;
        if ($id < 1) {
            $error['msg'] = '参数不正确，删除失败！';
            return $this->error($error);
        }
        $res = $this->Audio->delAudio($id);
        if ($res == false) {
            $error['msg'] = '删除音频失败';
            return $this->error($error);
        } else {
            $success['msg'] = '删除音频成功';
            $success['url'] = 'index';
            return $this->success($success);
        }
    }

    /**
     * 编辑音频
     */
    public function edit()
    {

        if (IS_POST) {
            $pdata = $this->input->post();
            $pdata['status'] = $this->input->post('status') == 'on' ? 1 : 2;    //是否上架
            $pdata['update_time'] = time();
            if (!empty($_FILES)) {
                if ($_FILES['audio_pic']['error'] == '0') {
                    $imgarr = $this->coslib->cos_upload($_FILES);  //cos图片上传
                    $pdata = array_merge($pdata, $imgarr);
                }
                if ($_FILES['audio_file']['error'] == '0') {

                    //先上传到服务器
                    $upload = $this->fileUpload('audio_file', 'public/file/audio/');
                    $result = $this->vodlib->applyUpload($upload['data']);
                    if ($result['message'] == 'success') {
                        $pdata['audio_file_id'] = $result['data']['fileId'];
                        $pdata['audio_addr'] = $result['data']['video']['url'];
                    }
                    //获取视频基本信息
                    $getId3 = new getID3();
                    $fileinfo = $getId3->analyze($upload['data']);
                    $pdata['audio_duration'] = (integer)$fileinfo['playtime_seconds'];
                    //删除本地文件
                    if (file_exists($upload['data'])) {
                        unlink($upload['data']);
                    }
                }
            }

            //UPDATE table_1 t1 left join table_2 t2 on t2.id = t1.tid SET t1.username = t2.uname where t1.id>5;
            //自动修改排序值
            $audio = $this->Audio->_getOne('sort',['id'=>$pdata['id']]);

            if($audio && $audio['sort'] != $pdata['sort']){
                $audio_sort = $this->db->query("select ci.id,ci.sort from t_course_items as ci left join t_course as c on c.id = ci.course_id 
                where ci.sort = {$pdata['sort']} and c.cid = {$pdata['cid']}");
                if($audio_sort){
                    $this->db->query("update t_course_items as ci left join t_course as c on c.id=ci.course_id set ci.sort = ci.sort - 1 
                    where ci.sort <= {$pdata['sort']} and c.cid = {$pdata['cid']}");
                }
            }
            $parme = array(
                'course_title'=>$pdata['title'],'cid'=>$pdata['cid']
            );
            $this->Course->editCourse($parme);    //添加音频
            unset($pdata['cid']);
            $res = $this->Audio->editAudio($pdata, ['id' => $pdata['id']]);    //编辑音频
            if ($res == false) {
                $error['msg'] = '编辑音频失败';
                return $this->error($error);
            } else {
                $success['msg'] = '编辑音频成功';
                $success['url'] = 'index';
                return $this->success($success);
            }
        } else {
            $id = $this->input->get('id') ?? 0;
            $audio = $this->Audio->_getOne('*', ['id' => $id]);    //音频信息

            if ($audio == false) {
                $error['msg'] = '编辑音频失败';
                return $this->error($error);
            }

            $course = $this->Course->_getOne('cid', ['status' => '1']);
            $audio['cid'] = $course['cid'];
            $audio['big_pic'] = !empty($audio['big_pic']) ? IMG_URL . $audio['big_pic'] : '';
            $audio['audio_pic'] = !empty($audio['audio_pic']) ? IMG_URL . $audio['audio_pic'] : '';

            $data['audio'] = $audio;
            //课程列表
            $courses = $this->Course->_get('id,course_title,cid', ['status' => '1']);
            $data['courses'] = array_column($courses, 'course_title', 'id');

            $category = $this->Category->_get('cat_name,id',['status'=>'1']);
            $data['category'] = array_column($category,'cat_name','id');
//            dump($data);exit;
            $this->display('Audio/edit', $data);
        }
    }

    /**
     * 图片上传腾讯cos
     */
    public function editorUpload()
    {
        $name = 'upload';
        if (!empty($_FILES[$name]['tmp_name'])) {
            try {
                $result = $this->coslib->cos_upload($_FILES);
            } catch (\Exception $exception) {
                $result = [];
            }

            if ($result) {
                $data = array('status' => 1, 'msg' => '上传成功', 'data' => $result[$name] ?? '');
            } else {
                $data = array('status' => 0, 'msg' => '上传失败');
            }

            echo json_encode($data);
            exit;
        }
    }
}