<?php



namespace app\common\controller;



use app\common\library\Auth;

use think\Config;

use think\Controller;

use think\Hook;

use think\Lang;

use think\Loader;

use think\Validate;

use think\Session;

use think\Db;

/**

 * 前台控制器基类

 */

class Frontend extends Controller

{







    public function _initialize()

    {

        $this->nav();//顶部导航栏

        $this->weizhi();//位置

        $this->middle();//中间部分

        $this->footer_nav();//底部导航栏

        $this->link();//友情链接

        $this->information();//网站基础信息

        $this->u_cart();//网站基础信息

    }


    public  function  nav(){//导航栏



        $token=input('token');

        if($token){

            $token=input('token');

        }else{

            $token='';

        }



        $this->assign('token',$token);



        $nav_title= Db::name('category')->where(array('pid'=>18,'type'=>1))->order('rank desc')->select();

        foreach ($nav_title as $key => $value) {

            $map2['pid'] = $value['id'];

            $z_nav = Db::name('category')->where($map2)->order('rank desc')->select();

            $nav_title[$key]['z_nav'] = $z_nav;

        }



        $this->assign('nav',$nav_title);

    }

    public  function  shopnav(){//导航栏



        $token=input('token');

        if($token){

            $token=input('token');

        }else{

            $token='';

        }



        $this->assign('token',$token);



        $nav_title= Db::name('xshop_category')->where(array('parent_id'=>0))->order('sort desc')->select();

        foreach ($nav_title as $key => $value) {

            $map2['parent_id'] = $value['id'];

            $z_nav = Db::name('xshop_category')->where($map2)->order('sort desc')->select();

            $nav_title[$key]['z_nav'] = $z_nav;

        }



        $this->assign('nav',$nav_title);

    }



    public function  weizhi(){//位置

        $id=input('id');

        $ftitle= Db::name('category')->where(array('id'=>$id,'status'=>'normal'))->find();

        $id2=$ftitle['pid'];

        $title= Db::name('category')->where(array('id'=>$id2,'status'=>'normal'))->find();

        $this->assign('ftitle',$ftitle);

        $this->assign('title',$title);



    }





    public function middle(){//中间部分

        $id=input('id');

        $ftitle=Db::name('category')->where(array('id'=>$id,'status'=>'normal'))->find();

        $id2=$ftitle['pid'];

        $type=Db::name('category')->where(array('id'=>$id2,'status'=>'normal'))->find();//顶级

        $types=Db::name('category')->where(array('pid'=>$id2,'status'=>'normal'))->order('rank desc')->select();//副级

        $this->assign('id', $id);



        $this->assign('type', $type);

        $this->assign('types', $types);

    }



    public function footer_nav(){

        $footer_nav= Db::name('xshop_article_category')->where(array('parent_id'=>22))->order('sort desc')->select();

        foreach ($footer_nav as $key => $value) {

            $map2['parent_id'] = $value['id'];

            $z_nav = Db::name('xshop_article_category')->where($map2)->order('sort desc')->select();

            $footer_nav[$key]['z_nav'] = $z_nav;

        }

        $this->assign('footer_nav', $footer_nav);

    }

    public function information(){//网站基础信息
        $information = Db::name('information')->where(array('status'=>'1'))->find();
        $link = Db::name('link')->where(array('status'=>'1'))->order('rank asc')->select();//友情链接
        $bkxx = Db::name('plate')->where(array('status'=>'1','pid'=>'2'))->order('sort asc')->select();
        $this->assign('information',$information);
        $this->assign('seo_title', $information['seo_title']);
        $this->assign('jj_title', $information['jj_title']);
        $this->assign('seo_wztitle', $information['wz_title']);
        $this->assign('seo_keywords', $information['seo_keywords']);
        $this->assign('seo_description', $information['seo_description']);
        $this->assign('link', $link);
        $this->assign('bkxx', $bkxx);
    }

