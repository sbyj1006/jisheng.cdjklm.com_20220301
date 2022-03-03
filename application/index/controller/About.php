<?php

namespace app\index\controller;
use think\Db;
use app\common\controller\Frontend;
use app\index\model\Article;
class About extends Frontend
{


    public function index(){//关于我们
        $Article = new Article();
        $id=input('id');

        if($id){

        }else{
            $pid=19;
            $id=Db::name('category')->where(array('pid'=>$pid))->order('rank desc')->value('id');
        }
        $ftitle=Db::name('category')->where(array('id'=>$id))->find();
        $title=Db::name('category')->where(array('id'=>$ftitle['pid']))->find();
        $types=Db::name('category')->where(array('pid'=>$ftitle['pid']))->order('rank desc')->select();//顶级
        $banner= DB::name('banner')->where(array('tid'=>8,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);
        $list = Db::name('article')->where(array('tid'=>$id,'status'=>1))->select();
//        dump($list);
        $this->assign('title', $title);
        $this->assign('ftitle', $ftitle);
        $this->assign('list', $list);
        $this->assign('types', $types);
        $this->assign('id',$id);
        return $this->fetch();
    }






    public function myuserqr(){

        $cardid="10";

        $mpcodeimg=$this->mpcode("/pages/member/myqr",$cardid);
        $dataret['mpcodeimg']=$mpcodeimg;
//        exit(json_encode(['code'=>200,
//            'msg'=>'二维码生成成功',
//            'mydatas'=>$dataret,
//        ]));
//
        $this->assign('retdata',$dataret);
        return $this->fetch();
    }




}
