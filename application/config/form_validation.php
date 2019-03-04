<?php
/**
 * Created by PhpStorm.
 * User: guochao
 * Date: 2018/7/18
 * Time: 23:00
 */

$config = array(
    //购买课程
    'buy_courses' => [
        array(
            'field' => 'courseId',
            'label' => 'courseId',  //课程id
            'rules' => 'integer|required',
            'errors' => array(
                'required' => 'courseId参数错误'
            ),
        )
    ]
);