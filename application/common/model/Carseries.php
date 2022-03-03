<?php

namespace app\common\model;

use think\Model;


class Carseries extends Model
{

    

    

    // 表名
    protected $name = 'car_series';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];



    public function getStatusList()
    {
        return ['0' => __('隐藏'), '1' => __('正常')];
    }




    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

//
//
//
//
//
//    public function category()
//    {
//        return $this->belongsTo('Category', 'tid', 'id', [], 'LEFT')->setEagerlyType(0);
//    }
}
