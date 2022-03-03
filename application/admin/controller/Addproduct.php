<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
use think\Session;

/**
 * 线路套餐
 *
 * @icon fa fa-circle-o
 */
class Addproduct extends Backend
{

    /**
     * SuitPrice模型对象
     * @var \app\admin\model\Chepeijian
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Chepeijian;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("typeList", $this->model->getTypeList());


    }


    public function index()
    {
        $lineid=$this->request->param('ids');//线路id
        $this->assignconfig('lineid',$this->request->param('ids'));
        $this->view->assign("lineid",$lineid);
        //设置过滤方法

        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $chepainum=Db::name('wechat_carlist')->where(array('id'=>$lineid))->value('chepainum');

//            dump($chepainum);


            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->where('chepainum',$chepainum)
                ->order('id asc')
                ->count();


            $list = $this->model
                ->where($where)
                ->where('chepainum',$chepainum)
                ->order('id asc')
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        $lineid=request()->param('ids');//线路id
        $this->view->assign("lineid",$lineid);

        $row = Db::name('wechat_peijianku')->where('id',$lineid)->find();
//        dump($row);die();
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        $this->view->assign("row", $row);
        if ($this->request->isPost()) {

            $params = $this->request->post("row/a");

            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                Db::startTrans();
                try {


                    $pr_c=Db::name('xshop_category')->where('name',$params['itemname'])->find();
                    if($pr_c){
                        $adddata['category_id']=$pr_c['id'];
                    }else{
                        $proc['name']=$params['itemname'];
                        $proc['parent_id']=1;
                        $adddata['category_id']=Db::name('xshop_category')->insertGetId($proc);

                    }

                    $peijian=Db::name('wechat_peijianku')->where('id',$params['ids'])->find();

                    $adddata['pinpai']=$peijian['pinpai'];
                    $adddata['chexi']=$peijian['chexi'];
                    $adddata['chexing']=$peijian['chexing'];
                    $adddata['title']=$peijian['pinpai']."-".$peijian['chexi']."-".$peijian['itemname'];
                    $adddata['chepainum']=$peijian['chepainum'];

                    $adddata['ruchangbianhao']=$peijian['ruchangbianhao'];
                    $adddata['rukunum']=$peijian['rukunum'];
                    $adddata['itemnum']=$peijian['itemnum'];
                    $adddata['price']=$params['price'];
                    $adddata['procode']='JSP'.time().rand(1,9);
                    $adddata['create_user']=$this->auth->id;
                    $adddata['create_time']=time();

                    $addpro=Db::name('xshop_product')->insert($adddata);

                    if($addpro){
                        $updatas['statue']=2;
                        $re=Db::name('wechat_peijianku')->where('id',$params['ids'])->update($updatas);
if($re){

    $carlists=Db::name('wechat_peijianku')->where('chepainum',$peijian['chepainum'])->where('statue',1)->select();
    if($carlists){}else{
        $upcardata['statue']=8;
        $re=Db::name('wechat_carlist')->where('chepainum',$peijian['chepainum'])->update($upcardata);
    }

    $result = true;
}else{
    $result = false;
}
                    }else{
                        $result = false;
                    }
//                    dump($peijian); dump($params); dump($adddata);die();



                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        return $this->view->fetch();
    }


    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }


    public function del($ids = "")
    {
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }


    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


}
