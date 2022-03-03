<?php

namespace app\shopapi\controller;
use app\common\controller\Api;
use function fast\e;
use app\common\controller\Frontend;
use think\Db;
use think\Image;
/**
 * 首页接口
 */
class User extends Common
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];



    public function index(){
        $openid = input('openid', '');
        $user=db('user')->where('openid',$openid)->find();
        $user_count=db('user')->where('uid',$user['id'])->count();//下级用户
        if ($user_count>0){
            $user['count']=$user_count;
        }else{
            $user['count']=0;
        }
        $vip=db('user_group')->where(array('status'=>'normal'))->order('sort asc')->select();
        exit(json_encode(['code'=>200,
            'msg'=>'用户中心获取成功',
            'user'=>$user,  'vip'=>$vip,
        ]));
    }


    public function  vip_info(){
        $id = input('id', '');
        $vip_info=db('user_group')->where(array('status'=>'normal','id'=>$id))->find();
        exit(json_encode(['code'=>200,
            'msg'=>'vip信息获取成功',
            'vip_info'=>$vip_info,
        ]));
    }




    //获取openid
    public function getOpenid(){
        $appid = input('appid','wxca2eca40e6ee5931');
        $secret = input('secret','df3c86b4c363fa2dd67975676d288214');
        $js_code = input('js_code','');

        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $data = array(
            'appid' => $appid,
            'secret' => $secret,
            'js_code' => $js_code,
            'grant_type' => 'authorization_code',
        );

        $res = httpRequest($url, 'POST', $data);
        //输出测试，正式使用请删除下面一行


        $obj = json_decode($res); //返回数组或对象
        if(isset($obj->openid)){
            if($obj->openid != null && $obj->openid != ''){
                exit(json_encode(['code'=>200, 'msg'=>'openid获取成功', 'result'=>$obj->openid]));
            }else{
                exit(json_encode(['code'=>400, 'msg'=>'openid获取失败','obj'=>$obj]));
            }
        }else{
            exit(json_encode(['code'=>420, 'msg'=>'openid获取失败','obj'=>$obj,'datas'=>$data]));
        }
    }

    public function getUser()
    {

        if($this->checkOpenid()){
            $openid = input('openid','');
            // 第三季修正
            $data['nickname'] = input('nickname','');
            $data['avatar'] = input('avatar','');
            //检索用户表
            $user = Db::name('user')->where('openid', $openid)->find();
            if($user){
                // 第三季修正
                // 当用户昵称或头像为空，同时接收的昵称或头像不为空，说明首次登录授权，需要更新用户表昵称和头像
                if($data['nickname']!=''){
                    // 更新用户表
                    $data2['nickname'] = $data['nickname'];
                    $data2['avatar'] = $data['avatar'];
                    Db::name('user')->where('openid', $openid)->update($data2);
                    $user= Db::name('user')->where('openid', $openid)->find();
                }

                if($user['group_id']){
                    $user['teamname']= Db::name('user_group')->where('id', $user['group_id'])->value('name');
                }

                // 重置token
                $user['token'] = $this->resetToken();
                if($user['token']){
                    exit(json_encode(['code'=>200, 'msg'=>'验证成功', 'data'=>$user]));
                }else{
                    exit(json_encode(['code'=>401, 'msg'=>'token重置失败，请重新授权']));
                }
            }else{
                $data['openid'] = input('openid','');
                $data['nickname'] = input('nickname','');
                $data['avatar'] = input('avatar','');
                $data['jointime']=time();
                $data['createtime']=time();
                $data['shopuser']=2;
                $data['token'] = getRandChar(32);
                $data['time_out'] = time();
                $data['group_id'] =99;
                $id = Db::name('user')->insertGetId($data);
                if($id) {
                    exit(json_encode(['code' => 200, 'msg' => '授权成功','data'=>$data]));
                }else{
                    exit(json_encode(['code' => 400, 'msg' => '授权失败']));
                }

            }
        }else{

            exit(json_encode(['code'=>403, 'msg'=>'登录失败，请重新授权']));
        }
    }


    //用户注册添加
    public function register(){
        $data['openid'] = input('openid','');
        // 第三季修正
        $data['nickname'] = input('nickname','');
        $data['avatar'] = input('avatar','');
        $data['jointime']=time();
        $data['createtime']=time();
        $data['token'] = getRandChar(32);
        $data['time_out'] = time();
        $data['group_id'] = 99;
        $data['status']=1;
        $data['shopuser']=2;
        $id = Db::name('user')->insertGetId($data);
        if($id){
            $user = Db::name('user')->where('id', $id)->find();
            exit(json_encode(['code'=>200, 'msg'=>'注册成功', 'data'=>$user]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'注册失败']));
        }
    }
    //用户注册添加
    public function updatauser(){
//        $data['openid'] = input('openid','');
        // 第三季修正
        $inputdata = input('');

        $openid=$inputdata['openid'];
        $token=$inputdata['token'];
        $adddata=$inputdata['adddata'];

        $nowtime=time();
        $tiemstr=substr($nowtime,-4);

        $user= Db::name('user')->where('openid', $openid)->find();

        if($user['my_tj_code']){}else{
            $adddata['my_tj_code']="JSTJ".$tiemstr.getRandnum(6);
        }
        if($user['status']){}else{
            $adddata['status'] = 1;
        }
        if($adddata['tj_code']){

            if($adddata['tj_code']=="JSTJ666888"){
                $resup = Db::name('user')->where('openid', $openid)->update($adddata);
                if($resup){
                    exit(json_encode(['code'=>200, 'msg'=>'提交成功，等待审核','adddata'=>$adddata,]));
                }else{
                    exit(json_encode(['code'=>400, 'msg'=>'官方推荐码注册失败','adddata'=>$adddata,]));
                }

            }else{
                $checode= Db::name('user')->where('my_tj_code', $adddata['tj_code'])->find();
                if($checode){
                    exit(json_encode(['code'=>400, 'msg'=>'正常推荐码','adddata'=>$adddata,]));
                }else{
                    exit(json_encode(['code'=>400, 'msg'=>'暂无此推荐码']));
                }
            }

        }else{

            exit(json_encode(['code'=>400, 'msg'=>'请填写推荐码']));
        }

        dump($adddata);die();
        $id = Db::name('user')->where('openid', $openid)->update($adddata);
        if($id){
            $user = Db::name('user')->where('id', $id)->find();
            exit(json_encode(['code'=>200, 'msg'=>'注册成功', 'data'=>$user]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'注册失败']));
        }





    }



