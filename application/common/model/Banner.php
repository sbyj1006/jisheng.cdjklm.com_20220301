<?php

namespace app\common\model;

use think\Model;


class Banner extends Model
{

    

    

    // 表名
    protected $name = 'banner';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text',
        'recommend_text'
    ];
    

    
    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }

    public function getRecommendList()
    {
        return ['0' => __('Recommend 0'), '1' => __('Recommend 1')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getRecommendTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['recommend']) ? $data['recommend'] : '');
        $list = $this->getRecommendList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function bannertype()
    {
        return $this->belongsTo('BannerType', 'tid', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
