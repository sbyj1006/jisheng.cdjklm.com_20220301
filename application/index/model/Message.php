<?php

namespace app\index\model;

use think\Db;
use think\Model;

class Message extends Model
{

    public function addData($data){//留言
        $map['address']=$data['address'];
        $map['name']=$data['name'];
        $map['phone']=$data['phone'];
        $map['remarks']=$data['remarks'];
        $map['addtime'] = time();
        $rst=DB::name('message')->insert($map);
        return $rst;
    }

}