//    function getUidByOpenid($openid = ''){
//        return Db::name('user')->where('openid',$openid)->value('id');
//    }




    public function bangzhu()
    {//帮助中心
        $list=db('xiaoxi')->where(array('del'=>0,'status'=>1,'tid'=>4))->order('addtime desc')->select();
        foreach ($list as $key=>$value){
            $list[$key]['addtime']=date('Y-m-d',$value['addtime']);
        }
        exit(json_encode(['code'=>200, 'msg'=>'数据获取成功', 'list'=>$list,'addtime'=> $list[$key]['addtime'],'content'=>$list[$key]['content'],]));
    }

    public function bz_list(){
        $id=input('id');
        $info=db('xiaoxi')->where(array('del'=>0,'status'=>1,'id'=>$id))->find();
        $addtime=date('Y-m-d',$info['addtime']);
        exit(json_encode(['code'=>200, 'msg'=>'数据获取成功', 'info'=>$info,'addtime'=> $addtime,]));
    }


    public function getdata(){

        $types=input('');
        $seltable=input('seltable');

        $page = input('page', 1);
        $pagea=($page-1)*10;
        if(input('statue')){
            $where['statue']=input('statue');
        }
        if(input('user_id')) {
            $where['user_id'] = input('user_id');
        }

        $list=Db::name($seltable)->where($where)->limit($pagea,10)->select();
        if($list){



            foreach ($list as $key=>$val){


                switch ($val['statue'])
                {
                    case 1:
                        $list[$key]['tags']='已入库'  ;
                        break;

                    default:
                        $list[$key]['tags']='待处理'  ;
                }

                if($seltable=='wechat_huishou' || $seltable=='wechat_carlist'){
                    $imgs=$val['chefileList'];
                    $img=explode ( ',', $imgs );
                    $list[$key]['imageURL']=$img[0];
                }

            }
        }

        exit(json_encode(['code'=>200, 'msg'=>'数据获取成功',
            'list'=>$list, 'page'=>$page,
            'types'=>$types]));
    }



    public function getdatalist(){

        $types=input('');
        $seltable=input('seltable');

        $page = input('page', 1);
        $pagea=($page-1)*10;
        if(input('statue')){
            $statue=input('statue');
            $statue=explode(',',$statue);
//            dump($statue);

            $where['statue']=array('in',$statue);
        }

        if(input('tuoy_ziy')){
            $tuoy_ziy=input('tuoy_ziy');

            $where['tuoy_ziy']=$tuoy_ziy;
        }

        if(input('user_id')) {
            $where['user_id'] = input('user_id');
        }
        if(input('searvalue')) {
            $where['chepainum'] = array('like','%'.input('searvalue').'%');
        }
//        dump($where);
        $list=Db::name($seltable)->where($where)->limit($pagea,10)->select();
        if($list){
            foreach ($list as $key=>$val){

                $imgs=$val['chefileList'];
                $img=explode ( ',', $imgs );

                switch ($val['statue'])
                {
                    case 1:
                        $list[$key]['tags']='待处理'  ;
                        break;
                    case 2:
                        $list[$key]['tags']='待处理'  ;
                        break;
                    default:
                        $list[$key]['tags']='待处理'  ;
                }

                $list[$key]['imageURL']=$img[0];
            }
        }

        exit(json_encode(['code'=>200, 'msg'=>'数据获取成功',
            'list'=>$list, 'page'=>$page,
            'types'=>$types]));
    }

    public function getdatainfo(){
        $inputdata=input('');

        if($inputdata['id']){
            $where['car.id']=$inputdata['id'];
        }
        if($inputdata['user_id']){
            $where['car.user_id']=$inputdata['user_id'];
        }
        $list=Db::name('wechat_carlist')
            ->alias('car')
            ->join('wechat_guobang gb','car.chepainum=gb.chepainum','LEFT')
            ->join('wechat_jiesuan js','car.chepainum=js.chepainum','LEFT')
            ->field(['car.*','gb.jinzhong as jinzhong','js.ruchangbianhao as ruchangbianhao'])
//        ->field(['car.*'])
            ->order('id desc')
            ->where($where)

            ->find();

        $list['dc_date']=date('Y-m-d',time());
        if($list['itemsb']){
            $listitemsc=explode(',',$list['itemsb']);
            $list['pjdatas']= Db::name('category')->where('id','in',$listitemsc)->order('rank desc')->select();
            $list['chaijfs']= Db::name('wechat_chaijie')->where('chepainum',$list['chepainum'])->order('createtime desc')->value('chaijfs');
            $list['quejianmx']= Db::name('wechat_yanshou')->where('chepainum',$list['chepainum'])->order('createtime desc')->value('quejianmx');

        }
        $list['danprice']=intval(Db::name('banner')->where('tid',15)->value('description'));
        exit(json_encode(['code'=>200, 'msg'=>'数据1获取成功',
            'list'=>$list,
            'types'=>$inputdata]));
    }




    public function getdatainfonew(){
        $inputdata=input('');

        if($inputdata['id']){
            $where['car.id']=$inputdata['id'];
        }
        if($inputdata['user_id']){
            $where['car.user_id']=$inputdata['user_id'];
        }
        $list=Db::name('wechat_carlist')
            ->alias('car')
            ->join('wechat_guobang gb','car.chepainum=gb.chepainum','LEFT')
            ->join('wechat_jiesuan js','car.chepainum=js.chepainum','LEFT')
            ->field(['car.*','gb.jinzhong as jinzhong','js.ruchangbianhao as ruchangbianhao'])
//        ->field(['car.*'])
            ->order('id desc')
            ->where($where)

            ->find();

        $list['dc_date']=date('Y-m-d',time());
        if($list['itemsb']){
            $listitemsc=explode(',',$list['itemsb']);
            $list['pjdatas']= Db::name('category')->where('id','in',$listitemsc)->order('rank desc')->select();

            foreach ($list['pjdatas'] as $key=>$val){
                $newpeijku=Db::name('wechat_peijianku')->where(array('chepainum'=>$list['chepainum'],'itemid'=>$val['id']))->find();
                if($newpeijku){
                    $list['pjdatas'][$key]['rukunum']=$newpeijku['rukunum'];
                    $list['pjdatas'][$key]['shul']=$newpeijku['itemnum'];
                }else{
                    $list['pjdatas'][$key]['rukunum']='';
                    $list['pjdatas'][$key]['shul']='';
                }

            }

            $list['chaijfs']= Db::name('wechat_chaijie')->where('chepainum',$list['chepainum'])->order('createtime desc')->value('chaijfs');

        }
        exit(json_encode(['code'=>200, 'msg'=>'数据获取成功',
            'list'=>$list,
            'types'=>$inputdata]));
    }

    public function getinfol(){

        $inputdata=input('');

        $seltable=input('seltable');


        if($inputdata['id']){
            $where['id']=$inputdata['id'];
        }
        if($inputdata['user_id']){
            $where['user_id']=$inputdata['user_id'];
        }
        $listb=Db::name($seltable)

            ->order('id desc')
            ->where($where)
            ->find();

//        $lista['dc_date']=date('Y-m-d',time());
//        var xszfileList = data.xszfileList;    //转成数组类似php的explode函数
//        var sfzfileList = data.sfzfileList;    //转成数组类似php的explode函数
//        var chefileList = data.chefileList;    //转成数组类似php的explode函数

        if($seltable=='wechat_huishou'){

            $xszfileList=explode(',',$listb['xszfileList']);
            foreach ($xszfileList as $key=>$val){
                $newsxszfileList[$key]['url']=$val;
            }

            $sfzfileList=explode(',',$listb['sfzfileList']);
            foreach ($sfzfileList as $key=>$val){
                $newssfzfileList[$key]['url']=$val;
            }

            $chefileList=explode(',',$listb['chefileList']);
            foreach ($chefileList as $key=>$val){
                $newschefileList[$key]['url']=$val;
            }
            $listb['xszfileList']=$newsxszfileList;
            $listb['sfzfileList']=$newssfzfileList;
            $listb['chefileList']=$newschefileList;

        }

        if($seltable=='wechat_chaijie'){
            $listb['itemsel']=explode(',',$listb['itemsb']);
            $listb['pjdatas']= Db::name('category')->where('id','in',$listb['itemsel'])->order('rank desc')->select();

        }



        $list=$listb;

        exit(json_encode(['code'=>200, 'msg'=>'数据获取成功',
            'list'=>$list,
            'types'=>$inputdata]));
    }


    public function getinfolend(){

        $inputdata=input('');

        $seltable=input('seltable');


        if($inputdata['id']){
            $where['id']=$inputdata['id'];
        }
        if($inputdata['user_id']){
            $where['user_id']=$inputdata['user_id'];
        }
        $listb=Db::name($seltable)

            ->order('id desc')
            ->where($where)
            ->find();

//        $lista['dc_date']=date('Y-m-d',time());
//        var xszfileList = data.xszfileList;    //转成数组类似php的explode函数
//        var sfzfileList = data.sfzfileList;    //转成数组类似php的explode函数
//        var chefileList = data.chefileList;    //转成数组类似php的explode函数



        if($seltable=='wechat_chaijieruku'){
            $listb['itemsel']=explode(',',$listb['itemsb']);
        }

        $listb['pjdatas']= Db::name('category')->where('id','in',$listb['itemsel'])->order('rank desc')->select();


        foreach ($listb['pjdatas'] as $key=>$val){
            $newpeijku=Db::name('wechat_peijianku')->where(array('chepainum'=>$listb['chepainum'],'itemid'=>$val['id']))->find();
            if($newpeijku){
                $listb['pjdatas'][$key]['rukunum']=$newpeijku['rukunum'];
                $listb['pjdatas'][$key]['shul']=$newpeijku['itemnum'];
            }else{
                $listb['pjdatas'][$key]['rukunum']='';
                $listb['pjdatas'][$key]['shul']='';
            }

        }

        $list=$listb;

        exit(json_encode(['code'=>200, 'msg'=>'数据获取成功',
            'list'=>$list,
            'types'=>$inputdata]));
    }
    
    public function addAddress(){
        $inputdata=input('');

        dump($inputdata);

    }

    public function getmyuser(){
        $openid=input('openid');
        if($openid){
            $mydatas= Db::name('user')->where(array('openid'=>$openid))->find();
            $datas= Db::name('user')->where(array('tj_code'=>$mydatas['my_tj_code']))->select();


        }else{
            exit(json_encode(['code'=>200,
                'msg'=>'未登录',

            ]));
        }

        exit(json_encode(['code'=>200,
            'msg'=>'首页数据获取成功',
            'mydatas'=>$datas,
        ]));

    }


    public function qrcodes(){

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

    public function mpcode(){
        $cardid=input('cardid');
        //参数
//        $postdata['scene']="nidaodaodao";
        $postdata['scene']=$cardid;
//        $postdata['scene']=12;
        // 宽度
        $postdata['width']=450;
        // 页面
//        $postdata['page']=$page;
        $postdata['page']="pages/member/myinfo/add";
        // 线条颜色
        $postdata['auto_color']=false;
        //auto_color 为 false 时生效
        $postdata['line_color']=['r'=>'0','g'=>'0','b'=>'0'];
        // 是否有底色为true时是透明的
        $postdata['is_hyaline']=false;
        $post_data = json_encode($postdata);
        $access_token=$this->getAccesstoken();
        $url="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
        $result=$this->api_notice_increment($url,$post_data);
        $data='data:image/png;base64,'.base64_encode($result);

        $saves=$this->save_base64($data);
        if($saves['code']==200){
            $dataup['ewmimg']=$saves['url'];
            $userup=1;
            $userup=Db::name('user')->where('id',$cardid)->update($dataup);
            if($userup){
                exit(json_encode(['code'=>200,
                    'msg'=>'二维码生成成功',
                    'mydatas'=>$saves,
                ]));
            }else{
                exit(json_encode(['code'=>310,
                    'msg'=>'二维码保存失败',
                    'mydatas'=>$saves,
                ]));
            }
        }else{
            exit(json_encode(['code'=>320,
                'msg'=>'二维码生成失败',
                'mydatas'=>$saves,
            ]));
        }

//        return $saves;



    }



    public function save_base64($logo_data){
//        $logo_data = $this->request->post('logo_base64','');



        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $logo_data, $result)){
            //图片后缀
//            $type = $result[2];
            $type = 'jpg';
            //保存位置--图片名
            $image_name=date('His').str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).".".$type;
            $image_file_path = '/userewm/'.date('Ymd');
            $image_file = ROOT_PATH.'uploads/shopapi'.$image_file_path;
            $imge_real_url = $image_file.'/'.$image_name;
            $imge_web_url = $image_file_path.'/'.$image_name;
            if (!file_exists($image_file)){
                mkdir($image_file, 0755);
//                fopen($image_file.'\\'.$image_name, "w");
            }
            //解码
            $decode=base64_decode(str_replace($result[1], '', $logo_data));
            if (file_put_contents($imge_real_url, $decode)){


                $data['code']=200;
                $data['imageName']=$image_name;
                $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ?"https://": "http://";
                //$wzurl = $protocol . $_SERVER['HTTP_HOST'];
                $data['url']=$protocol .$_SERVER['HTTP_HOST'].'/uploads/shopapi'.$imge_web_url;
//                dump(ROOT_PATH.'uploads/shopapi'.$imge_web_url);die();
                //上传成功之后，再进行缩放操作
                $image = \think\Image::open(ROOT_PATH.'uploads/shopapi'.$imge_web_url);

                // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
                $image->thumb(200, 200)->save(ROOT_PATH.'uploads/shopapi'.$imge_web_url);

                $data['msg']='保存成功！';
            }else{
                $data['code']=1;
                $data['imgageName']='';
                $data['url']='';
                $data['msg']='图片保存失败！';
            }
        }else{
            $data['code']=1;
            $data['imgageName']='';
            $data['url']='';
            $data['msg']='base64图片格式有误！';
        }
        return $data;

    }

}
