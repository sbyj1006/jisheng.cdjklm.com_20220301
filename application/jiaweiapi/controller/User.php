<?php

namespace app\api\controller;
use app\common\controller\Api;
use function fast\e;
use think\Db;
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
        $appid = input('appid','wx47faf7cd7ff4116b');
        $secret = input('secret','8c4508826a17cd56ac4779e44342f82d');
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
        //输出{"session_key":"GxT18piX7JEvUhazrrcsxw==","openid":"oEE2t4n0eerWnb2mNShyK2ttXLc0"}
        // file_put_contents("../log.txt", $res, FILE_APPEND);

        $obj = json_decode($res); //返回数组或对象
        if($obj->openid != null && $obj->openid != ''){
            exit(json_encode(['code'=>200, 'msg'=>'openid获取成功', 'result'=>$obj->openid]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'openid获取失败','obj'=>$obj]));
        }
    }

    public function getUser()
    {

        if($this->checkOpenid()){
            $openid = input('openid','');
            // 第三季修正
            $data['username'] = input('nickname','');
            $data['avatar'] = input('head','');
            //检索用户表
            $user = Db::name('user')->where('openid', $openid)->find();
            if($user){
                // 第三季修正
                // 当用户昵称或头像为空，同时接收的昵称或头像不为空，说明首次登录授权，需要更新用户表昵称和头像
                if($data['username']!=''){
                    // 更新用户表
                    $data2['username'] = $data['username'];
                    $data2['avatar'] = $data['avatar'];
                    Db::name('user')->where('openid', $openid)->update($data2);
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
                $data['username'] = input('nickname','');
                $data['avatar'] = input('head','');
                $data['jointime']=time();
                $data['createtime']=time();
                $data['token'] = getRandChar(32);
                $data['token_time'] = time();
                $data['group_id'] =1;
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
        $data['username'] = input('nickname','');
        $data['avatar'] = input('head','');
        $data['jointime']=time();
        $data['createtime']=time();
        $data['token'] = getRandChar(32);
        $data['token_time'] = time();
        $data['group_id'] = 1;

        $id = Db::name('user')->insertGetId($data);
        if($id){
            $user = Db::name('user')->where('id', $id)->find();
            exit(json_encode(['code'=>200, 'msg'=>'注册成功', 'data'=>$user]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'注册失败']));
        }
    }


    // 获取指定长度的随机字符串
    function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i ++) {
            $str .= $strPol[rand(0, $max)]; // rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }


    function getUidByOpenid($openid = ''){
        return Db::name('user')->where('openid',$openid)->value('id');
    }


    //获取用户收货地址
    public function getAddress(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $address = Db::name('address')->where('openid', $openid)->select();
            if(!$address){
                exit(json_encode(['code'=>201, 'msg'=>'无收货地址']));
            }
            exit(json_encode(['code'=>200, 'msg'=>'收货地址获取成功', 'info'=>$address]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    //获取某个用户具体某个收货地址
    public function getAddressById(){
        if($this->checkToken()){
            $id = input('id', 0); //地址ID
            $address = Db::name('address')->where('id', $id)->find();
            if(!$address){
                exit(json_encode(['code'=>400, 'msg'=>'无收货地址']));
            }
            exit(json_encode(['code'=>200, 'msg'=>'收货地址获取成功', 'info'=>$address]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    //添加收货地址
    public function addAddress(){
        if($this->checkToken()){
            $openid = input('openid', '');
            //将当前用户所有收货地址取消默认状态
            Db::name('address')->where('openid', $openid)->setField('state', 2);
            //新增
//            $data['name'] = input('name', '');
            $data['name'] = input('consignee', '');
            $data['phone'] = input('mobile', '');
            $data['address'] = input('address', '');
            $data['address_type'] = 1;
            $data['state'] = 1;
            $data['region'] = input('region', '');
            $data['openid'] = $openid;
            $data['addtime'] =time();
            $result = Db::name('address')->insert($data);
            if($result){
                exit(json_encode(['code'=>200, 'msg'=>'收货地址添加成功']));
            }else{
                exit(json_encode(['code'=>400, 'msg'=>'收货地址添加失败']));
            }
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }






    //添加商品评价
    public function addEvaluate(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $uid = getUidByOpenid($openid);

            //新增
            $data['uid'] = $uid;
            $data['ogid'] = input('ogid', 0);
            $data['gid'] = input('gid', 0);
            $data['content'] = input('content', '');
            $result = Db::name('comment')->insert($data);
            if($result){
                // 订单商品表状态设为1
                Db::name('order_goods')->where('id', $data['ogid'])->setField('comment_status', 1);
                exit(json_encode(['code'=>200, 'msg'=>'评价成功']));
            }else{
                exit(json_encode(['code'=>400, 'msg'=>'评价失败']));
            }
        }else{
            exit(json_encode(['code'=>401, 'msg'=>'请重新登录']));
        }
    }

    //删除收货地址
    public function deleteAddress(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $id = input('id', 0); //地址ID
            Db::name('address')->where('id', $id)->delete();
            //判断当前用户如果没有默认收货地址，则将最新一条地址设为默认
            $count = Db::name('address')->where('openid',$openid)->where('state', 1)->count();
            if(!$count){
                //设置最新一条地址为默认
                $count = Db::name('address')->where('openid',$openid)->order('id desc')->limit(1)->setField('state', 1);
            }
            exit(json_encode(['code'=>200, 'msg'=>'收货地址删除成功']));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    //设默认地址
    public function setDefault(){
        if($this->checkToken()){
            $id = input('id', 0); //地址ID
            $openid = input('openid', '');
            //除当前地址外都设为非默认
            Db::name('address')->where('openid',$openid)->where('id', 'neq', $id)->setField('state', 0);
            //再将当前地址设为默认
            Db::name('address')->where('id', $id)->setField('state', 1);
            exit(json_encode(['code'=>200, 'msg'=>'默认地址设置成功']));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    // 编辑收货地址
    public function editAddress() {
        if($this->checkToken()){
            $id = input('id', 0);

            $data['name'] = input('consignee', '');
            $data['region'] = input('region', '');
            $data['address'] = input('address', '');
            $data['phone'] = input('mobile', '');
            $result = Db::name('address')->where('id', $id)->update($data);
            if($result !== false){
                exit(json_encode(['code'=>200, 'msg'=>'收货地址编辑成功']));
            }else{
                exit(json_encode(['code'=>400, 'msg'=>'收货地址编辑失败']));
            }
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    //判断有无默认收货地址
    public function haveAddress(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $count = Db::name('address')->where('openid', $openid)->where('state', 1)->count();
            if($count){
                exit(json_encode(['code'=>200, 'msg'=>'有默认收货地址']));
            }else{
                exit(json_encode(['code'=>401, 'msg'=>'无默认收货地址']));
            }
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

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


    public function  money(){//钱包
        if($this->checkToken()){
            $openid = input('openid', '');
            $user=Db::name('user')->where('openid',$openid)->find();
            $recharge=Db::name('recharge')->where(array('openid'=>$openid,'zt'=>0))->order('addtime desc')->select();//充值记录
            foreach ($recharge as $key=>$value){
                $recharge[$key]['addtime']=date('Y-m-d',$value['addtime']);
                if($value['type']==0){
                    $recharge[$key]['type']='会员充值';
                } else if($value['type']==1){
                    $recharge[$key]['type']='活动充值';
                }else{
                    $recharge[$key]['type']='后台充值';
                }
            }
            exit(json_encode(['code'=>200, 'msg'=>'钱包数据获取成功', 'user'=>$user,'recharge'=> $recharge,'addtime'=> $recharge[$key]['addtime'],'type'=>$recharge[$key]['type'],]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }




    }

    // 旅游订单列表
    public function getLyOrderList(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $status = input('status', 'ALL');
            $page = input('page', 1);
            $map = [];
            switch ($status) {
                case 'ALL':
                    $map['status'] = ['not in',''];
                    break;
                case 'WAITPAY'://待付款
                    $map['status'] = 0;
                    $map['zf_status'] = 0;
                    break;
                case 'WAITSEND'://进行中
                    $map['status'] = ['not in','0,1,2'];
                    $map['zf_status'] = 1;
                    break;
                case 'WAITRECEIVE'://待发团
                    $map['status'] = 2;
                    $map['zf_status'] = 1;
                    break;
                case 'FINISH'://已完成
                    $map['status'] = 1;
                    $map['zf_status'] = 1;
                    break;
                default:
                    break;
            }
            $map['openid'] = $openid;
            $map['type'] = 1;
            $pagea=($page-1)*6;
            $order = Db::name('order')->where($map)->limit($pagea,6)->order('xd_time desc')->select();
            foreach ($order as $key => $value) {
                $order[$key] = array_merge((array)Db::name('article')->where('id',$value['lid'])->find(), (array)$value);
            }
            exit(json_encode(['code'=>200, 'msg'=>'订单列表加载成功', 'info'=>$order,'page'=>$page]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }



    // 酒店订单列表
    public function getJdOrderList(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $status = input('status', 'ALL');
            $page = input('page', 1);
            $map = [];
            switch ($status) {
                case 'ALL':
                    $map['status'] = ['not in',''];
                    break;
                case 'WAITPAY'://待付款
                    $map['status'] = 0;
                    $map['zf_status'] = 0;
                    break;
                case 'WAITSEND'://进行中
                    $map['status'] = ['not in','0,1,2'];
                    $map['zf_status'] = 1;
                    break;
                case 'WAITRECEIVE'://待收货
                    $map['status'] = 2;
                    $map['zf_status'] = 1;
                    break;
                case 'FINISH'://已完成
                    $map['status'] = 1;
                    $map['zf_status'] = 1;
                    break;
                default:
                    break;
            }
            $map['openid'] = $openid;
            $map['type'] = 2;
            $pagea=($page-1)*6;
            $order = Db::name('order')->where($map)->limit($pagea,6)->order('xd_time desc')->select();
            foreach ($order as $key => $value) {
                //获取该订单中商品金额最大的一件商品ID
                $order[$key] = array_merge((array)Db::name('article')->where('id',$value['lid'])->find(), (array)$value);
            }
            exit(json_encode(['code'=>200, 'msg'=>'订单列表加载成功', 'info'=>$order,'page'=>$page]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    // 订单列表
    public function getOrderList(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $status = input('status', 'ALL');
            $page = input('page', 1);
            $map = [];
            switch ($status) {
                case 'ALL':
                    $map['status'] = ['not in',''];
                    break;
                case 'WAITPAY'://待付款
                    $map['status'] = 0;
                    $map['zf_status'] = 0;
                    break;
                case 'WAITSEND'://进行中
                    $map['status'] = ['not in','0,1,2'];
                    $map['zf_status'] = 1;
                    break;
                case 'WAITRECEIVE'://待收货
                    $map['status'] = 2;
                    $map['zf_status'] = 1;
                    break;
                case 'FINISH'://已完成
                    $map['status'] = 1;
                    $map['zf_status'] = 1;
                    break;
                default:
                    break;
            }
            $map['openid'] = $openid;
            $map['type'] = 3;
            $pagea=($page-1)*6;
            $order = Db::name('order')->where($map)->limit($pagea,6)->order('xd_time desc')->select();
            foreach ($order as $key => $value) {
                //获取该订单中商品金额最大的一件商品ID
                $order_goods = Db::name('order_goods')->where('oid',$value['id'])->order('price desc')->limit(1)->find();
                $order[$key] = array_merge((array)Db::name('article')->where('id',$order_goods['gid'])->find(), (array)$value, (array)$order_goods);
            }
            exit(json_encode(['code'=>200, 'msg'=>'订单列表加载成功', 'info'=>$order,'page'=>$page]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    // 团队列表
    public function getTeamList(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $uid = $openid;
            $status = input('status', 'FIRST');
            $page = input('page', 1);
            $map = [];
            switch ($status) {
                case 'FIRST':
                    $map['level'] = 1;
                    break;
                case 'SECOND':
                    $map['level'] = 2;
                    break;
                case 'THIRD':
                    $map['level'] = 3;
                    break;
                default:
                    break;
            }
            $map['pid'] = $uid;
            $config = ['page'=>$page, 'list_rows'=>5];
            $team = Db::name('user_relation')->where($map)->order('id')->paginate(null,false,$config);
            foreach ($team as $key => $value) {
                $team[$key] = Db::name('user')->where('id',$value['uid'])->field('nickname,head,reg_time')->find();
            }
            exit(json_encode(['code'=>200, 'msg'=>'团队列表加载成功', 'info'=>$team]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    // 提现明细列表
    public function getApplymoneyList(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $status = input('status', 'ALL');
            $page = input('page', 1);
            $map = [];
            switch ($status) {
                case 'EXAMINE':
                    $map['state'] = 0;
                    break;
                case 'PAID':
                    $map['state'] = 1;
                    break;
                default:
                    break;
            }
            $map['uid'] = $uid;
            $config = ['page'=>$page, 'list_rows'=>5];
            $apply = Db::name('money_apply')->where($map)->order('id desc')->paginate(null,false,$config);
            exit(json_encode(['code'=>200, 'msg'=>'提现列表加载成功', 'info'=>$apply]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    // 分销订单列表
    public function getDistributeList(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $status = input('status', 'ALL');
            $page = input('page', 1);
            $map = [];
            switch ($status) {
                case 'WAITPAY':
                    $map['state'] = 0;
                    break;
                case 'FROZEN':
                    $map['state'] = 1;
                    break;
                case 'NOFROZEN':
                    $map['state'] = 2;
                    break;
                default:
                    break;
            }
            $map['uid'] = $uid;
            $config = ['page'=>$page, 'list_rows'=>5];
            $order = Db::name('distribute')->where($map)->order('id desc')->paginate(null,false,$config);
            foreach ($order as $key => $value) {
                $order[$key] = array_merge((array)Db::name('user')->where('id',$value['buyer_id'])->field('nickname,head')->find(), (array)$value);
            }
            exit(json_encode(['code'=>200, 'msg'=>'分销订单列表加载成功', 'info'=>$order]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    // 用户提现
    // 先判断用户可提现金额及最低提现金额，是则可提现金额减少，已提现金额增加，另增加一条提现记录
    public function applyMoney(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $uid = getUidByOpenid($openid);
            $data['uid'] = $uid;
            $data['money'] = input('money', 0.00);
            $data['alipay_account'] = input('alipay_account', '');
            $data['alipay_name'] = input('alipay_name', '');

            // 获取用户可提现金额
            $money_cash = Db::name('user')->where('id', $uid)->value('money_cash');
            // 获取配置表最低可提现金额
            $money_linit = Db::name('config')->where('name', 'limit')->value('value');
            if($money_cash < $data['money']){
                exit(json_encode(['code'=>401, 'msg'=>'可提现佣金不足！']));
            }else if($data['money'] < $money_linit){
                exit(json_encode(['code'=>402, 'msg'=>'最低提现佣金为'.$money_linit.'元！']));
            }else{
                // 可提现金额减少，已提现金额增加
                Db::name('user')->where('id', $uid)->setDec('money_cash', $data['money']);
                Db::name('user')->where('id', $uid)->setInc('money_cashed', $data['money']);
                // 增加一条提现记录
                $result = Db::name('money_apply')->insert($data);
                if($result){
                    exit(json_encode(['code'=>200, 'msg'=>'提现成功！']));
                }else{
                    exit(json_encode(['code'=>403, 'msg'=>'提现失败！']));
                }
            }
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    // 评价列表
    public function getEvaluateList(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $uid = getUidByOpenid($openid);
            $status = input('status', '-1');
            $page = input('page', 1);
            $map = [];
            switch ($status) {
                case '0':
                    $map['comment_status'] = 0;
                    break;
                case '1':
                    $map['comment_status'] = 1;
                    break;
                default:
                    break;
            }
            // 根据用户ID获取所有已完成的订单，再查订单所有商品，获取商品信息
            $oids = Db::name('order')->where('uid', $uid)->where('order_status', 1)->column('id');
            $oids = implode(',', $oids); //数组转字符串
            $config = ['page'=>$page, 'list_rows'=>5];
            $order_goods = Db::name('order_goods')->where('oid', 'IN', $oids)->where($map)->order('id desc')->paginate(null,false,$config);
            // 根据GID获取商品图片标题等信息
            $goods = [];
            foreach ($order_goods as $key => $value) {
                $goods[$key] = array_merge((array)Db::name('goods')->where('id',$value['gid'])->field('name,img')->find(), (array)$value);
                // 判断是否有评价信息
                if($value['comment_status'] == 1){
                    $goods[$key]['comment'] = Db::name('comment')->where('ogid', $value['id'])->value('content');
                }
            }
            exit(json_encode(['code'=>200, 'msg'=>'评价列表加载成功', 'info'=>$goods]));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    //取消订单
    public function cancelOrder(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $oid = input('oid');
            Db::name('order')->where('id',$oid)->where('openid',$openid)->setField('status', 5);
            exit(json_encode(['code'=>200, 'msg'=>'订单取消成功']));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    //确认收货
    public function confirmOrder(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $oid = input('oid', 0);
            $map['status']=1;
            Db::name('order')->where('id',$oid)->where('openid',$openid)->setField('status', 1);
//            Db::name('order')->where('id',$oid)->where('openid',$openid)->setField('order_status', 1);

            // 第三季升级分销功能
            // 确认收货：用户表冻结佣金转可提现佣金和分销订单表状态改为解除冻结
//            $order_sn = Db::name('order')->where('id',$oid)->value('order_sn');
//            if($res = $this->checkDistribute($uid)){
//                // 更新分销订单表状态改为解除冻结
//                Db::name('distribute')->where('order_sn', $order_sn)->update(['state'=>2]);
//                // 获取用户ID和对应佣金
//                $dis_data = Db::name('distribute')->where('order_sn', $order_sn)->column('uid,money');
//                //更新用户表冻结佣金转可提现佣金
//                foreach ($dis_data as $key => $value) {
//                    Db::name('user')->where('id', $key)->setDec('money_frozen', $value);
//                    Db::name('user')->where('id', $key)->setInc('money_cash', $value);
//                }
//            }

            exit(json_encode(['code'=>200, 'msg'=>'确认收货成功']));
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    public function lydd_info(){//旅游订单下单信息
        if($this->checkToken()){
            $openid = input('openid', '');
            $gid = input('gid', 0);
            $tid = input('tid', 0);
            $day = input('day', 0);
            $cr = input('cr', 0);
            $et = input('et', 0);
            $goodsInfo = Db::name('article')->where(array('status'=>1,'id'=>$gid))->find();
            $stock_price = DB::name('pro_price')->where(array('id'=>$tid))->find();//套餐
            $suit_price = DB::name('suit_price')->where(array('pro_pri_id'=>$tid,'addtime'=>$day))->find();//套餐价格
            $cr_price =$suit_price['adultprice']*$cr;
            $et_price =$suit_price['childprice']*$et;
            $price=$cr_price+$et_price;
                exit(json_encode(['code'=>200, 'msg'=>'旅游订单详情获取成功',
                    'result'=>$goodsInfo,'day'=>$day,
                    'cr'=>$cr,'et'=>$et,'stock_price'=>$stock_price,
                    'suit_price'=>$suit_price,
                    'price'=>$price,
                ]));

        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }



    public function lyjd_info(){//酒店订单下单信息
        if($this->checkToken()){
            $openid = input('openid', '');
            $gid = input('gid', 0);
            $tid = input('tid', 0);
            $minday = input('minday', 0);
            $maxday = input('maxday', 0);
            $goodsNum = input('goodsNum', 0);//房间数量
            $goodsInfo = Db::name('article')->where(array('status'=>1,'id'=>$gid))->find();
            $stock_price = DB::name('pro_price')->where(array('id'=>$tid))->find();//套餐
            $d1=strtotime($minday);
            $d2=strtotime($maxday);
            $day=round(($d2-$d1)/3600/24);
            $suit_price = DB::name('suit_price')->where(array('pro_pri_id'=>$tid,'addtime'=>$minday))->find();//套餐价格
            $price=$suit_price['adultprice']*$day*$goodsNum;
            exit(json_encode(['code'=>200, 'msg'=>'旅游订单详情获取成功',
                'result'=>$goodsInfo,'day'=>$day,
                'stock_price'=>$stock_price,
                'suit_price'=>$suit_price,
                'price'=>$price,
                'minday'=>$minday,
                'maxday'=>$maxday,
                'goodsNum'=>$goodsNum,
            ]));

        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }

    public  function diffBetweenTwoDays ($minday, $minday)
    {
        $second1 = strtotime($minday);
        $second2 = strtotime($minday);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }


    //获取旅游订单信息
    public function getLyOrderDetail(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $oid = input('oid', 0);

            $order = Db::name('order')->where('id',$oid)->find();
            if($order){
                $goods_info = Db::name('article')->where('id',$order['lid'])->find();//线路详情
                $stock_price = DB::name('pro_price')->where(array('id'=>$order['kid']))->find();//套餐
                $contacts = DB::name('contacts')->where(array('oid'=>$oid))->find();//联系人信息
                $tourist = DB::name('tourist')->where(array('oid'=>$oid))->order('id asc')->select();//游客信息
                exit(json_encode(['code'=>200, 'msg'=>'订单详情获取成功',
                    'result'=>$order,'good_info'=>$goods_info,
                    'tc'=>$stock_price,'contacts'=>$contacts,
                    'tourist'=>$tourist

                ]));
            }else{
                exit(json_encode(['code'=>401, 'msg'=>'该订单不存在']));
            }
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }




    //获取酒店订单信息
    public function getJdOrderDetail(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $oid = input('oid', 0);

            $order = Db::name('order')->where('id',$oid)->find();
            if($order){
                $goods_info = Db::name('article')->where('id',$order['lid'])->find();//线路详情
                $stock_price = DB::name('pro_price')->where(array('id'=>$order['kid']))->find();//套餐
                $contacts = DB::name('contacts')->where(array('oid'=>$oid))->find();//联系人信息
//                $tourist = DB::name('tourist')->where(array('oid'=>$oid))->order('id asc')->select();//游客信息
                exit(json_encode(['code'=>200, 'msg'=>'订单详情获取成功',
                    'result'=>$order,'good_info'=>$goods_info,
                    'tc'=>$stock_price,'contacts'=>$contacts,

                ]));
            }else{
                exit(json_encode(['code'=>401, 'msg'=>'该订单不存在']));
            }
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }



    //获取某个订单信息
    public function getOrderDetail(){
        if($this->checkToken()){
            $openid = input('openid', '');
            $oid = input('oid', 0);
            $orders = Db::name('order_goods')->where('id',$oid)->find();
            $order = Db::name('order')->where('id',$orders['oid'])->where('openid',$openid)->find();
            $address = Db::name('address')->where('id',$order['did'])->find();
            if($order){
                //修改订单状态
                switch ($order['status']) {
                    case 0:
                        $order['status'] = '未完成';
                        break;
                    case 1:
                        $order['status'] = '已完成';
                        break;
                    case 2:
                        $order['status'] = '已发货';
                        break;
                    case 3:
                        $order['status'] = '待发货';
                        break;
                    case 4:
                        $order['status'] = '待发货';
                        break;
                    case 5:
                        $order['status'] = '已取消订单';
                        break;
                    default:
                        break;
                }

                //查询该订单下所有商品信息
                $goods = Db::name('order_goods')->where('id', $oid)->find();
                //根据GID获取商品图片标题等信息
                $goods_info = Db::name('article')->where('id',$goods['gid'])->find();
                exit(json_encode(['code'=>200, 'msg'=>'订单详情获取成功', 'result'=>$order,'address'=>$address,'good_info'=>$goods_info]));
            }else{
                exit(json_encode(['code'=>401, 'msg'=>'该订单不存在']));
            }
        }else{
            exit(json_encode(['code'=>400, 'msg'=>'请重新登录']));
        }
    }




    // 分享推广：
    // 更新用户表（更新自身数据，更新上级ID和上级数据中分销用户数量 1-3条记录）
    // 添加用户关系表 1-3条记录
    public function userShare() {
        // 获取openid，进行查询，没有则注册，有则不做任何处理
        $appid = config('wxpay.appid');
        $secret = config('wxpay.secret');
        $js_code = input('js_code','');
        $parent_id = input('parent_id', 0); // 上线ID

        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $data = array(
            'appid' => $appid,
            'secret' => $secret,
            'js_code' => $js_code,
            'grant_type' => 'authorization_code',
        );
        $res = httpRequest($url, 'POST', $data);
        $obj = json_decode($res); //返回数组或对象
        if($obj->openid != null && $obj->openid != ''){
            // 检查数据表中是否有相同openid
            $count = Db::name('user')->where('openid', $obj->openid)->count();
            if($count){
                exit(json_encode(['code'=>400, 'msg'=>'用户已注册过']));
            }else{
                // 新用户注册
                $user_data['openid'] = $obj->openid;
                $user_data['token'] = getRandChar(32);
                $user_data['token_time'] = time();
                $user_data['pid'] = $parent_id;
                $uid = Db::name('user')->insertGetId($user_data);

                // 更新直接上线一级会员数量
                Db::name('user')->where('id', $parent_id)->setInc('first_member', 1);
                // 添加一条关系数据
                $data['pid'] = $parent_id;
                $data['level'] = 1;
                $data['uid'] = $uid;
                Db::name('user_relation')->insert($data);

                $parents = Db::name('user_relation')->where('uid', $parent_id)->where('level', 'IN', '1,2')->column('level,pid');
                if(!empty($parents)){
                    //该用户有二级或三级上线
                    foreach ($parents as $key => $value) {
                        switch ($key) {
                            case 1:
                                Db::name('user')->where('id', $value)->setInc('second_member', 1);
                                //添加一张关系数据
                                $data = [];
                                $data['pid'] = $value;
                                $data['level'] = 2;
                                $data['uid'] = $uid;
                                Db::name('user_relation')->insert($data);
                                break;

                            case 2:
                                Db::name('user')->where('id', $value)->setInc('third_member', 1);
                                //添加一张关系数据
                                $data = [];
                                $data['pid'] = $value;
                                $data['level'] = 3;
                                $data['uid'] = $uid;
                                Db::name('user_relation')->insert($data);
                                break;

                            default:
                                break;
                        }
                    }
                }
                exit(json_encode(['code'=>200, 'msg'=>'用户推广数据更新成功！']));
            }
        }
    }






}
