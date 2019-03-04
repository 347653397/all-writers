<?php

/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 10/17
 * Time: 15:26
 */
class ContentScore_model extends MY_Model
{
    public $_table = 'content_score';

    public function sumSorce(string $tmp, array $where)
    {
        $this->db->select_sum($tmp);
        $this->db->where($where);
        $query = $this->db->get($this->_table)->row();

        return $query->$tmp ?? 0;
    }
}