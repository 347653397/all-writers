<?php
/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/7/3
 * Time: 22:44
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home_Controller
 * 前台父控制器
 */
class Home_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->set_ci_view_dir(HOME_VIEW_DIR);    //设置前台视图路径
    }
}

/**
 * Class Api_Controller
 * API父控制器
 * @property PushLog_model $PushLog
 */
class Api_Controller extends CI_Controller
{
    protected $user_info;

    protected $page = 1;
    protected $pageSize = 10;

    public function __construct()
    {
        parent::__construct();

        if (in_array($_SERVER['HTTP_HOST'], ['pre.startechsoft.cn', 'test.startechsoft.cn'])
            && !isset($_GET['test'])) {
            $this->checkUser();
        } else {
            $this->user_info = $this->User->_getOne('*', ['openid' => 'osxqv0VUxmpoqZHe6DD9kd_1XuUA']);
        }
    }

    //检测用户是否登录
    protected function checkUser()
    {
        $user_info = $this->session->userdata($this->wechat_user);
        if (!$user_info) {
            $this->responseToJson(502, '非法登录');
        } else {
            $data = $this->User->_getOne('*', ['openid' => $user_info['openid']]);
            if ($data) {
                $this->user_info = $data;
            } else {
                $this->responseToJson(502, '该用户还未注册');
            }
        }
    }

    /**
     * 格式化返回数据
     * @param $status 200->成功 502->失败
     * @param int $status
     * @param string $msg
     * @param array $data
     */
    protected function responseToJson(int $status, string $msg, array $data = [])
    {
        header('Content-Type: application/json');
        header('Content-Type: text/html;charset=utf-8');

        if (!in_array($status, [200, 502, 501])) {
            echo json_encode(['status' => 402, 'msg' => '返回状态码不正确，请联系开发者!']);
        } else {
            echo json_encode([
                'status' => $status,
                'msg' => $msg,
                'data' => $data
            ]);
        }

        exit;
    }


}

/**
 * Class Admin_Controller
 * 后台父控制器
 */
class Admin_Controller extends CI_Controller
{

    protected $_layout = 'Public/layout';

    protected $admin = [];    //管理员信息
    protected $menu = [];    //菜单列表
    protected $current = [];    //当前操作
    protected $setting = [];    //系统设置

    protected $page = 1;
    protected $pageSize = 10;
    protected $configPage = [];//分页样式配置

    public $msg = array(
        'status' => array(
            '1' => '显示',
            '2' => '不显示'
        )
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->set_ci_view_dir(ADMIN_VIEW_DIR);    //设置后台视图路径
        $flag = false;

        /** -------------- 验证用户是否登录，获取登录用户信息 ---------------- **/
        $token = $this->session->userdata('token');

        //如果是登录，显示登录页面
        if (ucfirst($this->router->fetch_class()) == 'Login' && !$token) {
            return false;
        }

        if ($token) {
            $admin = $this->Admin->_getOne('id,username,head_pic,sex,phone,email', ['token' => $token, 'status' => 1]);
            if ($admin) {
                $flag = true;
                $admin['head_pic'] = json_decode($admin['head_pic']);
                $this->admin = $admin;    //保存用户信息
            }
        }

        if ($flag == false) {
            $this->session->unset_userdata('token');
            header("Location: /Admin/Login");
            exit();
        }

        /** ---------------- 判断操作是否有权限，获取权限列表 ----------------**/
        $superAdmin = $this->Admin->SuperAdmin($this->admin['id']);    //是否是超级管理员

        $auth_name = ucfirst($this->router->fetch_class() . '/' . $this->router->fetch_method());    //当前操作与方法

        //如果是超级管理员不用验证操作权限
        if ($superAdmin) {
            $checkAuthName = $this->Auth->_get('auth_id', ['auth.name' => $auth_name]);
        } else {
            $checkAuthName = $this->Admin->getAdminAuth($this->admin['id'], ['auth.name' => $auth_name]);    //判断当前操作是否有权限
        }

//         print_r($auth_name);exit;
        if ($checkAuthName == false) {
            header("Location: /Admin/Login/authError");
            exit();
        } else {
            $this->current = $this->Auth->get_current_auth($checkAuthName[0]['auth_id']) ?? '';    //获取当前操作方法
        }

        if ($superAdmin) {
            $admin_auth_arr = $this->Auth->_get('*', ['status' => 1], false, ["sort" => "asc"]);    //管理员获取所菜单
        } else {
            $admin_auth_arr = $this->Admin->getAdminAuth($this->admin['id']);    //获取角色所有权限
        }

        $this->menu = $this->get_menu_tree($admin_auth_arr);    //获取菜单列表
        $this->setting = $this->Setting->getAll();    //获取系统设置

        /** ---------------- 分页样式 ----------------**/
        $this->pageStyle();
    }

