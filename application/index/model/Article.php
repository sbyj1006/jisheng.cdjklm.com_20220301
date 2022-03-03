<?php

namespace app\index\model;

use think\Db;
use think\Model;

class Article extends Model
{

    public function get_list($id){//about
        $map['tid']=$id;
        $map['status']=1;//已发布
        $list = DB::name('article')->where($map)->find();
        return $list;
    }

    public function tysh_list($id){//
        $map['tid']=$id;
        $map['status']=1;//已发布
        $list=DB::name('article')->where($map)->order('addtime desc')->paginate(9,false,['query' => request()->param()]);
        $page = $list->render();
        $result = array(
            'page' => $page,
            'list'=>$list,
        );
        return $result;
    }


    public function welfare_list($id){//
        $map['tid']=$id;
        $map['status']=1;//已发布
        $list=DB::name('article')->where($map)->order('addtime desc')->paginate(9,false,['query' => request()->param()]);
        $page = $list->render();
        $result = array(
            'page' => $page,
            'list'=>$list,
        );
        return $result;
    }


    public function shzr_list($id){//社会责任
        $map['tid']=$id;
        $map['status']=1;//已发布
        $list=DB::name('article')->where($map)->order('addtime desc')->paginate(5,false,['query' => request()->param()]);
        $page = $list->render();
        $result = array(
            'page' => $page,
            'list'=>$list,
        );
        return $result;
    }

    public function news_list($id){//资讯分页
        $map['tid']=$id;
        $map['status']=1;//已发布
        $list=DB::name('article')->where($map)->order('addtime desc')->paginate(6,false,['query' => request()->param()]);
        $page = $list->render();
        $result = array(
            'page' => $page,
            'list'=>$list,
        );
        return $result;
    }

    public function cases_list($id){//资讯分页
        $map['tid']=$id;
        $map['status']=1;//已发布
        $list=DB::name('article')->where($map)->order('addtime desc')->paginate(6,false,['query' => request()->param()]);
        $page = $list->render();
        $result = array(
            'page' => $page,
            'list'=>$list,
        );
        return $result;
    }


    public function fw_cases($id){//服务范围案例
        $map['tid']=$id;
        $map['status']=1;//已发布
        $list=DB::name('cases')->where($map)->order('addtime desc')->paginate(6,false,['query' => request()->param()]);
        $page = $list->render();
        $result = array(
            'page' => $page,
            'list'=>$list,
        );
        return $result;
    }



    public function download_list($id){//资讯分页
        $map['tid']=$id;
        $map['status']=1;//已发布
        $list=DB::name('article')->where($map)->order('addtime desc')->paginate(8,false,['query' => request()->param()]);
        $items=$list->items();

        foreach ($items as $key=>$value){
            $size=Db::name('attachment')->where(array('url'=>$value['videofile']))->find();
            $size_get=$this->renderSize($size['filesize']);
            $items[$key]['size']=$size_get;
        }

        $page = $list->render();
        $result = array(
            'page' => $page,
            'list'=>$items,
        );
        return $result;
    }



    function renderSize($size){
        $size = $size > (1024)
            ? ($size > (1024 * 1024)
                ? ($size > (1024 * 1024 * 1024)
                    ? round($size / (1024 * 1024 * 1024),2 ) . "GB"
                    : round($size / (1024 * 1024),2) . "MB")
                : round($size / 1024,2) . "KB")
            : $size . 'Bytes';
        return $size;
    }



    public function product_list($id){//产品分页
        $map['tid']=$id;
        $map['status']=1;//已发布
        $list=DB::name('article')->where($map)->order('addtime desc')->paginate(8,false,['query' => request()->param()]);
        $page = $list->render();
        $result = array(
            'page' => $page,
            'list'=>$list,
        );
        return $result;
    }

    public function get_list2($id){//产品详情
        $map['id']=$id;
        $map['status']=1;//已发布
        $list = DB::name('article')->where($map)->find();
        DB::name('article')->where('id',$list['id'])->setInc('dian');
        return $list;
    }




}