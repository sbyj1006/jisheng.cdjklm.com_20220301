<?php

namespace app\index\controller;
use think\Db;
use app\common\controller\Frontend;
use app\index\model\Article;
class Service extends Frontend
{


    public function index(){//关于我们
        $Article = new Article();
        $id=input('id');
        $about_nav=Db::name('category')->where(array('pid'=>1,'status'=>'normal'))->order('rank desc')->select();//
        $list=Db::name('article')->where(array('tid'=>$id,'status'=>1))->find();//公司介绍
        $this->assign('list', $list);
        $this->assign('about_nav', $about_nav);
        $banner= DB::name('banner')->where(array('tid'=>4,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);
        $fw_case=$Article->fw_cases($id);
        $this->assign('fw_case', $fw_case);
        return $this->fetch();
    }




    public function  fwcase_detail(){//
        $banner= DB::name('banner')->where(array('tid'=>4,'status'=>1))->order('sort asc')->select();
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
