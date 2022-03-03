<?php

namespace app\admin\model\xshop;

use think\Model;


class ProductMatching extends Model
{

    

    

    // 表名
    protected $name = 'xshop_product_matching';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text',
        'recommend_text',
        'hot_text'
    ];
    

    
    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }

    public function getRecommendList()
    {
        return ['0' => __('Recommend 0'), '1' => __('Recommend 1')];
    }

    public function getHotList()
    {
        return ['0' => __('Hot 0'), '1' => __('Hot 1')];
    }


    public function getTypeList()
    {
        return ['0' => __('Type 0'), '1' => __('Type 1')];
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


    public function getHotTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['hot']) ? $data['hot'] : '');
        $list = $this->getHotList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function category()
    {
        return $this->belongsTo('Category', 'tid', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
