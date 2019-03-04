<?php

class Redislib
{

    protected $_redis;

    public function __construct()
    {
        //$this->ci = &get_instance();
        $this->_redis = new Redis();

        $this->_redis->connect('212.64.35.22', 6379);//测试
//        $this->_redis->connect('118.25.45.240', 6379); //线上 上线注意改
        $this->_redis->auth('dV4iZRIgzBphP0XR');
    }

    //普通键值
    public function set($key, $value)
    {
        $result = $this->_redis->set($key, $value);
        return $result;
    }

    public function get($key)
    {
        $result = $this->_redis->get($key);
        return $result;
    }

    public function del($key)
    {
        $result = $this->_redis->del($key);
        return $result;
    }

    //设置带过期时间
    public function setex($key, $value, $timeout)
    {
        $result = $this->_redis->setex($key, $timeout, $value);
        return $result;
    }

    //判断重复,键存在 返回false
    public function setnx($key, $value)
    {
        $result = $this->_redis->setnx($key, $value);
        return $result;
    }

    //批量设置
    public function mset($arr_data)
    {
        $result = $this->_redis->mset($arr_data);
        return $result;
    }

    //获取key的生存时间
    public function ttl($key)
    {
        $result = $this->_redis->ttl($key);
        return $result;
    }

    //删除
    public function delete($key)
    {
        $result = $this->_redis->delete($key);

        return $result;
    }

    //判断键是否存在
    public function exists($key)
    {
        $result = $this->_redis->exists($key);

        return $result;
    }

    //获取所有的键 like
    public function keys($partner)
    {
        $result = $this->_redis->keys($partner);
        return $result;
    }

    //数字递增 +1
    public function incr($key)
    {
        $result = $this->_redis->incr($key);
        return $result;
    }

    //数字递减  -1
    public function decr($key)
    {
        $result = $this->_redis->decr($key);
        return $result;
    }

    //数字递增 加指定整数
    public function incrby($key, $value)
    {
        $result = $this->_redis->incrby($key, $value);
        return $result;
    }

    //数字递减  减指定整数
    public function decrby($key, $value)
    {
        $result = $this->_redis->decrby($key, $value);
        return $result;
    }

    //数字递增 加指定浮点数  也加一个负值
    public function incrbyfloat($key, $value)
    {
        $result = $this->_redis->incrbyfloat($key, $value);
        return $result;
    }

    //数字递减  减指定浮点数
    public function decrbyfloat($key, $value)
    {
        $result = $this->_redis->decrbyfloat($key, $value);
        return $result;
    }


    //list 部分 ===========================================================

    //左边插入
    public function lPush($key, $value)
    {
        $result = $this->_redis->lPush($key, $value);
        return $result;
    }

    //右边插入
    public function rPush($key, $value)
    {
        $result = $this->_redis->rPush($key, $value);
        return $result;
    }

    //左边去重插入，存在则不插入 lPushx/rPushx
    public function lPushx($key, $value)
    {
        $result = $this->_redis->lPushx($key, $value);
        return $result;
    }

    //右边去重插入，存在则不插入 lPushx/rPushx
    public function rPushx($key, $value)
    {
        $result = $this->_redis->rPushx($key, $value);
        return $result;
    }

    //删除左侧第一个元素
    public function lPop($key)
    {
        $result = $this->_redis->lPop($key);
        return $result;
    }

    //删除右侧第一个元素
    public function rPop($key)
    {
        $result = $this->_redis->rPop($key);
        return $result;
    }

    //获取list的元素个数
    public function lSize($key)
    {
        $result = $this->_redis->lSize($key);
        return $result;
    }

    //获取list的元素
    public function lRange($key, $start_index, $end_index)
    {
        $result = $this->_redis->lRange($key, $start_index, $end_index);
        return $result;
    }

    //删除元素
    //首先要去判断count参数，如果count参数为0，那么所有符合删除条件的元素都将被移除。
    //如果count参数为整数,将从左至右删除count个符合条件的元素，如果为负数则从右至左删除count个符合条件的元素。
    //返回删除的元素个数 没有元素则返回0
    public function lRem($key, $value, $count)
    {
        $result = $this->_redis->lRem($key, $value, $count);
        return $result;
    }



