<?php

namespace app\index\controller;
use think\Db;
use app\common\controller\Frontend;
use app\index\model\Article;
use think\Request;

class Contact extends Frontend
{


    public function index(){//关于我们
        $Article = new Article();
        $id=input('id');
        if($id){

        }else{
            $pid=30;
            $id=Db::name('category')->where(array('pid'=>$pid))->order('rank desc')->value('id');
        }
        $list=db('article')->where(array('tid'=>$id,'status'=>1))->find();
//        dump($id);
        $this->assign('list', $list);
        $title=db('category')->where(array('id'=>$id))->find();
        $this->assign('id',$id);
        $this->assign('title',$title);
        $banner= DB::name('banner')->where(array('tid'=>12,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);
        return $this->fetch();
    }


    public function msg(){
        if($this->request->isPost()){
            $data['name'] = input('post.name');
            $data['phone'] = input('post.phone');
            $data['remarks'] = input('post.remarks');
            $data['createtime'] = time();
            $data['type'] = 2;
            $data['status'] = 0;
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