    /**
     * 渲染后台视图
     * @param $view string 视图路径
     * @param null $data 数据
     */
    protected function display($view, $data = null)
    {
        $data['admin'] = $this->admin;    //管理员信息
        $data['menus'] = $this->menu;    //左侧菜单
        $data['current'] = $this->current;    //当前操作方法
        $data['setting'] = $this->setting;    //系统设置

        $layout_data['content'] = $this->load->view($view, $data, true);
        $this->load->view($this->_layout, $layout_data);
    }

    /**
     * 成功跳转页面
     * @param null $success
     */
    protected function success($success = null)
    {
        $data['success'] = $success;
        $this->load->view('Public/header', $data);
        $this->load->view('Public/footer');
        $this->load->view('success');
    }

    /**
     * 错误跳转页面
     * @param null $error
     */
    protected function error($error = null)
    {
        $data['error'] = $error;
        $this->load->view('Public/header', $data);
        $this->load->view('Public/footer');
        $this->load->view('error');
    }

    /**
     * @param int $status
     * @param string $msg
     * @param array $data
     */
    protected function responseToJson(int $status, string $msg, array $data = [])
    {
        echo json_encode([
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ]);

        exit;
    }

    /**
     * 添加操作日志
     * @param $log string 操作说明
     * @param string $admin_id
     * @return mixed
     */
    protected function add_log($log, $admin_id = '')
    {
        $this->load->model('AdminLog_model', 'AdminLog');
        return $this->AdminLog->add($log, $admin_id);
    }

    /**
     * 操作菜单列表生成树状
     * @param $items array 操作数组
     * @param string $id
     * @param string $pid
     * @param string $son
     * @return array
     * @todo http://www.jb51.net/article/65840.htm
     */
    protected function get_menu_tree($items, $id = 'auth_id', $pid = 'pid', $son = 'children')
    {
        $tmpMap = array();
        $tree = array();

        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }

        foreach ($items as $item) {
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        return $tree;
    }

    protected function pageStyle()
    {
        $this->configPage['uri_segment'] = 3;
        $this->configPage['num_links'] = 10;
        $this->configPage['use_page_numbers'] = TRUE;
        $this->configPage['page_query_string'] = true;
        $this->configPage['query_string_segment'] = 'page';
        $this->configPage['reuse_query_string'] = true;
        $this->configPage['full_tag_open'] = '<div><ul class="pagination" style="margin:0">';
        $this->configPage['full_tag_close'] = '</ul></div>';

        $this->configPage['first_link'] = '<<';
        $this->configPage['first_tag_open'] = '<li>';
        $this->configPage['first_tag_close'] = '</li>';
        $this->configPage['first_url'] = '?page=1';
        //$this->configPage['first_url'] = ''; //自动生成请求条件

        $this->configPage['last_tag_open'] = '<li >';
        $this->configPage['last_tag_close'] = '</li>';
        $this->configPage['last_link'] = '末页';
        $this->configPage['next_link'] = '>>';
        $this->configPage['next_tag_open'] = '<li class="disabled">';
        $this->configPage['next_tag_close'] = '</li>';
        $this->configPage['next_tag_open'] = '<li>';
        $this->configPage['next_tag_close'] = '</li>';
        $this->configPage['prev_link'] = '<<';
        $this->configPage['prev_tag_open'] = '<li>';
        $this->configPage['prev_tag_close'] = '</li>';
        $this->configPage['cur_tag_open'] = '<li class="active"><a href="#">';
        $this->configPage['cur_tag_close'] = '</a></li>';
        $this->configPage['num_tag_open'] = '<li>';
        $this->configPage['num_tag_close'] = '</li>';
    }

