<?php

namespace app\index\controller;
use think\Db;
use app\common\controller\Frontend;
use app\index\model\Article;
use think\Request;

class Jshuishou extends Frontend
{


    public function index(){//回收
        $Article = new Article();
        $id=input('id');

        $chetype=Db::name('category')->where(array('pid'=>40))->order('rank desc')->select();
        $this->assign('chetype', $chetype);
//
        $this->assign('id',$id);

        $banner= DB::name('banner')->where(array('tid'=>16,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);
        return $this->fetch();
    }


    public function msg(){
        if($this->request->isPost()){
            $data['name'] = input('post.f_name');
            $data['phone'] = input('post.f_phone');
            $data['f_dizhi'] = input('post.f_dizhi');
            $data['f_che'] = input('post.f_che');
            $data['f_type'] = input('post.f_type');
            $data['f_chenum'] = input('post.f_chenum');
            $data['iscandrive'] = input('post.iscandrive');
            $data['type'] = 1;
            $data['status'] = 0;
            $data['remarks'] = input('post.remarks');
            $data['createtime'] = time();
            $message=db('message')->insertGetId($data);
            if($message){
                $data =1;
                return json($data);
            }else{
                $data =2;
                return json($data);
            }

        }
    }











}
