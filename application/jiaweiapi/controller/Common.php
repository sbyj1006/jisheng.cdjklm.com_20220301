<?php

namespace app\Jiaweiapi\controller;


use app\common\controller\Jiaweiapi;
use app\common\model\Area;
use app\common\model\Version;
use fast\Random;
use think\Config;
use think\Db;

/**
 * 公共接口
 */
class Common extends Jiaweiapi
{
    protected $noNeedLogin = ['init'];
    protected $noNeedRight = '*';


}
