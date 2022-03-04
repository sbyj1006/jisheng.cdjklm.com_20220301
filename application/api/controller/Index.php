<?php

namespace app\api\controller;
use think\Controller;
use app\common\controller\Api;
use think\Db;
/**
 * 首页接口
 */
class Index extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     *
     */
    public function indexa()
    {

        exit(json_encode(['code'=>200,
            'msg'=>'首页数据获取成功',

        ]));

    }


    public function getdataa(){
        $id=input('id');
        if($id){

        }else{
            $id=3;
        }
        $datas= Db::name('banner_type')->where(array('id'=>$id))->order('sort desc')->find();
        $datas['webinfo']=Db::name('banner')->where('tid',14)->find();
        exit(json_encode(['code'=>200,
            'msg'=>'首页数据获取成功',
            'id'=>$id,'datas'=>$datas,
        ]));

    }

    /**
     * 获取品牌
     */

    public function getcarbrand(){
        $id=input('id');
        $carbrand=input('carbrand');

        if($id){

        }else{

        }
        if($carbrand){
            $where['bname']=array('like','%'.$carbrand.'%');
            $datas= Db::name('car_brand')->where($where)->select();

            $code=200;
        }else{
            $code=300;
            $datas=[];
        }

        exit(json_encode(['code'=>$code,
            'msg'=>'car数据获取成功',
            'id'=>$carbrand,'datas'=>$datas,
        ]));
    }


    /**
     * 获取车系
     */

    public function getcarseries(){
        $id=input('id');
        $carseries=input('carseries');
        $car_brand=input('car_brand');

        if($id){

        }else{

        }
        if($car_brand ){

            $branddata= Db::name('car_brand')->where('bname',$car_brand)->find();

            if($branddata){
                $where['bcode']=$branddata['bcode'];
            }

            $where['sname']=array('like','%'.$carseries.'%');
            $datas= Db::name('car_series')->where($where)->select();

            $code=200;
        }else{
            $code=300;
            $datas=[];
        }

        exit(json_encode(['code'=>$code,
            'msg'=>'car数据获取成功',
            'where'=>$where,'datas'=>$datas,
        ]));
    }


    /**
     * 获取车型
     */

    public function getcarmodel(){
        $id=input('id');
        $car_model=input('car_model');
        $car_series=input('car_series');

        if($id){

        }else{

        }
        if($car_series ){

            $branddata= Db::name('car_series')->where('sname',$car_series)->find();

            if($branddata){
                $where['scode']=$branddata['scode'];
            }

            $where['mname']=array('like','%'.$car_model.'%');
            $datas= Db::name('car_model')->where($where)->select();

            $code=200;
        }else{
            $code=300;
            $datas=[];
        }

        exit(json_encode(['code'=>$code,
            'msg'=>'car车型数据获取成功',
            'where'=>$where,'datas'=>$datas,
        ]));
    }

    public function getxieyi(){
        $id=input('id');
        if($id){

        }else{
            $id=23;
        }
        $datas= Db::name('banner')->where(array('tid'=>$id,'status'=>1))->order('sort desc')->find();
        exit(json_encode(['code'=>200,
            'msg'=>'协议数据获取成功',
            'id'=>$id,'datas'=>$datas,
        ]));

    }


    public function getpeijian(){
        $uid=input('uid');

        $datas= Db::name('category')->where(array('pid'=>1))->order('rank desc')->select();

        exit(json_encode(['code'=>200,
            'msg'=>'配件数据获取成功',
            'datas'=>$datas,
        ]));

    }

    public function getmdddata(){
        $id=input('id');

        $datas= Db::name('banner_type')->where(array('pid'=>$id))->order('sort desc')->select();

        exit(json_encode(['code'=>200,
            'msg'=>'目的地数据获取成功',
            'datas'=>$datas,
        ]));

    }

    public function getdataqujian(){
        $pid=input('pid');

        $datas= Db::name('category')->where(array('pid'=>$pid))->order('rank desc')->select();

        exit(json_encode(['code'=>200,
            'msg'=>'缺件数据获取成功',
            'datas'=>$datas,
        ]));

    }

    public function getchetype(){
        $pid=input('pid');

        $datas= Db::name('categoryche')->where(array('pid'=>$pid))->order('rank desc')->select();

        exit(json_encode(['code'=>200,
            'msg'=>'车辆类型数据获取成功',
            'datas'=>$datas,
        ]));

    }
//    public function suiji(){
//        $ruchangbianhao='RC'.time().rand(1,9);
//dump($ruchangbianhao);
//    }

    public function wxupload(){

        date_default_timezone_set("Asia/Shanghai"); //设置时区
        $code = $_FILES['file'];//获取小程序传来的图片
        if(is_uploaded_file($_FILES['file']['tmp_name'])) {
            //把文件转存到你希望的目录（不要使用copy函数）
            $uploaded_file=$_FILES['file']['tmp_name'];
            $username = "min_img";
            //我们给每个用户动态的创建一个文件夹
            $user_path=$_SERVER['DOCUMENT_ROOT']."/uploads/wechata_img/".$username;
            //判断该用户文件夹是否已经有这个文件夹
            if(!file_exists($user_path)) {
                //mkdir($user_path);
                mkdir($user_path,0777,true);
            }

//$move_to_file=$user_path."/".$_FILES['file']['name'];
            $file_true_name=$_FILES['file']['name'];

            $new_images_name="/".time().rand(1,1000)."-".date("Y-m-d").substr($file_true_name,strrpos($file_true_name,"."));

            $move_to_file=$user_path.$new_images_name;
            //strrops($file_true,".")查找“.”在字符串中最后一次出现的位置
            $uploaded_file_return="/uploads/wechata_img/".$username.$new_images_name;
//echo "$uploaded_file   $move_to_file";
            if(move_uploaded_file($uploaded_file,iconv("utf-8","gb2312",$move_to_file))) {

                return $uploaded_file_return;
//                return "/uploads/wechata_img/".$username."/".$move_to_file;
            } else {
                return "上传失败".date("Y-m-d H:i:sa");

            }
        } else {
            return "上传失败".date("Y-m-d H:i:sa");
        }



    }



}