    public function publics(){

        $information = Db::name('information')->where(array('status'=>'1'))->find();
// dump($information);
        return $information;

    }


public function u_cart(){



    $user= Session::get("user");//

    $cartnew=[];

    $subtotal=0;

    if($user){



        $cart =Db::name('xshop_cart')->where('user_id',$user['id'])->select();

        foreach ($cart as $key=>$val){

            $val['product']=DB::name('xshop_product')->where(array('id'=>$val['product_id']))->find();//当前颜色产品

            $val['product_sku']=DB::name('xshop_product_sku')->where(array('product_id'=>$val['product']['id'],'id'=>$val['size_id']))->find();//产品规格

            $cartnew[$key]=$val;

            $subtotal+=$val['quantity']*$val['product']['price'];

        }

//        dump($cartnew);

        $this->assign('cart', $cartnew);



    }else{
        $open_id= Session::get("open_id");//

if($open_id){}else{
    $str = md5(uniqid(md5(microtime(true)), true)); //生成一个不会重复的字符串
    $open_id = sha1($str); //加密
    Session::set("open_id", $open_id);
}



//        $str = md5(uniqid(md5(microtime(true)), true)); //生成一个不会重复的字符串
//        $str = sha1($str); //加密

        $cart =Db::name('xshop_cart')->where(array('open_id'=>$open_id,'is_user'=>2))->select();

        foreach ($cart as $key=>$val){

            $val['product']=DB::name('xshop_product')->where(array('id'=>$val['product_id']))->find();//当前颜色产品

            $val['product_sku']=DB::name('xshop_product_sku')->where(array('product_id'=>$val['product']['id'],'id'=>$val['size_id']))->find();//产品规格

            $cartnew[$key]=$val;

            $subtotal+=$val['quantity']*$val['product']['price'];

        }

//        dump($cartnew);

        $this->assign('cart', $cartnew);



    }

    $this->assign('subtotal', $subtotal);



}



    public function  link(){

        $link= Db::name('link')->where(array('status'=>1))->order('rank asc')->select();

        $this->assign('link', $link);

    }







    //    获取accesstoken
    public function getAccesstoken(){
//        $appid = '';                     /*小程序appid*/
//        $srcret = '';      /*小程序秘钥*/
        $appid ='wx700f73bbbf0a958c';
        $secret = 'ba97d51c30c176070240aaeadeee294f';

        $tokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
        $getArr=array();
        $tokenArr=json_decode($this->send_post($tokenUrl,$getArr,"GET"));
        $access_token=$tokenArr->access_token;
        return $access_token;
    }
    public function send_post($url, $post_data,$method='POST') {
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => $method, //or GET
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
    public function api_notice_increment($url, $data){
        $ch = curl_init();
        $header=array('Accept-Language:zh-CN','x-appkey:114816004000028','x-apsignature:933931F9124593865313864503D477035C0F6A0C551804320036A2A1C5DF38297C9A4D30BB1714EC53214BD92112FB31B4A6FAB466EEF245710CC83D840D410A7592D262B09D0A5D0FE3A2295A81F32D4C75EBD65FA846004A42248B096EDE2FEE84EDEBEBEC321C237D99483AB51235FCB900AD501C07A9CAD2F415C36DED82','x-apversion:1.0','Content-Type:application/x-www-form-urlencoded','Accept-Charset: utf-8','Accept:application/json','X-APFormat:json');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        //         var_dump($tmpInfo);
        //        exit;
        if (curl_errno($ch)) {
            return false;
        }else{
            // var_dump($tmpInfo);
            return $tmpInfo;
        }
    }
    /*上面生成的是数量限制10万的二维码，下面重写数量不限制的码*/
    /*getWXACodeUnlimit*/
    /*码一，圆形的小程序二维码，数量限制一分钟五千条*/
    /*45009    调用分钟频率受限(目前5000次/分钟，会调整)，如需大量小程序码，建议预生成。
    41030    所传page页面不存在，或者小程序没有发布*/
    public function mpcode($page,$cardid){
        //参数
//        $postdata['scene']="nidaodaodao";
        $postdata['scene']=$cardid;
        // 宽度
        $postdata['width']=430;
        // 页面
        $postdata['page']=$page;
//        $postdata['page']="pages/postcard/postcard";
        // 线条颜色
        $postdata['auto_color']=false;
        //auto_color 为 false 时生效
        $postdata['line_color']=['r'=>'0','g'=>'0','b'=>'0'];
        // 是否有底色为true时是透明的
        $postdata['is_hyaline']=true;
        $post_data = json_encode($postdata);
        $access_token=$this->getAccesstoken();
        $url="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
        $result=$this->api_notice_increment($url,$post_data);
        $data='image/png;base64,'.base64_encode($result);
        return $data;
//        echo '<img src="data:'.$data.'">';
    }
    /*码二，正方形的二维码，数量限制调用十万条*/
    public function qrcodes(){
        $path="pages/postcard/postcard";
        // 宽度
        $postdata['width']=430;
        // 页面
        $postdata['path']=$path;
        $post_data = json_encode($postdata);
        $access_token=$this->getAccesstoken();
        $url="https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=".$access_token;
        $result=$this->api_notice_increment($url,$post_data);
        $data='image/png;base64,'.base64_encode($result);
        echo '<img src="data:'.$data.'">';
    }











}