    /**
     * 转换模型中对应的字段说明 如 status 1 2 显示 隐藏
     */
    public function changeMsg($data)
    {
        if (!empty($data)) {
            if (count($data) == count($data, 1)) {
                $data['status'] = $this->msg['status'][$data['status']];
            } else {
                foreach ($data as $key => $value) {
                    $data[$key]['status'] = $this->msg['status'][$value['status']];    //获取对应说明
                }
            }
        }

        return $data;
    }

    //格式化短信头部
    function changefrom($from)
    {

        $msg = '';
        switch ($from) {
            case 'moguqianbao':
                $msg = '蘑菇钱包';
                break;
            case 'leifengqianbao':
                $msg = '雷锋钱包';
                break;
            case 'yinyiqianbao':
                $msg = '银翼钱包';
                break;
            default:
                $msg = '蘑菇钱包';
                break;
        }
        return $msg;
    }

    /**
     * 操作分页函数
     * @param string $count 数据数量
     * @param string $pageSize 分页大小
     * @return mixed
     */
    protected function pageInit($count = '', $pageSize = '')
    {
        //继承分页样式
        $configPage = $this->configPage;
        $configPage['total_rows'] = $count;
        $configPage['per_page'] = $pageSize;
        //生成分页
        $this->pagination->initialize($configPage);
        return $this->pagination->create_links();
    }

    /**
     * 封装图片上传
     * @param $name
     * @param $upload_path
     * @param $allowed_types
     * @param $max_size
     * @param $file_name
     * @return array
     */
    public function picUpload($name = 'upload', $upload_path = '', $file_name = '', $allowed_types = '', $max_size = '')
    {
        $config['upload_path'] = FCPATH . $upload_path ? FCPATH . $upload_path : FCPATH . 'public/img/uploads/head_pic/';
        $config['allowed_types'] = $allowed_types ? $allowed_types : 'gif|jpg|png|jpeg';
        $config['max_size'] = $max_size ? $max_size : 2048;
        $config['file_name'] = $file_name ? $file_name : 'pic_' . time();

        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($name)) {
            $data = array('status' => 0, 'msg' => $this->upload->display_errors());
        } else {
            $data = array('status' => 1, 'msg' => '上传成功', 'data' => '/' . $upload_path . $this->upload->data()['file_name']);
        }

        return $data;
    }

    /**
     * 封装文件上传
     * @param $name
     * @param $upload_path
     * @param $allowed_types
     * @param $max_size
     * @param $file_name
     * @return array
     */
    public function fileUpload($name = 'upload', $upload_path = '', $file_name = '', $allowed_types = '', $max_size = '')
    {
        $config['upload_path'] = FCPATH . $upload_path ? FCPATH . $upload_path : FCPATH . 'public/file/upload/';
        $config['allowed_types'] = $allowed_types ? $allowed_types : 'wav|mp3|midi|wma|ape|cd|aiff';
        $config['max_size'] = $max_size ? $max_size : 52428800;//50M
        $config['file_name'] = $file_name ? $file_name : 'audio_' . time();

        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($name)) {
            $data = array('status' => 0, 'msg' => $this->upload->display_errors());
        } else {
            $data = array('status' => 1, 'msg' => '上传成功', 'data' => $upload_path . $this->upload->data()['file_name']);
        }

        return $data;
    }


}