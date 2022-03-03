<?php

namespace app\index\model;

use think\Db;
use think\Model;

class User extends Model
{

    public function updateUserGz($data)
    {
        if ($data['openid']) {
            $map['openid'] = $data['openid'];
            $count = Db::name('user_gz')->where($map)->count();

            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $data['token'] . '&openid=' . $data['openid'] . '&lang=zh_CN';
            $arr2 = curl_get($url);  // 一个使用curl实现的get方法请求
            $arr2 = json_decode($arr2, true);
            $ndata['openid'] = $data['openid'];
            $ndata['access_token'] = $data['token'];
            if ($arr2['unionid']) {
                $ndata['unionid'] = $arr2['unionid'];
            }

            if (empty($count)) {
                $this->startTrans();
                try {
                    Db::name('user_gz')->insert($ndata);
                    $this->commit();
                    return $ndata;
                } catch (\Exception $e) {
                    $this->rollback();
                }
            }else{
                $this->startTrans();
                try {
                    Db::name('user_gz')->where($map)->update($ndata);
                    $this->commit();
                    return $ndata;
                } catch (\Exception $e) {
                    $this->rollback();
                }
            }
        }
    }

}