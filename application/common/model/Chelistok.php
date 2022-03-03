<?php

namespace app\common\model;

use think\Model;


class Chelistok extends Model
{





    // 表名
    protected $name = 'wechat_carlist';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text',
        'tuoy_ziy_text',
    ];



    public function getStatusList()
    {
        return ['7' => __('statue 7'), '8' => __('statue 8')];
    }

    public function getTuoyziyList()
    {
        return ['1' => __('tuoy_ziy 1'), '2' => __('tuoy_ziy 2')];
    }

    public function getRecommendList()
    {
        return ['0' => __('Recommend 0'), '1' => __('Recommend 1')];
    }


    public function getTypeList()
    {
        return ['0' => __('Type 0'), '1' => __('Type 1')];
    }



    public function getTuoyziyTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['tuoy_ziy']) ? $data['tuoy_ziy'] : '');
        $list = $this->getTuoyziyList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['statue']) ? $data['statue'] : '');
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
