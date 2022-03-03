<?php

namespace app\index\controller;
use think\Db;
use app\common\controller\Frontend;
use app\index\model\Article;
class News extends Frontend
{


    public function index(){
        $Article = new Article();
        $id=input('id');

        if($id){
            $id=input('id');
        }else{
            $fistdata=Db::name('category')->where(array('pid'=>24))->order("weigh asc")->find();
            $id=$fistdata['id'];
        }

        $list=$Article->news_list($id);



        $ftitle=Db::name('category')->where(array('id'=>$id))->find();
        $title=Db::name('category')->where(array('id'=>$ftitle['pid']))->find();
        $types=Db::name('category')->where(array('pid'=>$ftitle['pid']))->order('rank desc')->select();//顶级

//
        $this->assign('types', $types);
        $this->assign('id', $id);
        $this->assign('list', $list);
        $this->assign('ftitle',$ftitle);
        $this->assign('title',$title);
        $banner= DB::name('banner')->where(array('tid'=>10,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);
        return $this->fetch();
    }




    public function  news_detail(){//
        $banner= DB::name('banner')->where(array('tid'=>10,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);
        $id=input('id');
        $list = Db::name('article')->where(array('id'=>$id,'status'=>1))->find();
        DB::name('article')->where('id',$list['id'])->setInc('dian');
        $ids=$list['tid'];
        $ftitle=Db::name('category')->where(array('id'=>$list['tid']))->find();
        $title=Db::name('category')->where(array('id'=>$ftitle['pid']))->find();
        $type=Db::name('category')->where(array('id'=>$ftitle['pid']))->find();
        $types=Db::name('category')->where(array('pid'=>$ftitle['pid']))->order('rank desc')->select();//顶级
        $prev= Db::name('article')->where(array('tid'=>$list['tid'],'status'=>'1'))->where('addtime','>',$list['addtime'])->order('addtime','asc')->limit(1)->find();//上一篇
        $next= Db::name('article')->where(array('tid'=>$list['tid'],'status'=>'1'))->where('addtime','<',$list['addtime'])->order('addtime','desc')->limit(1)->find();//下一篇
        $this->assign('list', $list);
        $this->assign('types',$types);
        $this->assign('type',$type);
        $this->assign('id',$ids);
        $this->assign('ftitle',$ftitle);
        $this->assign('title',$title);
        $this->assign('prev',$prev);
        $this->assign('next',$next);
        return $this->fetch();
    }








}
