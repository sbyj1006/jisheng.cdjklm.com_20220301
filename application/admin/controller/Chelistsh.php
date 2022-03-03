<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
use fast\Tree;
use think\Session;
/**
 *
 *
 * @icon fa fa-circle-o
 */
class Chelistsh extends Backend
{

    /**
     * Article模型对象
     * @var \app\common\model\Chelistsh
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\Chelistsh;


        $tree = Tree::instance();
        $tree->init(collection(Db::name('category')->where('type',1)->order('rank desc,id desc')->select())->toArray(), 'pid');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = [0 => ['type' => '1', 'name' => __('None')]];
        foreach ($this->categorylist as $k => $v) {
            $categorydata[$v['id']] = $v;
        }
        $wherety['group_id']=2;
        $wherety['shopuser']=1;
        $wherety['status']=2;
        $tuoyunList=Db::name('user')->where($wherety)->select();

        $this->assign('tuoyunList',$tuoyunList);


        $wherety['group_id']=2;
        $wherety['shopuser']=1;
        $wherety['status']=2;
        $tuoyunList=Db::name('user')->where($wherety)->select();

        $this->assign('tuoyunList',$tuoyunList);

        $this->view->assign("parentList", $categorydata);
        $this->view->assign("tuoy_ziyList", $this->model->getTuoyziyList());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     */

    public function nav(){
        $tree = Tree::instance();
        $tree->init(collection(Db::name('category')->order('rank desc,id desc')->select())->toArray(), 'pid');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->categorylist as $k => $v) {
            $categorydata[$v['id']] = $v;
        }

        return json($categorydata);

    }





    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            $map['tuoy_ziy']=1;
            $map['statue']=['in',[1,2]];
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model

                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

//            foreach ($list as $row) {
//                $row->visible(['id','tid','title','image','status','recommend','hot','sort','createtime','addtime','type']);
//                $row->visible(['category']);
//				$row->getRelation('category')->visible(['name']);
//            }
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
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }

//                    $nav=db('category')->where(array('pid'=>2,'status'=>'normal'))->order('rank desc')->select();//产品
//                    foreach ($nav as $key => $value) {
//                        $a[]=$value['id'];
//                    }
//                    $nav_2=db('category')->where(array('status'=>'normal'))->where('pid', 'in',$a)->order('rank desc')->select();//产品
//                    foreach ($nav_2 as $key => $value) {
//                        $b[]=$value['id'];
//                    }
//
//                    if (in_array($params['tid'], $b))
//                    {
//                        $params['type'] = 1;
//                    }else{
//                        $params['type'] =0;
//                    }
                    $result = $this->model->allowField(true)->save($params);
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

    /**
     * 编辑
     */
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

                    $tyuser=Db::name('user')->where('id',$params['tuoy_user_id'])->find();
                    $params['ty_statue']=2;
                    if($tyuser['name']){
                        $params['tuoy_user_name']=$tyuser['name'].'-'.$tyuser['mobile'];

                    }else{
                        $params['tuoy_user_name']=$tyuser['nickname'].'-'.$tyuser['mobile'];
                    }

//                    dump($params);die();



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

    /**
     * 删除
     */
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




}
