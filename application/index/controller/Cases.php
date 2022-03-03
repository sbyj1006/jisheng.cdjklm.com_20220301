<?php

namespace app\index\controller;
use think\Db;
use app\common\controller\Frontend;
use app\index\model\Article;
class Cases extends Frontend
{


    public function index(){//
        $Article = new Article();
        $id=input('id');
        if($id){
if($id==37){
    $ftitle=Db::name('category')->where(array('id'=>$id))->find();
    $title=Db::name('category')->where(array('id'=>$ftitle['pid']))->find();

    $chetype=Db::name('category')->where(array('pid'=>40))->order('rank desc')->select();
    $this->assign('chetype', $chetype);
    $list=$Article->get_list($id);

//        dump($list);die();

    $this->assign('id', $id);
    $this->assign('list', $list);
    $this->assign('ftitle',$ftitle);
    $this->assign('title',$title);
    $banner= DB::name('banner')->where(array('tid'=>9,'status'=>1))->order('sort asc')->select();
    $this->assign('banner',$banner);
    return $this->fetch('zzshengbao');
}
        }else{

            $pid=28;
            $id=Db::name('category')->where(array('pid'=>$pid))->order('rank desc')->value('id');
        }

        $ftitle=Db::name('category')->where(array('id'=>$id))->find();
        $title=Db::name('category')->where(array('id'=>$ftitle['pid']))->find();


        $list=$Article->get_list($id);
        $types=Db::name('category')->where(array('pid'=>$ftitle['pid']))->order('rank desc')->select();//顶级
        $this->assign('types',$types);

//        dump($list);die();

        $this->assign('id', $id);
        $this->assign('list', $list);
        $this->assign('ftitle',$ftitle);
        $this->assign('title',$title);
        $banner= DB::name('banner')->where(array('tid'=>9,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);
        return $this->fetch();
    }

    public function case_list(){
        $Article = new Article();
        $id=input('id');
        if($id){

        }else{

            $pid=28;
            $id=Db::name('category')->where(array('pid'=>$pid))->order('rank desc')->value('id');
        }

        $ftitle=Db::name('category')->where(array('id'=>$id))->find();
        $title=Db::name('category')->where(array('id'=>$ftitle['pid']))->find();


        $list=$Article->get_list($id);

//        dump($list);die();

        $this->assign('id', $id);
        $this->assign('list', $list);
        $this->assign('ftitle',$ftitle);
        $this->assign('title',$title);
        $banner= DB::name('banner')->where(array('tid'=>9,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);
        return $this->fetch();
    }


    public function  cases_detail(){//
        $banner= DB::name('banner')->where(array('tid'=>9,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);
        $id=input('id');

        $list = Db::name('article')->where(array('id'=>$id,'status'=>1))->find();

        DB::name('article')->where('id',$list['id'])->setInc('dian');

        $map['status']=1;

        $map['tid']=$list['tid'];
        $map['recommend']=1;

        $casetj=DB::name('article')->where($map)->order('sort desc')->order('addtime desc')->limit(8)->select();

        $this->assign('casetj',$casetj);

        $list['imagestc']=explode(",",$list['images']);

//dump($list);die();
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
