<?php

namespace app\index\controller;
use think\Controller;
use think\Db;
use app\common\controller\Frontend;
use think\Session;
use app\common\library\Email;
use app\common\model\Config as ConfigModel;
use think\Exception;
use think\Validate;

class Index extends Frontend
{


    public function  index(){


        $banner= DB::name('banner')->where(array('tid'=>7,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);

//bannerx
        $bannerx= DB::name('plate')->where(array('pid'=>1,'status'=>1))->order('sort asc')->limit(3)->select();
        $this->assign('bannerx',$bannerx);
//sy about
//        $syabout= DB::name('plate')->where(array('pid'=>2,'status'=>1))->order('sort asc')->find();
//        $this->assign('syabout',$syabout);
//aboutx
        $syaboutx= DB::name('plate')->where(array('pid'=>3,'status'=>1))->order('sort asc')->limit(4)->select();
        $this->assign('syaboutx',$syaboutx);

        //sy about
        $youshi= DB::name('plate')->where(array('pid'=>4,'status'=>1))->order('sort asc')->find();
        $this->assign('youshi',$youshi);
//aboutx
        $youshix= DB::name('plate')->where(array('pid'=>5,'status'=>1))->order('sort asc')->limit(4)->select();
        $this->assign('youshix',$youshix);


//        //sy 产品服务
//        $cp_ser= DB::name('category')->where(array('id'=>20,'type'=>1,'status'=>'normal'))->order('rank asc')->find();
//        $this->assign('cp_ser',$cp_ser);
////产品服务内容
//        $cp_serx= DB::name('category')->where(array('pid'=>20,'type'=>1,'status'=>'normal'))->order('rank asc')->limit(12)->select();
//        $this->assign('cp_serx',$cp_serx);
//

        //sy 案例展示
        $case_t= DB::name('category')->where(array('id'=>28,'type'=>1,'status'=>'normal'))->order('rank asc')->find();
        $this->assign('case_t',$case_t);
        //sy 案例展示
//        $news_t= DB::name('category')->where(array('id'=>25,'type'=>1,'status'=>'normal'))->order('rank asc')->find();
//        $this->assign('news_t',$news_t);
        $news_nava=DB::name('category')->where(array('pid'=>24,'type'=>1,'status'=>'normal'))->order('rank desc')->select();//新闻
        foreach ($news_nava as $key => $value) {
            $a[]=$value['id'];
        }
        $wherea['status']=1;
        $wherea['hot']=1;
        $wherea['tid']=array('in',$a);
        $news_ta= DB::name('category')->where(array('id'=>25,'type'=>1,'status'=>'normal'))->order('rank asc')->find();
        $this->assign('news_ta',$news_ta);
        $news_tja= DB::name('article')->where($wherea)->order('addtime asc')->limit(6)->select();
        $this->assign('news_tja',$news_tja);
        $whereb['status']=1;
        $whereb['recommend']=1;
        $whereb['tid']=array('in',$a);
        $news_tjab= DB::name('article')->where($whereb)->order('addtime asc')->limit(10)->select();
        $this->assign('news_tjab',$news_tjab);
        $news_tb= DB::name('category')->where(array('id'=>26,'type'=>1,'status'=>'normal'))->order('rank asc')->find();
        $this->assign('news_tb',$news_tb);
        $news_tjb= DB::name('article')->where(array('status'=>1,'recommend'=>1))->where('tid',26)->order('addtime asc')->limit(6)->select();
        $this->assign('news_tjb',$news_tjb);
        $news_tc= DB::name('category')->where(array('id'=>27,'type'=>1,'status'=>'normal'))->order('rank asc')->find();
        $this->assign('news_tc',$news_tc);
        $news_tjc= DB::name('article')->where(array('status'=>1,'recommend'=>1))->where('tid',27)->order('addtime asc')->limit(6)->select();
        $this->assign('news_tjc',$news_tjc);
        return $this->fetch();
    }

    public function shops(){
        $id=23;
        $ftitle=Db::name('category')->where(array('id'=>$id))->find();
        $title=Db::name('category')->where(array('id'=>$ftitle['pid']))->find();
        $types=Db::name('category')->where(array('pid'=>$ftitle['pid']))->order('rank desc')->select();//顶级


        $banner= DB::name('banner')->where(array('tid'=>17,'status'=>1))->order('sort asc')->select();
        $this->assign('banner',$banner);

        return $this->fetch();

    }

    public function daorujson(){

        $filename="https://www.jeasum.com/public/car_city.json";
        $json_string=file_get_contents($filename);
        $datas=json_decode($json_string,true);
        $updata=[];
        foreach ($datas as $key=>$val){
//            dump($val);die();

            $re=Db::name('carcity')->insert($val);
            if($re){
                dump($val).'-成功';
            }else{
                dump($val).'-失败';
            }

        }



    }


}
