<?php

namespace app\api\controller;
use app\common\controller\Api;
use function fast\e;
use app\common\controller\Frontend;

use think\Db;
require_once "./extend/phpqrcode/phpqrcode.php";
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
        $appid = input('appid','wx700f73bbbf0a958c');
        $secret = input('secret','ba97d51c30c176070240aaeadeee294f');
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
                $data['shopuser']=1;
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
        $data['shopuser']=1;

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


        if($user['status']){}else{
            $adddata['status'] = 1;
        }

//        if($user['my_tj_code']){}else{
//            $adddata['my_tj_code']="JSTJ".$tiemstr.getRandnum(6);
//        }
//        if($adddata['tj_code']){
//
//            if($adddata['tj_code']=="JSTJ666888"){
//                $resup = Db::name('user')->where('openid', $openid)->update($adddata);
//                if($resup){
//                    exit(json_encode(['code'=>200, 'msg'=>'提交成功，等待审核','adddata'=>$adddata,]));
//                }else{
//                    exit(json_encode(['code'=>400, 'msg'=>'官方推荐码注册失败','adddata'=>$adddata,]));
//                }
//
//            }else{
//                $checode= Db::name('user')->where('my_tj_code', $adddata['tj_code'])->find();
//                if($checode){
//                    exit(json_encode(['code'=>400, 'msg'=>'正常推荐码','adddata'=>$adddata,]));
//                }else{
//                    exit(json_encode(['code'=>400, 'msg'=>'暂无此推荐码']));
//                }
//            }
//
//        }else{
//
//            exit(json_encode(['code'=>400, 'msg'=>'请填写推荐码']));
//        }
//
//        dump($adddata);die();

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
        $gettype=input('gettype');
        if($gettype=='jieshou'){

            if(input('user_id')) {
                $where['tuoy_user_id'] = input('user_id');
            }
            else{
                exit(json_encode(['code'=>200, 'msg'=>'未接收到登录人员信息获取成功',
                    'list'=>$types, ]));
            }

            if(input('mdd_id')) {
                $where['mdd_id'] = 19;
            }else{
                $where['mdd_id'] = ['<>',19];
            }

        }else{

        }

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
            ->field(['car.*','gb.jinzhong as jinzhong'])
//        ->field(['car.*'])
            ->order('id desc')
            ->where($where)

            ->find();
//dump($where);die();
        $list['dc_date']=date('Y-m-d',time());
        if($list){


            $code=mb_substr($list['chepainum'], 0 ,2,"utf-8");

            $chejidi= Db::name('carcity')->where('code',$code)->find();
            $list['chejidi']=$chejidi['province'].'-'.$chejidi['city'];
            $list['huishoubm']= Db::name('wechat_jieshou')->where('chepainum',$list['chepainum'])->value('user_name');

            $list['ruchangysren']= Db::name('wechat_yanshou')->where('chepainum',$list['chepainum'])->value('user_name');

            if($list['itemsb']){
                $listitemsc=explode(',',$list['itemsb']);

                $list['pjdatas']= Db::name('category')->where('id','in',$listitemsc)->order('rank desc')->select();
                $list['chaijfs']= Db::name('wechat_chaijie')->where('chepainum',$list['chepainum'])->order('createtime desc')->value('chaijfs');
                $list['quejianmx']= Db::name('wechat_yanshou')->where('chepainum',$list['chepainum'])->order('createtime desc')->value('quejianmx');

            }
            $list['quejiankoukuan']=0;
            if($list['itemsqj']){
                $listcitems=explode(',',$list['itemsqj']);
                $list['itemqjlist']= Db::name('category')->where('id','in',$listcitems)->order('rank desc')->select();
                $list['quejiana']=Db::name('category')->where('id',$list['itemqjlist'][0]['pid'])->order('rank desc')->select();
                foreach ($list['itemqjlist'] as $key=>$val){
                    $list['quejiankoukuan']+=$val['prices'];
                }

            }

            $list['canzhiprice']=$list['jinzhong']/1000*$list['dan_price'];

//            dump($list);die();
        }
        exit(json_encode(['code'=>200, 'msg'=>'数据1获取成功',
            'list'=>$list,
            'types'=>$inputdata]));
    }




    public function getdatainfonew(){
        $inputdata=input('');
        if($inputdata['ruchangbianhao']){
            $where['car.ruchangbianhao']=$inputdata['ruchangbianhao'];
        }
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

        if($seltable=='wechat_yanshou'){
            $listb['quejiankoukuan']=0;
            $listcitems=explode(',',$listb['itemsqj']);
            $listb['itemqjlist']= Db::name('category')->where('id','in',$listcitems)->order('rank desc')->select();
            $listb['quejiana']=Db::name('category')->where('id',$listb['itemqjlist'][0]['pid'])->order('rank desc')->select();
            foreach ($listb['itemqjlist'] as $key=>$val){
                $listb['quejiankoukuan']+=$val['prices'];
            }

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

    public function getewm(){
        $newnumber=input('newnumber');
//        $newnumber='12312';
        $ewmcar=Db::name('wechat_carlist')->where('ruchangbianhao',$newnumber)->find();
        if($ewmcar){

            if($ewmcar['ewm_img']){
                exit(json_encode(['code'=>200,
                    'msg'=>'二维码1数据获取成功',
                    'datas'=>$ewmcar['ewm_img'],
                ]));
            }else{
                //生成二维码图片
                $object = new \QRcode();
//        $url='http://mqd.cdjk.net/product/id/1/auid/'.$uid;
                $url=$newnumber;
                $level=3;
                $size=4;
//        $filename=$this->getRandChar(16);
                $ad =  $_SERVER['DOCUMENT_ROOT']. '/uploads/wechata_img/ewm/'.$newnumber.'.jpg';
                $errorCorrectionLevel =intval($level) ;//容错级别
                $matrixPointSize = intval($size);//生成图片大小
                $object->png($url,  $ad, $errorCorrectionLevel, $matrixPointSize, 2);

                $ewm_img= '/uploads/wechata_img/ewm/'.$newnumber.'.jpg';
                $upd['ewm_img']=$ewm_img;
                $uperwm=Db::name('wechat_carlist')->where('ruchangbianhao',$newnumber)->update($upd);
                if($uperwm){
                    exit(json_encode(['code'=>220,
                        'msg'=>'二维码数据获取成功',
                        'datas'=>$ewm_img,
                    ]));
                }else{
                    exit(json_encode(['code'=>200,
                        'msg'=>'二维码保存失败',
                        'datas'=>$ewm_img,
                    ]));
                }
            }


        }else{
            exit(json_encode(['code'=>330,
                'msg'=>'未查到车辆信息',
                'lists'=>'no',
            ]));
        }

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
