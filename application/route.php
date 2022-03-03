<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +-------------------------------------------------------------------

use think\Route;
//// 注册路由到index模块的News控制器的read操作

Route::rule('/','index');

Route::rule('abouts','Index/About/index');
Route::rule('about/:id','Index/About/index');
Route::rule('teams_info/:id','Index/About/teams_info');

Route::rule('newsc','Index/News/index');//
Route::rule('news/:id','Index/News/index');//
Route::rule('news_detail/:id','Index/News/news_detail');

Route::rule('products','Index/Product/index');//
Route::rule('product/:id','Index/Product/index');//
Route::rule('product_detail/:id','Index/Product/pro_detail');


Route::rule('cases','Index/Cases/index');//
Route::rule('case/:id','Index/Cases/index');//
Route::rule('cases_detail/:id','Index/Cases/cases_detail');

Route::rule('hzhbs','Index/Hzhb/index');
Route::rule('hzhb/:id','Index/Hzhb/index');
Route::rule('hzhb_info/:id','Index/Hzhb/hzhb_info');

Route::rule('contact','Index/Contact/index');//

Route::rule('jshuishou','Index/Jshuishou/index');

Route::rule('shops','Index/Index/shops');
