<?php

/**
 * model基础类
 *
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/7/8
 * Time: 22:49
 */
class MY_Model extends CI_Model
{
    /**
     * @var string 表名
     */
    public $_table = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 获取数据列表
     * @param string $filed 查询字段 默认为所有
     * @param array $where 查询条件 $where = array('id'=>'1') $where = array('id >'=>'1')
     * @param array $like 搜索条件 $like = array('name'=>'test')
     * @param array $order 排序条件 $order = array("id"=>"desc")
     * @param array $limit 条数限制 $limit = array('page'=>1,'count'=>10);
     * @param array $join 连表 $join = array('table2' => 'table2.id = table1.id');
     * @param string $left 连表方式 $left = left，right，outer，inner，left outer 和 right outer
     * @param string $group 分组 $group = 'group_id' or array("group_id", "group_id1");
     * @param array $having 筛选 $having = array('title =' => 'My Title','id <' => $id)
     * @return bool|mixed
     */
    public function _get($filed = '*', $where = [], $like = [], $order = [], $limit = [], $join = [], $left = 'left', $group = '', $having = [])
    {
        if ($this->db->conn_id === false) {
            return false;
        }
        $this->db->select($filed);
        $this->db->from($this->_table);

        /* where条件 */
        if (is_array($where) && !empty($where)) {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    $this->db->where_in($key, $value);
                } else {
                    $this->db->where($key, $value);
                }
            }
        }

        /* like条件 */
        if (is_array($like) && !empty($like)) {
            foreach ($like as $key => $value) {
                $this->db->like($key, $value);
            }
        }

        /* order条件 */
        if (is_array($order) && !empty($order)) {
            foreach ($order as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }

        /* 分页 */
        if (is_array($limit) && !empty($limit)) {
            $limit['offset'] = (($limit['page'] - 1) * $limit['count']);
            $limit['limit'] = $limit['count'];
            $this->db->limit($limit['limit'], $limit['offset']);
        }

        /* join条件*/
        if (is_array($join) && !empty($join)) {
            foreach ($join as $key => $value) {
                $this->db->join($key, $value, $left);
            }
        }

        /* group条件 */
        if ($group) {
            if (is_array($group)) {
                foreach ($group as $key => $value) {
                    $this->db->group_by($value);
                }
            } else {
                $this->db->group_by($group);
            }
        }

        /* having条件 */
        if (is_array($having) && !empty($having)) {
            $this->db->having($having);
        }

        return $this->db->get()->result_array();
    }

    /**
     * 获取单条数据
     * @param string $filed
     * @param array $where
     * @param array $order
     * @param array $join 连表 $join = array('table2' => 'table2.id = table1.id');
     * @param string $left 连表方式 $left = left，right，outer，inner，left outer 和 right outer
     * @return bool
     */
    public function _getOne($filed = '*', $where = [], $order = [], $join = [], $left = 'left')
    {
        if ($this->db->conn_id === false) {
            return false;
        }
        $this->db->select($filed);
        $this->db->from($this->_table);

        /* where条件 */
        if (is_array($where) && !empty($where)) {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    $this->db->where_in($key, $value);
                } else {
                    $this->db->where($key, $value);
                }
            }
        }

        /* order条件 */
        if (is_array($order) && !empty($order)) {
            foreach ($order as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }

        /* join条件*/
        if (is_array($join) && !empty($join)) {
            foreach ($join as $key => $value) {
                $this->db->join($key, $value, $left);
            }
        }

        $query = $this->db->get()->result_array();

        if ($query) {
            return $query[0];
        } else {
            return false;
        }
    }

    /**
     * 更新数据
     * @param array $data 数据
     * @param array $where 条件
     * @return bool|mixed
     */
    public function _update($data = [], $where = [])
    {
        if (empty($where) || empty($data)) {
            return false;
        }

        $select_r = $this->_get('*', $where);
        if ($select_r == false) {
            return false;
        }

        $this->db->update($this->_table, $data, $where);
        return $this->db->affected_rows();
    }

    /**
     * 添加方法（支持批量插入）
     * @param array $data
     * @param string $return 返回insert结果或者插入id
     * @return bool|mixed
     */
    public function _add($data = [], $return = '')
    {
        if ($this->db->conn_id === false || !is_array($data))
            return false;
        if (count($data) == count($data, 1)) {
            $inert_r = $this->db->insert($this->_table, $data);
            if ($return == '') {
                return $inert_r ?? false;
            } else {
                return $this->db->insert_id();
            }
        } else {    //批量插入,多维数组
            return $this->db->insert_batch($this->_table, $data);
        }
    }

    /**
     * 删除方法
     * @param array $where
     * @return bool|mixed
     */
    public function _del($where = [])
    {
        if ($this->db->conn_id === false)
            return false;
        if (!is_array($where)) {
            return false;
        }

        return $this->db->delete($this->_table, $where);
    }

    /**
     * 执行sql
     * @param $sql string 需要执行的sql
     * @return bool 执行结果
     */
    public function _query($sql)
    {
        $query = $this->db->query($sql);
        return is_bool($query) ? $query : $query->result_array();
    }

    /**
     * 事物处理
     * @access public
     * @param array $sql sql数组
     * @return bool
     */
    public function _transtion($sql = array())
    {
        if (empty($sql)) {
            return false;
        }
        $this->db->trans_begin();
        foreach ($sql as $v) {
            $this->db->query($v);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    /**
     * 批量更新
     * @param array $data 要更新的数据
     * @param string $key 主键条件
     * @return mixed
     */
    public function _update_batch($data = [], $key = 'id')
    {
        return $this->db->update_batch($this->_table, $data, $key);
    }

    /**
     * 获取统计数量
     * @param string|array $where where条件 数组格式
     * @param string|array $like 搜索 数组格式
     * @return bool|int
     */
    public function _count($where = '', $like = '', $select = "*", $join = '', $left = '')
    {
        return $this->_get("count({$select}) as count", $where, $like, '', '', $join, $left)[0]['count'];
    }


    /**
     * 统计某个字段总数量
     * @param string $tmp
     * @param array $where
     * @return int
     */
    public function _sum(string $tmp, array $where)
    {
        $this->db->select_sum($tmp);
        $this->db->where($where);
        $query = $this->db->get($this->_table)->row();

        return $query->$tmp ?? 0;
    }
}