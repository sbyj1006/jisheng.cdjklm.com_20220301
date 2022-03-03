<?php

namespace app\api\controller;
use think\Controller;
use app\common\controller\Api;
use think\Db;
/**
 * 首页接口
 */
class Myform extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     *
     */


    /**
     * 回收单录入
     */
    public function huishouadd()
    {
        $thisdb = Db::name('wechat_huishou');
        $data=input('');
//        dump($data);die();
        if($data){
            $datas['chepainum']=$data['chepainum'];

            $datas['isyijia']=$data['isyijia'];
            $datas['yj_price']=$data['yj_price'];
            $datas['yj_note']=$data['yj_note'];


            $datas['chetype_name']=$data['chetype_name'];
            $datas['chetype_id']=$data['chetype_id'];
            $datas['dan_price']=$data['dan_price'];


            $datas['mdd_id']=$data['mdd_id'];
            $datas['mdd_name']=$data['mdd_name'];

             $datas['chejiahao']=$data['chejiahao'];

             $datas['carxingzhi']=$data['carxingzhi'];
            $datas['bz_content']=$data['bz_content'];
            $datas['checolor']=$data['checolor'];
            $datas['tuoy_ziy']=$data['tuoy_ziy'];
            $datas['chepainum']=$data['chepainum'];
            $datas['chexi']=$data['chexi'];
            $datas['chexing']=$data['chexing'];
            $datas['chezbzl']=$data['chezbzl'];
            $datas['f_address']=$data['f_address'];
            $datas['f_name']=$data['f_name'];
            $datas['f_phone']=$data['f_phone'];
            $datas['pinpai']=$data['pinpai'];

            $datas['user_id']=$data['userInfo']['id'];
            $datas['openid']=$data['userInfo']['openid'];
            $datas['user_name']=$data['userInfo']['name'];
            $datas['createtime'] = time();
            $datas['statue']=1;
//            dump($datas);die();
            if($data['sfzfileList']){
                foreach ($data['sfzfileList'] as $key=>$val){

                    $sfzfileList[$key]=$val['url'];

                }

                $datas['sfzfileList']=  implode(",", $sfzfileList);

            }

            if($data['xszfileList']){
                foreach ($data['xszfileList'] as $key=>$val){

                    $xszfileList[$key]=$val['url'];

                }

                $datas['xszfileList']=  implode(",", $xszfileList);

            }


            if($data['chefileList']){
                foreach ($data['chefileList'] as $key=>$val){

                    $chefileList[$key]=$val['url'];

                }

                $datas['chefileList']=  implode(",", $chefileList);

            }


            $where['chepainum']=$data['chepainum'];




            $res=Db::name('wechat_carlist')->where($where)->find();
            if($res){
                $code=300;
                $msg='车牌号已存在，请勿重复添加';
            }else{

                $resadd=Db::name('wechat_huishou')->strict(false)->insert($datas);

                $datasb=$datas;

                if($datas['tuoy_ziy']==2){
                    $datasb['statue']=2;
                }else{
                    $datasb['statue']=1;
                }


                $resaddb=Db::name('wechat_carlist')->strict(false)->insert($datasb);

                if($resadd && $resaddb){
                    $code=200;
                    $msg='信息入库成功';
                }else{

                    $code=400;
                    $msg='信息入库失败';
                }

            }

        }else{
            $code=500;
            $msg='请传入数据';

        }
        exit(json_encode(['code'=>$code, 'adddata'=>$datas, 'msg'=>$msg,

        ]));

    }


    /**
     * 接收单录入
     */
    public function jieshouadd()
    {
        $thisdb = Db::name('wechat_jieshou');
        $data=input('');
        if($data){
            $datas['chepainum']=$data['chepainum'];
            $datas['yslicheng']=$data['yslicheng'];



//dump($datas);die();

            $where['chepainum']=$data['chepainum'];


            $newtime=date('YmdHis',time());
            $datas['jieshounum']='JS-'.'JS-'.$newtime.'-'.$data['userInfo']['id'];



            $res=Db::name('wechat_carlist')->where($where)->find();
            if($res){

                $datas['chejiahao']=$data['chejiahao'];

                $datas['checolor']=$data['checolor'];
                $datas['mdd_id']=$data['mdd_id'];
                $datas['mdd_name']=$data['mdd_name'];



                $datas['chepainum']=$res['chepainum'];
                $datas['f_name']=$res['f_name'];
                $datas['f_phone']=$res['f_phone'];


                $datas['user_id']=$data['userInfo']['id'];
                $datas['openid']=$data['userInfo']['openid'];
                $datas['user_name']=$data['userInfo']['name'];
                $datas['createtime'] = time();
                $datas['statue']=1;


                if(array_key_exists('items', $data)){

                    foreach ($data['items'] as $key=>$val){

                        $datas[$val]=1;

                    }

                }else{

                }

                $wherejs['chepainum']=$data['chepainum'];
//                $wherejs['statue']=$datas['statue'];
                $wherejs['mdd_id']=$data['mdd_id'];
                $resjieshou=$thisdb->where($wherejs)->find();

//                    dump($datas);dump($datasb);die();

                if($resjieshou){
                    if($resjieshou[''])
                        $code=300;
                    $msg='车牌号已添加，请勿重复添加';
                }else{


                    $datasb['chezhu']=$datas['f_name'];
                    if($datas['mdd_id']==19){
                        $datasb['statue']=2;
                    }
                    $datasb['chejiahao']=$datas['chejiahao'];
                    $datasb['mdd_id']=$datas['mdd_id'];

                    $datasb['mdd_name']=$datas['mdd_name'];
                    $datasb['jieshounum']=$datas['jieshounum'];

//                    dump($datas);dump($datasb);die();

                    $resadd=$thisdb->strict(false)->insert($datas);


                    $resaddb=Db::name('wechat_carlist')->where($where)->strict(false)->update($datasb);

                    if($resadd && $resaddb){
                        $code=200;
                        $msg='信息入库成功';
                    }else{

                        $code=400;
                        $msg='信息入库失败';
                    }


                }



            }else{



            }

        }else{
            $code=500;
            $msg='请传入数据';

        }
        exit(json_encode(['code'=>$code, 'adddata'=>$datas, 'msg'=>$msg,

        ]));

    }


    /**
     * 过磅单录入
     */
    public function guobangadd()
    {
        $thisdb = Db::name('wechat_guobang');
        $data=input('');
        if($data){
            $datas['chepainum']=$data['chepainum'];
            $datas['checolor']=$data['checolor'];

            if($data['guobangtime']){
                $datas['guobangtime']=$data['guobangtime'];
            }
            $datas['maozhong']=$data['maozhong'];
            $datas['pizhong']=$data['pizhong'];

            $datas['jinzhong']=$data['jinzhong'];
            $datas['kouzha']=$data['kouzha'];
            $datas['rcdate']=$data['rcdate'];


            $datas['user_id']=$data['userInfo']['id'];
            $datas['openid']=$data['userInfo']['openid'];
            $datas['user_name']=$data['userInfo']['name'];
            $datas['createtime'] = time();
            $datas['statue']=1;
            if(isset($data['bz_content'])){
                $datas['bz_content']=$data['bz_content'];
            }




//dump($datas);die();

            $where['chepainum']=$data['chepainum'];




            $res=Db::name('wechat_carlist')->where($where)->find();
            if($res){

                $resjieshou=$thisdb->where($where)->find();
                if($resjieshou){
                    $code=300;
                    $msg='车牌号已添加，请勿重复添加';
                }else{

                    $datas['f_name']=$res['f_name'];
                    $datas['f_phone']=$res['f_phone'];


                    $resadd=$thisdb->strict(false)->insert($datas);


                    $datasb['statue']=5;

                    $resaddb=Db::name('wechat_carlist')->where($where)->strict(false)->update($datasb);

                    if($resadd && $resaddb){
                        $code=200;
                        $msg='信息入库成功';
                    }else{

                        $code=400;
                        $msg='信息入库失败';
                    }


                }



            }else{
                $code=300;
                $msg='数据库暂无数据';


            }

        }else{
            $code=500;
            $msg='请传入数据';

        }
        exit(json_encode(['code'=>$code, 'adddata'=>$datas, 'msg'=>$msg,

        ]));

    }



    /**
     * 结算单录入
     */
    public function jiesuanadd()
    {
        $thisdb = Db::name('wechat_jiesuan');
        $data=input('');
        if($data){

            $datas['chetype_name']=$data['chetype_name'];
            $datas['chetype_id']=$data['chetype_id'];
            $datas['dan_price']=$data['dan_price'];

            $where['chepainum']=$data['chepainum'];
            $res=Db::name('wechat_carlist')->where($where)->find();

            $datas['chepainum']=$data['chepainum'];
            $datas['checolor']=$data['checolor'];
            $datas['chejiahao']=$data['chejiahao'];

            $datas['user_id']=$data['userInfo']['id'];
            $datas['openid']=$data['userInfo']['openid'];
            $datas['user_name']=$data['userInfo']['name'];

            $datas['createtime'] = time();
            $datas['statue']=1;
            if(isset($data['bz_content'])){
                $datas['bz_content']=$data['bz_content'];
            }
            if(isset($data['butie'])) {
                $datas['butie'] = $data['butie'];
            }
            if(isset($data['canzhiprice'])) {
                $datas['canzhiprice'] = $data['canzhiprice'];

            }
            if(isset($data['cartype'])) {
                $datas['cartype']=$data['cartype'];
            }
            if(isset($data['carxingzhi'])) {
                $datas['carxingzhi']=$data['carxingzhi'];
            }
            if(isset($data['chejidi'])) {
                $datas['chejidi']=$data['chejidi'];
            }
            if(isset($data['chezhu'])) {
                $datas['chezhu']=$data['chezhu'];
            }
            if(isset($data['danprice'])) {
                $datas['danprice']=$data['danprice'];
            }
            if(isset($data['daxie_totalprice'])) {
                $datas['daxie_totalprice']=$data['daxie_totalprice'];
            }
            if(isset($data['drivers'])) {
                $datas['drivers']=$data['drivers'];
            }
            if(isset($data['huishoubm'])) {
                $datas['huishoubm']=$data['huishoubm'];
            }
            if(isset($data['jieshounum'])) {
                $datas['jieshounum']=$data['jieshounum'];
            }
            if(isset($data['jinzhong'])) {
                $datas['jinzhong']=$data['jinzhong'];
            }

            if(isset($data['quejiannum'])) {
                $datas['quejiannum']=$data['quejiannum'];
            }
            if(isset($data['ruchangysren'])) {
                $datas['ruchangysren']=$data['ruchangysren'];
            }
            if(isset($data['ruchangbianhao'])) {
                $datas['ruchangbianhao']=$data['ruchangbianhao'];
            } else{
                //自动生成入场编号
                $datas['ruchangbianhao']=$res['ruchangbianhao'];

            }
            if(isset($data['jsdate'])) {

                $datas['jsdate']=$data['jsdate'];
            }
            if(isset($data['totalprice'])) {
                $datas['totalprice']=$data['totalprice'];
            }
            if(isset($data['tuocheprice'])) {
                $datas['tuocheprice']=$data['tuocheprice'];
            }
            if(isset($data['tuochepz'])) {
                $datas['tuochepz']=$data['tuochepz'];
            }
            if(isset($data['yunshufs'])) {

                $datas['yunshufs']=$data['yunshufs'];
            }
            if(isset($data['zhidanren'])) {
                $datas['zhidanren']=$data['zhidanren'];
            }




//dump($datas);die();


            $datas['quejianmx']= Db::name('wechat_yanshou')->where('chepainum',$data['chepainum'])->order('createtime desc')->value('quejianmx');




            if($res){

                $resjieshou=$thisdb->where($where)->find();
                if($resjieshou){
                    $code=300;
                    $msg='车牌号已添加，请勿重复添加';
                }else{

                    $resadd=$thisdb->strict(false)->insert($datas);


                    $datasb['statue']=4;
                    $datasb['ruchangbianhao']= $datas['ruchangbianhao'];
                    $resaddb=Db::name('wechat_carlist')->where($where)->strict(false)->update($datasb);

                    if($resadd && $resaddb){
                        $code=200;
                        $msg='信息入库成功';
                    }else{

                        $code=400;
                        $msg='信息入库失败';
                    }


                }



            }else{
                $code=300;
                $msg='数据库暂无数据';


            }

        }else{
            $code=500;
            $msg='请传入数据';

        }
        exit(json_encode(['code'=>$code, 'adddata'=>$datas, 'msg'=>$msg,

        ]));

    }






    /**
     * 入场验收单
     */
    public function yanshouadd()
    {
        $thisdb = Db::name('wechat_yanshou');
        $data=input('');

        if($data){
            $where['chepainum']=$data['chepainum'];

            $res=Db::name('wechat_carlist')->where($where)->find();

            $datas['chepainum']=$res['chepainum'];
            $datas['checolor']=$res['checolor'];
            $datas['chejiahao']=$res['chejiahao'];

            $datas['pinpai']=$res['pinpai'];

            $datas['chexi']=$res['chexi'];
            $datas['chexing']=$res['chexing'];
//            $datas['ruchangbianhao']=$res['ruchangbianhao'];
//            $datas['ruchangysren']=$data['ruchangysren'];
            if($data['ruchangbianhao']) {
                $datas['ruchangbianhao']=$data['ruchangbianhao'];
            }else{
                //自动生成入场编号
                $newtime=date('YmdHis',time());
                $datas['ruchangbianhao']='JS-'.'RC-'.$newtime.'-'.$data['userInfo']['id'];

            }

            $datas['user_id']=$data['userInfo']['id'];
            $datas['openid']=$data['userInfo']['openid'];
            $datas['user_name']=$data['userInfo']['name'];
            $datas['createtime'] = time();
            $datas['statue']=1;
            if(isset($data['quejianmx'])){
                $datas['quejianmx']=$data['quejianmx'];
            }

            if(array_key_exists('itemsqj', $data)){

                $datas['itemsqj']=implode(',',$data['itemsqj']);

            }else{

            }


            $datasb['statue']=3 ;
            $datasb['itemsqj']=$datas['itemsqj'];
            $datasb['ruchangbianhao']=$datas['ruchangbianhao'];
//            dump($datas);dump($datasb);die();




            if($res){

                $resjieshou=$thisdb->where($where)->find();
                if($resjieshou){
                    $code=300;
                    $msg='车牌号已添加，请勿重复添加';
                }else{

                    $resadd=$thisdb->strict(false)->insert($datas);


                    $datasb['statue']=3 ;

                    $resaddb=Db::name('wechat_carlist')->where($where)->strict(false)->update($datasb);

                    if($resadd && $resaddb){
                        $code=200;
                        $msg='信息入库成功';
                    }else{

                        $code=400;
                        $msg='信息入库失败';
                    }


                }



            }else{
                $code=300;
                $msg='数据库暂无数据';


            }

        }else{
            $code=500;
            $msg='请传入数据';

        }
        exit(json_encode(['code'=>$code, 'adddata'=>$datas, 'msg'=>$msg,

        ]));

    }




    /**
     * 拆解单录入
     */
    public function chaijieadd()
    {
        $thisdb = Db::name('wechat_chaijie');
        $inputdata=input('');
        $itemsb=$inputdata['itemsb'];
        $data=$inputdata['adddata'];
        $data['userInfo']=$inputdata['userInfo'];
        $data['itemsb']=implode(',',$itemsb);
        $data['chaijfs']=input('chaijfs');
//dump($data);die();
        if($data){
            $where['chepainum']=$data['chepainum'];

            $res=Db::name('wechat_carlist')->where($where)->find();


            $datas['ruchangbianhao']=$data['ruchangbianhao'];


            $datas['user_id']=$data['userInfo']['id'];
            $datas['openid']=$data['userInfo']['openid'];
            $datas['user_name']=$data['userInfo']['name'];
            $datas['createtime'] = time();
            $datas['statue']=1;
            if(isset($data['bz_content'])){
                $datas['bz_content']=$data['bz_content'];
            }




//dump($datas);die();


            if($res){


                $datas['chepainum']=$res['chepainum'];
                $datas['checolor']=$res['checolor'];
                $datas['chejiahao']=$res['chejiahao'];

                $datas['f_phone']=$res['f_phone'];

                $datas['pinpai']=$res['pinpai'];
                $datas['chexi']=$res['chexi'];
                $datas['chexing']=$res['chexing'];

                $datas['jinzhong']=$data['jinzhong'];

                $datas['chaijfs']=$data['chaijfs'];
                $datas['itemsb']=$data['itemsb'];



                $resjieshou=$thisdb->where($where)->find();
                if($resjieshou){
                    $code=300;
                    $msg='车牌号已添加，请勿重复添加';
                }else{
//                    dump($datas);die();
                    $resadd=$thisdb->strict(false)->insert($datas);


                    $datasb['statue']=6;
                    $datasb['itemsb']=$datas['itemsb'];


                    $resaddb=Db::name('wechat_carlist')->where($where)->strict(false)->update($datasb);

                    if($resadd && $resaddb){
                        $code=200;
                        $msg='信息入库成功';
                    }else{

                        $code=400;
                        $msg='信息入库失败';
                    }


                }



            }else{
                $code=300;
                $msg='数据库暂无数据';


            }

        }else{
            $code=500;
            $msg='请传入数据';

        }
        exit(json_encode(['code'=>$code, 'adddata'=>$datas, 'msg'=>$msg,

        ]));

    }




    /**
     * 拆解ruku单录入
     */
    public function chaijierukuadd()
    {
        $thisdb = Db::name('wechat_chaijieruku');
        $inputdata=input('');

        $data=$inputdata['adddata'];
        $data['chaijfs']=input('chaijfs');
        $data['userInfo']=$inputdata['userInfo'];
        if($data){
            $where['chepainum']=$data['chepainum'];

            $res=Db::name('wechat_carlist')->where($where)->find();


            $datas['ruchangbianhao']=$data['ruchangbianhao'];


            $datas['user_id']=$data['userInfo']['id'];
            $datas['openid']=$data['userInfo']['openid'];
            $datas['user_name']=$data['userInfo']['name'];
            $datas['createtime'] = time();
            $datas['statue']=1;
            if(isset($data['bz_content'])){
                $datas['bz_content']=$data['bz_content'];
            }



//dump($datas);die();


            if($res){


                $datas['chepainum']=$res['chepainum'];
                $datas['checolor']=$res['checolor'];
                $datas['chejiahao']=$res['chejiahao'];

                $datas['f_phone']=$res['f_phone'];

                $datas['pinpai']=$res['pinpai'];
                $datas['chexi']=$res['chexi'];
                $datas['chexing']=$res['chexing'];

                $datas['jinzhong']=$data['jinzhong'];

                $datas['chaijfs']=$data['chaijfs'];
                $datas['itemsb']=$res['itemsb'];

                if(isset($data['cjbz'])){
                    $datas['cjbz']=$data['cjbz'];
                }
                if(isset($data['cj_time'])){
                    $datas['cj_time']=$data['cj_time'];
                }
                if(isset($data['xgxcjz'])){
                    $datas['xgxcjz']=$data['xgxcjz'];
                }

                $resjieshou=$thisdb->where($where)->find();
                if($resjieshou){
                    $code=300;
                    $msg='车牌号已添加，请勿重复添加';
                }else{
//                    dump($datas);die();




                    $resadd=$thisdb->strict(false)->insert($datas);


                    $datasb['statue']=7;


                    $resaddb=Db::name('wechat_carlist')->where($where)->strict(false)->update($datasb);
//
                    if($resadd && $resaddb){
                        $code=200;
                        $msg='信息入库成功';
                    }else{

                        $code=400;
                        $msg='信息入库失败';
                    }


                }



            }else{
                $code=300;
                $msg='数据库暂无数据';


            }

        }else{
            $code=500;
            $msg='请传入数据';

        }
        exit(json_encode(['code'=>$code, 'adddata'=>$datas, 'msg'=>$msg,

        ]));

    }





    /**
     * 配件入库录入
     */
    public function peijiankuadd()
    {
        $thisdb = Db::name('wechat_peijianku');
        $inputdata=input('');
        $peijian=$inputdata['peijianadd'];
        $data=$inputdata['adddata'];
        $data['peijian']=$peijian;
        $data['chaijfs']=input('chaijfs');
        $data['userInfo']=$inputdata['userInfo'];
        if($data){

            if($peijian){

                foreach ($peijian as $key=>$val){
                    if(isset($val['rukunum']) && $val['rukunum']){

                        $where['chepainum']=$data['chepainum'];

                        $res=Db::name('wechat_carlist')->where($where)->find();
                        $items=Db::name('category')->where(array('id'=>$val['id'],'type'=>2))->find();

                        $datas['ruchangbianhao']=$res['ruchangbianhao'];


                        $datas['user_id']=$data['userInfo']['id'];
                        $datas['openid']=$data['userInfo']['openid'];
                        $datas['user_name']=$data['userInfo']['name'];
                        $datas['createtime'] = time();
                        $datas['statue']=1;



//dump($items);die();


                        if($res && $items){


                            $datas['chepainum']=$res['chepainum'];
                            $datas['checolor']=$res['checolor'];
                            $datas['chejiahao']=$res['chejiahao'];

                            $datas['f_phone']=$res['f_phone'];

                            $datas['pinpai']=$res['pinpai'];
                            $datas['chexi']=$res['chexi'];
                            $datas['chexing']=$res['chexing'];

                            $datas['itemid']=$val['id'];
                            $datas['itemnum']=$val['shul'];
                            $datas['rukunum']=$val['rukunum'];
                            $datas['itemname']=$items['name'];


                            $whereb['chepainum']=$res['chepainum'];
                            $whereb['itemid']=$datas['itemid'];
                            $resjieshou=$thisdb->where($whereb)->find();
                            if($resjieshou){
                                $code=300;
                                $msg='请勿重复添加';
                            }else{
//                    dump($datas);die();




                                $resadd=$thisdb->strict(false)->insert($datas);


                                $datasb['statue']=6;


//                    $resaddb=Db::name('wechat_carlist')->where($where)->strict(false)->update($datasb);
//
//                    if($resadd && $resaddb){
                                $code=200;
                                $msg='信息入库成功';
//                    }else{
//
//                        $code=400;
//                        $msg='信息入库失败';
//                    }


                            }



                        }else{
                            $code=300;
                            $msg='数据库暂无数据';


                        }




                    }else{
                        $code=310;
                        $msg='请输入入库编号';
                        $datas=$val;

                        exit(json_encode(['code'=>$code, 'adddata'=>$datas, 'msg'=>$msg,

                        ]));
                    }

                }

            }



        }else{
            $code=500;
            $msg='请传入数据';

        }
        exit(json_encode(['code'=>$code, 'adddata'=>$datas, 'msg'=>$msg,

        ]));

    }





}
