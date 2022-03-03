<?php

namespace app\admin\controller;


use app\common\controller\Backend;
use think\Db;
use fast\Tree;
use think\Session;
/**
 * 统计图表示例
 *
 * @icon   fa fa-charts
 * @remark 展示在FastAdmin中使用Echarts展示丰富多彩的统计图表
 */
class Echarts extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AdminLog');
    }

    /**
     * 查看
     */
    public function index()
    {

        return $this->view->fetch();
    }


    /**
     * 回收车辆数据统计查看
     */
    public function huishou()
    {

        return $this->view->fetch();
    }

    /**
     * 详情
     */
    public function detail($ids)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $this->view->assign("row", $row->toArray());
        return $this->view->fetch();
    }
}
