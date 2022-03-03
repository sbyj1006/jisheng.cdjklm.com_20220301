<?php

namespace app\Jiaweiapi\controller;

use app\common\controller\Api;
use app\common\controller\Jiaweiapi;
use think\Db;
/**
 * 首页接口
 */
class Index extends Jiaweiapi
{


    public function home_info(){//首页信息
        $id=23;//这是一级导航id
        $category=DB::name('xshop_category')->where('id',$id)->find();//产品分类
        $nav_info_qp=DB::name('xshop_category_info')->where(array('pid'=>$id,'type'=>'1'))->order('sort asc')->select();//全屏信息
        foreach ($nav_info_qp as $key=>$value){
            $nav_info_qp[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$value['image'];
        }

        $nav_info_jz=DB::name('xshop_category_info')->where(array('pid'=>$id,'type'=>'2'))->order('sort asc')->select();//居中信息
        foreach ($nav_info_jz as $key=>$value){
            $nav_info_jz[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$value['image'];
        }


        $nav_info_gg=DB::name('xshop_category_info')->where(array('pid'=>$id,'type'=>'3'))->order('sort asc')->select();//广告信息
        foreach ($nav_info_gg as $key=>$value){
            $nav_info_gg[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$value['image'];
        }

        exit(json_encode(['code'=>200,
            'msg'=>'产品信息',
            'category'=>$category,
            'nav_info_qp'=>$nav_info_qp,
            'nav_info_jz'=>$nav_info_jz,
            'nav_info_gg'=>$nav_info_gg,

        ]));
    }



    public  function  nav(){//导航栏
        $nav_title= Db::name('xshop_category')->where(array('parent_id'=>0))->order('sort desc')->select();
        foreach ($nav_title as $key => $value) {
            $map2['parent_id'] = $value['id'];
            $z_nav = Db::name('xshop_category')->where($map2)->order('sort desc')->select();
            $nav_title[$key]['z_nav'] = $z_nav;
        }
        exit(json_encode(['code'=>200,
            'msg'=>'导航栏获取成功',
            'nav'=>$nav_title,
        ]));
    }



    public function footer_nav(){//底部导航栏
        $footer_nav= Db::name('xshop_article_category')->where(array('parent_id'=>0))->order('sort desc')->select();
        foreach ($footer_nav as $key => $value) {
            $map2['parent_id'] = $value['id'];
            $z_nav = Db::name('xshop_article_category')->where($map2)->order('sort desc')->select();
            $footer_nav[$key]['z_nav'] = $z_nav;
        }
        exit(json_encode(['code'=>200,
            'msg'=>'底部导航栏获取成功',
            'footer_nav'=>$footer_nav,
        ]));
    }


    public  function  znav(){//获取子分类
        $id=input('id');//分类id
        $znav= Db::name('xshop_category')->where(array('parent_id'=>$id))->order('sort desc')->select();
        foreach ($znav as $key=>$value){
            $znav[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$value['image'];
            $f_nav= Db::name('xshop_category')->where(array('parent_id'=>$value['id']))->order('sort desc')->select();
            $znav[$key]['f_nav']=$f_nav;

        }
        exit(json_encode(['code'=>200,
            'msg'=>'获取子分类获取成功',
            'znav'=>$znav,
        ]));
    }



    public function footer_article_info(){//底部点击文章
        $id=input('id');//文章分类id
        $article_category=DB::name('xshop_article_category')->where('id',$id)->find();//文章分类
        $articles_categories=DB::name('xshop_articles_categories')->where('category_id',$id)->find();//文章中间表
        $articles_info=DB::name('xshop_article')->where(array('id'=>$articles_categories['article_id']))->find();
        $articles_info['content'] = str_replace('src="', 'src="http://jiawei.cdjklm.com', $articles_info['content']);
        exit(json_encode(['code'=>200,
            'msg'=>'底部文章信息',
            'articles_info'=>$articles_info,
            'article_category'=>$article_category,
        ]));
    }

    public function nav_info(){//一级导航信息
        $id=input('id');//这是一级导航id
        $category=DB::name('xshop_category')->where('id',$id)->find();//产品分类
        $nav_info_qp=DB::name('xshop_category_info')->where(array('pid'=>$id,'type'=>'1'))->order('sort asc')->select();//全屏信息
        foreach ($nav_info_qp as $key=>$value){
            $nav_info_qp[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$value['image'];
        }

        $nav_info_jz=DB::name('xshop_category_info')->where(array('pid'=>$id,'type'=>'2'))->order('sort asc')->select();//居中信息
        foreach ($nav_info_jz as $key=>$value){
            $nav_info_jz[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$value['image'];
        }


        $nav_info_gg=DB::name('xshop_category_info')->where(array('pid'=>$id,'type'=>'3'))->order('sort asc')->select();//广告信息
        foreach ($nav_info_gg as $key=>$value){
            $nav_info_gg[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$value['image'];
        }

        exit(json_encode(['code'=>200,
            'msg'=>'产品信息',
            'category'=>$category,
            'nav_info_qp'=>$nav_info_qp,
            'nav_info_jz'=>$nav_info_jz,
            'nav_info_gg'=>$nav_info_gg,

        ]));
    }





    public function product_info(){//产品详情信息
        $id=input('id');//这是产品的id
        $product=DB::name('xshop_product')->where('category_id',$id)->find();//当前颜色产品
        $category=DB::name('xshop_category')->where('id',$id)->find();//产品分类
        $f_category=DB::name('xshop_category')->where('parent_id',$category['parent_id'])->order('sort desc')->select();//产品分类
        foreach ($f_category as $key=>$value){
            $f_category[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$value['image'];
        }
        $product_info=DB::name('xshop_product_info')->where(array('pid'=>$product['id'],'on_sale'=>'1'))->order('sort asc')->select();//当前颜色产品的产品信息
        foreach ($product_info as $key=>$value){
            $product_info[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$value['image'];
        }


        $product_banner=DB::name('xshop_product_banner')->where(array('pid'=>$product['id'],'on_sale'=>'1'))->order('sort asc')->select();//当前颜色产品的产品信息
        foreach ($product_banner as $key=>$value){
            $product_banner[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$value['image'];
        }

        $product_images=DB::name('xshop_product_images')->where(array('pid'=>$product['id'],'on_sale'=>'1'))->order('sort asc')->select();//当前颜色产品的产品信息
        foreach ($product_images as $key=>$value){
            $images=explode(',',$value['images']);
            $product_images[$key]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$images;
        }




        exit(json_encode(['code'=>200,
            'msg'=>'产品信息',
            'category'=>$category,
            'product'=>$product,
            'product_info'=>$product_info,
            'f_category'=>$f_category,

        ]));
    }



    public function product_buy_info(){//产品购买信息
        $id=input('id');//这是产品的id
        $product=DB::name('xshop_product')->where('category_id',$id)->find();//当前颜色产品基础信息
        $category=DB::name('xshop_category')->where('id',$id)->find();//产品分类
        $product_info=DB::name('xshop_product_info')->where(array('pid'=>$product['id'],'on_sale'=>'1'))->order('sort asc')->select();//当前颜色产品的产品信息
        $product_article_info=DB::name('xshop_product_article')->where(array('pid'=>$id,'status'=>'1'))->order('sort asc')->select();//产品文章
        $product_sku=DB::name(' xshop_product_sku')->where('product_id',$id)->select();//产品规格
        exit(json_encode(['code'=>200,
            'msg'=>'产品信息',
            'category'=>$category,
            'product'=>$product,
            'product_info'=>$product_info,
            'product_sku'=>$product_sku,
            'product_article_info'=>$product_article_info,

        ]));
    }



    public function product_article(){//产品文章
        $id=input('id');
        $product_article_info=DB::name('xshop_product_article')->where(array('pid'=>$id,'status'=>'1'))->order('sort asc')->select();
        $product_article_info['content'] = str_replace('src="', 'src="http://jiawei.cdjklm.com/', $product_article_info['content']);
        exit(json_encode(['code'=>200,
            'msg'=>'产品文章',
            'product_article_info'=>$product_article_info,
        ]));
    }



    public function message(){//留言
        $datas=input('');
        $postdata=$datas['customer']['ruleForm'];
        $map['america']=$postdata['America'];
        $map['Businessname']=$postdata['Businessname'];
        $map['name']=$postdata['Name'];
        $map['remarks']=$postdata['contents'];
        $map['email']=$postdata['email'];
        $map['phone']=$postdata['mobile'];
        $map['sstate']=$postdata['sstate'];//
        $map['createtime']=time();
        $map['type']=2;

        $id =Db::name('message')->insertGetId($map);
        if($id){
            $message = Db::name('message')->where('id', $id)->find();
            exit(json_encode(['code'=>200, 'msg'=>'Message successful', 'data'=>$message]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'Message failed']));
        }


    }



    public function pt_message(){//普通留言
        $datas=input('');
        $postdata=$datas['customer']['ruleForm'];
        $map['name']=$postdata['Name'];
        $map['remarks']=$postdata['contents'];
        $map['email']=$postdata['email'];
        $map['subject']=$postdata['subject'];
        $map['createtime']=time();
        $map['type']=1;

        $id =Db::name('message')->insertGetId($map);
        if($id){
            $message = Db::name('message')->where('id', $id)->find();
            exit(json_encode(['code'=>200, 'msg'=>'Message successful', 'data'=>$message]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'Message failed']));
        }


    }


    private function makeToken()
    {

        $str = md5(uniqid(md5(microtime(true)), true)); //生成一个不会重复的字符串
        $str = sha1($str); //加密
        return $str;
    }


    public function checkToken($token)//函数用于检验 token 是否存在, 并且更新 token
    {
        $res = Db::name('user')->field('time_out')->where('token', $token)->select();
        if (!empty($res)) {
            //dump(time() - $res[0]['time_out']);
            if (time() - $res[0]['time_out'] > 0) {
               //token长时间未使用而过期，需重新登陆
                exit(json_encode(['code'=>90003, 'msg'=>'relogin']));
            }
            $new_time_out = time() + 604800; //604800是七天
            $res = Db::name('user')
                ->where('token', $token)
                ->update(['time_out' => $new_time_out]);
            if ($res) {
                return true;
//                exit(json_encode(['code'=>90001, 'msg'=>'ok']));
              //token验证成功，time_out刷新成功，可以获取接口信息
            }
        }
        exit(json_encode(['code'=>90002, 'msg'=>'err']));
       //token错误验证失败
    }


    public function login()
    {
            $datas = input('');
            $password = md5($datas['password']);
            $emailcc = $datas['email'];
            $email = Db::name('user')->where('email', $emailcc)->find();
            if ($email == null) {
                exit(json_encode(['code' => 400, 'msg' => 'The mailbox does not exist']));
            } else {
                $userpsisset = Db::name('user')
                    ->where('email', $emailcc)
                    ->where('password', $password)->find();
                if ($userpsisset == null) {
                    exit(json_encode(['code' => 400, 'msg' => 'Email password error']));
                } else {
                    //session('user', $username);
                    $new_time_out = time() + 604800; //604800是七天
                    $token = $this->makeToken();
                    $time_out = strtotime("+7 days");
                    $userinfo = ['time_out' => $new_time_out,
                        'token' => $token];
                    $res = Db::name('user')
                        ->where('email', $emailcc)
                        ->update($userinfo);
                    if ($res) {
                        $user= Db::name('user')
                            ->where('email', $emailcc)
                            ->find();
                        unset($user['password']);
                        exit(json_encode(['code' => 200, 'msg' => 'Login successful', 'data' => $user, 'token' => $user['token']]));
                    }
                }
            }
    }


    public function register(){//注册
        $datas=input('');
        $postdata=$datas['customer']['ruleForm'];
        $map['group_id']=$postdata['group_id'];
        $map['lastname']=$postdata['lastname'];
        $map['name']=$postdata['firstName'];
        $map['email']=$postdata['email'];
        $map['mobile']=$postdata['mobile'];
        $map['password']=md5($postdata['password']);
        $map['type']=$postdata['Subscribe'];
        $map['jointime']=time();
        $map['createtime']=time();
        //  $yz_mobile=Db::name('user')->where('mobile',$mobile)->find();
        $yz_email=Db::name('user')->where('email',$postdata['email'])->find();
        if($yz_email){
            exit(json_encode(['code'=>300, 'msg'=>'The email address already exists']));
        }else{
            $id =Db::name('user')->insertGetId($map);
            if($id){
                $user = Db::name('user')->where('id', $id)->find();
                unset($user['password']);
                exit(json_encode(['code'=>200, 'msg'=>'Register Account Success', 'data'=>$user]));
            }else{
                exit(json_encode(['code'=>400, 'msg'=>'Register Account Failed']));
            }
        }

    }


    public function  edit_userinfo(){//编辑用户个人资料
        $datas = input('');
        $postdata=$datas['customer']['ruleForm'];
        $map['lastname']=$postdata['lastname'];
        $map['name']=$postdata['name'];
        $map['email']=$postdata['email'];
        $map['mobile']=$postdata['mobile'];
        $map['updatetime']=time();
        $token = $datas['userToken'];
        if($this->checkToken($token)){
            $yz_email=Db::name('user')->where('email',$postdata['email'])->find();
            if($yz_email['token']!==$token) {
                exit(json_encode(['code' => 300, 'msg' => 'The email address already exists']));
            }else{
                $user = Db::name('user')->where('token', $token)->update($map);
                if ($user) {
                    $user = Db::name('user')->where('token', $token)->find();
                    unset($user['password']);
                    exit(json_encode(['code' => 200, 'msg' => 'Personal data updated successfully', 'data' => $user]));
                } else {
                    exit(json_encode(['code' => 400, 'msg' => 'Update failed']));
                }
            }
        }else{

            exit(json_encode(['code'=>403, 'msg'=>'登录失败，请登录']));
        }
    }



//    public function  change_password(){//修改密码
//
//        $datas = input('');
//        $password = md5($datas['password']);//新密码
//        $ypassword = md5($datas['ypassword']);//原密码
//        $emailcc = $datas['email'];
//        $email = Db::name('user')->where('email', $emailcc)->find();
//        if ($email == null) {
//            exit(json_encode(['code' => 400, 'msg' => 'The mailbox does not exist']));
//        } else {
//            $userpsisset = Db::name('user')
//                ->where('email', $emailcc)
//                ->where('password', $ypassword)->find();
//            if ($userpsisset == null) {
//                exit(json_encode(['code' => 400, 'msg' => 'Email password error']));
//            } else {
//                $new_time_out = time() + 604800; //604800是七天
//                $token = $this->makeToken();
//                $time_out = strtotime("+7 days");
//                $userinfo = ['password' => $new_time_out,
//                    'token' => $token];
//                $res = Db::name('user')
//                    ->where('email', $emailcc)
//                    ->update($userinfo);
//                if ($res) {
//                    $user = Db::name('user')
//                        ->where('email', $emailcc)
//                        ->find();
//                    exit(json_encode(['code' => 200, 'msg' => 'Login successful', 'data' => $user, 'token' => $user['token']]));
//                }
//            }
//        }
//
//
//}

        public function  address_list(){//地址列表
            $datas = input('');
            $token = $datas['userToken'];
            if($this->checkToken($token)){
                $user = Db::name('user')->where('token', $token)->find();
                $address_list = Db::name('xshop_address')->where('user_id', $user['id'])->order('create_time desc')->select();
                exit(json_encode(['code'=>200, 'msg'=>'地址列表', 'data'=>$address_list]));
            }else{
                exit(json_encode(['code'=>403, 'msg'=>'登录失败，请登录']));
            }

        }


        public function add_address()
        {//添加地址
            $datas = input('');
            $token = $datas['userToken'];
            if ($this->checkToken($token)) {
                $user = Db::name('user')->where('token', $token)->find();
                $postdata = $datas['customer']['ruleForm'];
                $map['lastname'] = $postdata['lastname'];
                $map['firstname'] = $postdata['firstname'];
                $map['mobile'] = $postdata['mobile'];
                $map['address'] = $postdata['address'];
                $map['create_time'] = time();
                $map['user_id'] = $user['id'];
                $add_address = Db::name('xshop_address')->insertGetId($map);
                if ($add_address) {
                    $address = Db::name('xshop_address')->where('id', $add_address)->find();
                    exit(json_encode(['code' => 200, 'msg' => 'Added successfully', 'data' => $address]));
                } else {
                    exit(json_encode(['code' => 400, 'msg' => 'Add failed']));
                }
            }else{

                exit(json_encode(['code'=>403, 'msg'=>'登录失败，请登录']));
            }
        }



    public function edit_address_info()
    {//编辑地址
        $datas = input('');
        $token = $datas['userToken'];
        $id = $datas['id'];
        if ($this->checkToken($token)) {
            $address = Db::name('xshop_address')->where('id',$id)->find();
            if ($address) {
                exit(json_encode(['code' => 200, 'msg' => 'Address info', 'data' => $address]));
            } else {
                exit(json_encode(['code' => 400, 'msg' => 'Address err']));
            }
        }else{

            exit(json_encode(['code'=>403, 'msg'=>'登录失败，请登录']));
        }
    }




    public function edit_address()
    {//编辑地址
        $datas = input('');
        $token = $datas['userToken'];
        $id = $datas['id'];
        if ($this->checkToken($token)) {
            $postdata = $datas['customer']['ruleForm'];
            $map['lastname'] = $postdata['lastname'];
            $map['firstname'] = $postdata['firstname'];
            $map['mobile'] = $postdata['mobile'];
            $map['address'] = $postdata['address'];
            $add_address = Db::name('xshop_address')->where('id',$id)->update($map);
            if ($add_address) {
                $address = Db::name('xshop_address')->where('id', $id)->find();
                exit(json_encode(['code' => 200, 'msg' => 'Edited successfully', 'data' => $address]));
            } else {
                exit(json_encode(['code' => 400, 'msg' => 'Edit failed']));
            }
        }else{

            exit(json_encode(['code'=>403, 'msg'=>'登录失败，请登录']));
        }
    }



    public function del_address()
    {//删除地址
        $datas = input('');
        $token = $datas['userToken'];
        $id = $datas['id'];
        if ($this->checkToken($token)) {
            $add_address = Db::name('xshop_address')->where('id',$id)->delete();
            if ($add_address) {
                exit(json_encode(['code' => 200, 'msg' => 'Del successfully']));
            } else {
                exit(json_encode(['code' => 400, 'msg' => 'Del failed']));
            }
        }else{

            exit(json_encode(['code'=>403, 'msg'=>'登录失败，请登录']));
        }
    }



    public function  edit_subscribe()
    {//修改订阅
        $datas = input('');
        $map['type'] = $datas['type'];//1是已订阅  2没有订阅
        $token = $datas['userToken'];
        if ($this->checkToken($token)) {
            $user = Db::name('user')->where('token', $token)->update($map);
            if ($user) {
                $user = Db::name('user')->where('token', $token)->find();
                unset($user['password']);
                exit(json_encode(['code' => 200, 'msg' => 'Modified successfully', 'data' => $user]));
            } else {
                exit(json_encode(['code' => 400, 'msg' => 'Modification failed']));
            }
        }else{
            exit(json_encode(['code'=>403, 'msg'=>'登录失败，请登录']));
        }
    }











}