    //hash 部分 =======================================================
    //设置hash
    public function hset($key, $field, $value)
    {
        $result = $this->_redis->hSet($key, $field, $value);
        return $result;
    }

    //获取hash的一个字段
    public function hget($key, $field)
    {
        $result = $this->_redis->hGet($key, $field);
        return $result;
    }

    //获取hash的元素个数
    public function hLen($key)
    {
        $result = $this->_redis->hLen($key);

        return $result;
    }

    //删除一个元素
    public function hDel($key, $field)
    {
        $result = $this->_redis->hDel($key, $field);
        return $result;
    }


    //set 部分 =============================================================
    //set 添加项 存在则不添加  返回false
    public function sAdd($key, $value)
    {
        $result = $this->_redis->sAdd($key, $value);
        return $result;
    }

    //从集合移除一个元素
    public function sRem($key, $value)
    {
        $result = $this->_redis->sRem($key, $value);
        return $result;
    }

    //判断元素是否在一个集合中
    public function sIsMember($key, $value)
    {
        $result = $this->_redis->sIsMember($key, $value);
        return $result;
    }

    //获取set的元素个数
    public function sSize($key)
    {
        $result = $this->_redis->sSize($key);
        return $result;
    }

    //获取集合的所有元素
    public function sMembers($key)
    {
        $result = $this->_redis->sMembers($key);
        return $result;
    }

    public function sort($key)
    {
        $result = $this->_redis->sort($key);
        return $result;
    }

    //获取2个set的交集
    public function sInter($key1, $key2)
    {
        $result = $this->_redis->sInter($key1, $key2);
        return $result;
    }

    //获取set的并集
    public function sUnion($key1, $key2)
    {
        $result = $this->_redis->sUnion($key1, $key2);
        return $result;
    }

    //附近的人专用
    public function sUnionForNine($key1, $key2, $key3, $key4, $key5, $key6, $key7, $key8, $key9)
    {
        $result = $this->_redis->sUnion($key1, $key2, $key3, $key4, $key5, $key6, $key7, $key8, $key9);
        return $result;
    }


    //sorted set 有序集 ====================================================

    //向sorted set添加成员
    public function zAdd($key, $score, $member)
    {
        $result = $this->_redis->zAdd($key, $score, $member);

        return $result;
    }

    //向sorted 有序集合中指定成员的分数加上增量 increment
    public function zIncrBy($key, $score, $member)
    {
        $result = $this->_redis->zIncrBy($key, $score, $member);

        return $result;
    }

    //从小到大取元素 $redis->zRange('key1', 0, -1);
    public function zRange($key, $start, $end, $widthscores = false)
    {
        $result = $this->_redis->zRange($key, $start, $end,$widthscores);

        return $result;
    }

    //从大到小取元素
    public function zRevRange($key, $start, $end, $widthscores = false)
    {
        $result = $this->_redis->zRevRange($key, $start, $end,$widthscores);

        return $result;
    }

    //根据分数从大到小获取对应元素  加 LIMIT  offset  count 可实现分页
    public function zRevRangeByScore($key, $max = '+inf', $min = '-inf', array $options = [])
    {
        $result = $this->_redis->zRevRangeByScore($key, $max, $min,$options);

        return $result;
    }

    //删除元素
    public function zDelete($key, $member)
    {
        $result = $this->_redis->zDelete($key, $member);
        return $result;
    }

    //取set的所有元素个数
    public function zCard($key)
    {
        $result = $this->_redis->zCard($key);
        return $result;
    }

    //取元素的分数
    public function zScore($key, $member)
    {
        $result = $this->_redis->zScore($key, $member);
        return $result;
    }

    //取元素的索引 从小到大
    public function zRank($key, $member)
    {
        $result = $this->_redis->zRank($key, $member);
        return $result;
    }

    //取元素的索引 从大到小
    public function zRevRank($key, $member)
    {
        $result = $this->_redis->zRevRank($key, $member);
        return $result;
    }
}

?>
