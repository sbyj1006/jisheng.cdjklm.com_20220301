<?php

namespace app\api\model;

use think\Db;
use think\Model;


class Huishou extends Model
{

    // 表名
    protected $name = 'wechat_huishou';


    public function get_data($where){//

        $list = $this->where($where)->find();
        return $list;
    }

}
