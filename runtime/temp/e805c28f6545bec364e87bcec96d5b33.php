<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:71:"/www/wwwroot/jisheng.cdjklm.com/application/index/view/index/index.html";i:1638340153;s:71:"/www/wwwroot/jisheng.cdjklm.com/application/index/view/public/head.html";i:1639190522;s:71:"/www/wwwroot/jisheng.cdjklm.com/application/index/view/public/navs.html";i:1640313094;s:73:"/www/wwwroot/jisheng.cdjklm.com/application/index/view/public/footer.html";i:1630140836;s:73:"/www/wwwroot/jisheng.cdjklm.com/application/index/view/public/script.html";i:1638338888;}*/ ?>
<!DOCTYPE html>
<!-- 语言 -->
<html lang="zh">
<head>
    <!-- 字符编码格式 -->
    <meta charset="UTF-8">
    <!-- 浏览器兼容问题 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 网页标题 -->

    <title><?php if(isset($list['title'])): ?><?php echo $list['title']; ?>_<?php endif; if(isset($ftitle['name'])): ?><?php echo $ftitle['name']; ?>_<?php endif; if(isset($title['name'])): ?><?php echo $title['name']; ?>_<?php endif; ?><?php echo $seo_title; ?></title>
    <meta name="keywords" content="<?php if(!(empty($list['seo_keywords']) || (($list['seo_keywords'] instanceof \think\Collection || $list['seo_keywords'] instanceof \think\Paginator ) && $list['seo_keywords']->isEmpty()))): ?><?php echo $list['seo_keywords']; ?>_<?php endif; ?><?php echo $seo_keywords; ?> " />
    <meta name="description" content="<?php if(!(empty($list['seo_description']) || (($list['seo_description'] instanceof \think\Collection || $list['seo_description'] instanceof \think\Paginator ) && $list['seo_description']->isEmpty()))): ?><?php echo $list['seo_description']; ?>_<?php endif; ?><?php echo $seo_description; ?>" />

    <!-- bootstrap的样式表 -->
    <link href="/public/home/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/home/fonts/myicon/iconfont.css" rel="stylesheet">

    <!-- 动画样式 -->
    <link href="/public/home/css/animate.css" rel="stylesheet" >
    <!-- 主要内容样式 -->
    <link href="/public/home/css/jisheng.css?8" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/public/home/js/html5shiv.min.js"></script>
    <script src="/public/home/js/respond.min.js"></script>
    <![endif]-->
</head>


<body>




<div class="container-fluid p0 navbar-fixed-top topnav">
    <div class="col-md-3 ">
        <div class="jjloog">
            <img src="<?php echo $information['logo_img']; ?>" class="img-responsive ">
        </div>
        <!-- logo和响应式按钮 -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#jm-navbar-collapse">
                <span class="iconfont icon-list"></span>

            </button>

        </div>
    </div>
    <div class="col-md-8  p0">
        <nav class="navbar navbar-default bootsnav navbar-right ">
            <div class="w100 navbar-yb">


                <div class="col-md-12   p0">



                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse topbj w100" id="jm-navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active">
                                <a href="/">网站首页
                                    <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <?php if(is_array($nav) || $nav instanceof \think\Collection || $nav instanceof \think\Paginator): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <li class=" <?php if(count($vo['z_nav'])>1): ?> dropdown <?php endif; if($title['id'] == $vo['id']): endif; ?>">
                                <a href="<?php echo $vo['route']; ?>" <?php if(count($vo['z_nav'])>1): ?> class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-haspopup="true" aria-expanded="false"<?php endif; ?>><?php echo $vo['name']; ?>
                                </a>
                                <?php if(count($vo['z_nav'])>1): ?>
                                <ul class="dropdown-menu animated" >
                                    <?php if(is_array($vo['z_nav']) || $vo['z_nav'] instanceof \think\Collection || $vo['z_nav'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['z_nav'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
                                    <li><a href="<?php echo url($vo2['route'].$vo2['id']); ?>"><?php echo $vo2['name']; ?></a></li>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; endif; else: echo "" ;endif; ?>




                            <li class="navtel">
                                <a href=""> <span class="iconfont icon-dianhualianxi"></span><?php echo $information['landline']; ?></a>
                            </li>



                        </ul>

                    </div><!-- /.navbar-collapse -->

                </div>


            </div>

        </nav>


    </div>

</div>



<div class="w100 yb-banner">
    <div class="container-fluid p0">



        <div class="swiper-container-b">
            <div class="swiper-wrapper">

                <?php if(is_array($banner) || $banner instanceof \think\Collection || $banner instanceof \think\Paginator): $i = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <div class="swiper-slide">
                    <a href="<?php echo $vo['url']; ?>">
                        <img src="<?php echo $vo['image']; ?>" class="ani center-block img-responsive"    swiper-animate-effect="fadeInUp"  swiper-animate-duration=".8s" alt="First slide">
                        <div class="text">
                            <div class="texta color-fff ani"   swiper-animate-effect="fadeInUp" swiper-animate-duration=".8s" swiper-animate-delay=".5s" >
                                <?php echo $vo['title']; ?>
                            </div>
                            <div class="textb color-fff ani"   swiper-animate-effect="fadeInUp" swiper-animate-duration=".8s" swiper-animate-delay=".5s" >
                                <?php echo $vo['description']; ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>




            </div>



            <div class="ban-x">

                <div class="container-fluid p0">

                    <div class="col-md-10 col-md-offset-1">


                        <div class="banx-cen">
                            <?php if(is_array($bannerx) || $bannerx instanceof \think\Collection || $bannerx instanceof \think\Paginator): $i = 0; $__LIST__ = $bannerx;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <div class="ban-li">
                                <img src="<?php echo $vo['image']; ?>" class="img-responsive">
                                <span><?php echo $vo['title']; ?></span>
                            </div>
                            <?php endforeach; endif; else: echo "" ;endif; ?>


                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Add Pagination -->
        <div class="swiper-pagination swiper-pagination-b  swiper-pagination-white"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-prev swiper-button-prev-b  swiper-button-white">  </div>
        <div class="swiper-button-next swiper-button-next-b  swiper-button-white"> </div>




    </div>



</div>
<div class="ban-sea">

    <form class="ban-form" >

        <div class="form-group">
            <input class="form-control" placeholder="输入车架号查询车辆状态">
        </div>
        <div class="form-group sea-btn">
            <span class="iconfont icon-sousuo"></span>
            <input type="button" class="btn form-control" value="查询">
        </div>

    </form>

</div>
<div class="page-main ">
    <div class="container p0">

        <div class="col-md-12 p8">
            <div class="page-tit ">
                <div class="page-cn mt20"><?php echo $news_ta['ename']; ?> <span><?php echo $news_ta['name']; ?></span></div>
                <div class="page-en"><?php echo $news_ta['description']; ?></div>
            </div>
<div class="col-md-6">

<div class="news-l">
    <div class="swiper-wrapper">
        <?php if(is_array($news_tja) || $news_tja instanceof \think\Collection || $news_tja instanceof \think\Paginator): $i = 0; $__LIST__ = $news_tja;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <div class="swiper-slide">
            <div class="news-div">

                <div class="news-img-s">
                    <a href="<?php echo url('/news_detail/'.$vo['id']); ?>"> <img src="<?php echo $vo['image']; ?>" class="img-responsive"></a>
                </div>

                <div class="newsbody">
                    <a href="<?php echo url('/news_detail/'.$vo['id']); ?>" class="newstit">
                        <?php echo $vo['title']; ?>
                    </a>
                    <div class="newsnote">
                        <?php echo $vo['description']; ?>
                    </div>
                    <div class="news-top ">
                        <div class="news-time"><?php echo date("Y-m-d",strtotime($vo['addtime'])); ?></div>

                    </div>
                    <div class="newsmore">
                        <a href="<?php echo url('/news_detail/'.$vo['id']); ?>">READ MORE <div class="iconfont icon-jiantou7"></div></a>
                    </div>
                </div>

            </div>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>



    </div>


    <!-- Add Pagination -->
    <div class="swiper-pagination swiper-new-p"></div>
    <!-- Add Navigation -->
    <div class="jiant-pre newsfy "> <span class="iconfont icon-jiantou5"></span> </div>
    <div class="jiant-next newsfy "> <span class="iconfont icon-jiantou10"></span> </div>


</div>


</div>
<div class="col-md-6">
    <ul class="news-ul">
        <?php if(is_array($news_tjab) || $news_tjab instanceof \think\Collection || $news_tjab instanceof \think\Paginator): $i = 0; $__LIST__ = $news_tjab;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <li class="w100">
            <div class="news-div">

                    <a href="<?php echo url('/news_detail/'.$vo['id']); ?>" class="newstit">
                        <span class="iconfont icon-jiantou10"></span>     <?php echo $vo['title']; ?>
                    </a>

            </div>
        </li>
        <?php endforeach; endif; else: echo "" ;endif; ?>

    </ul>

</div>


        </div>

    </div>

</div>

<!---->
<div class="page-main page-yous" style="background: url('<?php echo $youshi['image']; ?>')">
    <div class="container p0">

        <div class="col-md-12 p8">

            <div class="yous-x">
                <div class="yous-cen">

                    <?php if(is_array($syaboutx) || $syaboutx instanceof \think\Collection || $syaboutx instanceof \think\Paginator): $i = 0; $__LIST__ = $syaboutx;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <div class="yous-li">
                        <div class="t"><?php echo $vo['title']; ?><small><?php echo $vo['titles']; ?></small></div>
                        <span><?php echo $vo['description']; ?></span>
                    </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>


                </div>

            </div>


            <div class="page-tit page-tit-a mt60">
                <div class="page-cn mt20"><?php echo $youshi['title']; ?> <span><?php echo $youshi['titles']; ?></span></div>
                <div class="page-en"><?php echo $youshi['description']; ?></div>
            </div>

            <div class="yous-cen">

                <?php if(is_array($youshix) || $youshix instanceof \think\Collection || $youshix instanceof \think\Paginator): $i = 0; $__LIST__ = $youshix;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <div class="yous-li-b">
                    <div class="t"><?php echo $vo['title']; ?></div>
                    <div class="notes"><?php echo $vo['description']; ?></div>
                </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>

            </div>

        </div>

    </div>

</div>

<!---->
<div class="page-main ">
    <div class="container p0">

        <div class="col-md-12 p8">
            <div class="page-tit ">
                <div class="page-cn mt20"><?php echo $news_tb['ename']; ?> <span><?php echo $news_tb['name']; ?></span></div>
                <div class="page-en"><?php echo $news_tb['description']; ?></div>
            </div>

            <ul class="news-ul">
                <?php if(is_array($news_tjb) || $news_tjb instanceof \think\Collection || $news_tjb instanceof \think\Paginator): $i = 0; $__LIST__ = $news_tjb;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li class="col-md-4">
                    <div class="news-div">

                        <div class="news-img-s">
                            <a href="<?php echo url('/news_detail/'.$vo['id']); ?>"> <img src="<?php echo $vo['image']; ?>" class="img-responsive"></a>
                        </div>

                        <div class="newsbody">
                            <a href="<?php echo url('/news_detail/'.$vo['id']); ?>" class="newstit">
                                <?php echo $vo['title']; ?>
                            </a>
                            <div class="newsnote">
                                <?php echo $vo['description']; ?>
                            </div>
                            <div class="news-top ">
                                <div class="news-time"><?php echo date("Y-m-d",strtotime($vo['addtime'])); ?></div>

                            </div>
                            <div class="newsmore">
                                <a href="<?php echo url('/news_detail/'.$vo['id']); ?>">READ MORE <div class="iconfont icon-jiantou7"></div></a>
                            </div>
                        </div>

                    </div>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>



        </div>

    </div>

</div>


<div class="page-main page-case">
    <div class="container-fluid p0">

        <div class="col-md-11 col-md-offset-1 p0">

            <div class="sy-case">

                <div class="col-md-5">
                    <div class="page-tit mt60">
                        <div class="page-cn mt20"><?php echo $case_t['ename']; ?> <span><?php echo $case_t['name']; ?></span></div>
                        <div class="page-en"><?php echo $case_t['description']; ?></div>
                    </div>

                    <div class="case-tit sycase-tit">
                        <a  href="<?php echo url('/cases'); ?>" class="cn">报废汽车回收</a>
                        <div class="en">Scrap car recycling</div>
                    </div>

                    <div class="casemore">
                        <a href="<?php echo url('/cases'); ?>"> <div class="iconfont icon-jiantou12"></div> 查看详情</a>
                    </div>

                </div>
                <div class="col-md-7 p0">

                    <img src="/public/home/images/caseimg.jpg" class="img-responsive">

                </div>


            </div>

        </div>

    </div>

</div>


<div class="page-main ">
    <div class="container p0">

        <div class="col-md-12 p8">
            <div class="page-tit ">
                <div class="page-cn mt20"><?php echo $news_tc['ename']; ?> <span><?php echo $news_tc['name']; ?></span></div>
                <div class="page-en"><?php echo $news_tc['description']; ?></div>
            </div>

            <ul class="news-ul">
                <?php if(is_array($news_tjc) || $news_tjc instanceof \think\Collection || $news_tjc instanceof \think\Paginator): $i = 0; $__LIST__ = $news_tjc;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li class="col-md-4">
                    <div class="news-div">

                        <div class="news-img-s">
                            <a href="<?php echo url('/news_detail/'.$vo['id']); ?>"> <img src="<?php echo $vo['image']; ?>" class="img-responsive"></a>
                        </div>

                        <div class="newsbody">
                            <a href="<?php echo url('/news_detail/'.$vo['id']); ?>" class="newstit">
                                <?php echo $vo['title']; ?>
                            </a>
                            <div class="newsnote">
                                <?php echo $vo['description']; ?>
                            </div>
                            <div class="news-top ">
                                <div class="news-time"><?php echo date("Y-m-d",strtotime($vo['addtime'])); ?></div>

                            </div>
                            <div class="newsmore">
                                <a href="<?php echo url('/news_detail/'.$vo['id']); ?>">READ MORE <div class="iconfont icon-jiantou7"></div></a>
                            </div>
                        </div>

                    </div>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>



        </div>

    </div>

</div>



<div class="foota">
    <div class="links">
        <div class="container-fluid p0">

            <div class="col-md-10 col-md-offset-1">
                <div class="linkstit">
                    友情链接：
                </div>
                <div class="linkscon">
<?php if(is_array($link) || $link instanceof \think\Collection || $link instanceof \think\Paginator): $i = 0; $__LIST__ = $link;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <a href="<?php echo $vo['url']; ?>" class="links-a" target="_blank"><?php echo $vo['title']; ?></a>
<?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>

        </div>
    </div>
    <div class="container-fluid p0">

        <div class="col-md-10 col-md-offset-1">

            <div class="col-md-6 p8">

                <div class="w100">
                    <div class="foottit"><img src="<?php echo $information['dlogo_img']; ?>" class="img-responsive"></div>
                    <ul class="foot-nav">
                        <li>
                            <a href="/" >
                                <div class=" foot-nav-cn">返回首页</div>

                            </a>

                        </li>

                        <li>
                            <a href="/abouts" >
                                <div class=" foot-nav-cn">关于我们</div>

                            </a>

                        </li>

                        <li>
                            <a href="/products" >
                                <div class=" foot-nav-cn">产品服务</div>

                            </a>

                        </li>

                        <li>
                            <a href="/newsc" >
                                <div class=" foot-nav-cn">新闻资讯</div>

                            </a>

                        </li>

                        <li>
                            <a href="/contact" >
                                <div class=" foot-nav-cn">联系我们</div>

                            </a>

                        </li>


                    </ul>

                </div>




            </div>
            <div class="col-md-4 p8">

                <ul class="footlx">
                    <li><div class="iconfont icon-dizhi_"> 公司地址：<?php echo $information['address']; ?> </div></li>
                    <li><div class="iconfont icon-dianhua2"> 电话：<?php echo $information['landline']; ?> 邮箱：<?php echo $information['email']; ?>    </div></li>
                    <li><div class="iconfont icon-list1">   <a href="<?php echo $information['beian_url']; ?>"><?php echo $information['beian_number']; ?></a> </div></li>

                </ul>


            </div>
            <div class="col-md-2">

                <div class="footewm">
                    <img src="<?php echo $information['ewm_img']; ?>" class="img-responsive fr">
                </div>

            </div>
            <div class="footbq">
                <div class="fl"><?php echo $information['copyright']; ?></div>

            </div>
        </div>

    </div>

</div>


<script src="/public/home/js/jquery-1.11.0.min.js"></script>
<script src="/public/home/js/bootstrap.js"></script>
<script src="/public/home/js/bootsnav.js"></script>
<!--二维码弹出js	-->

<link href="/public/home/swiper5/css/swiper.min.css?v=1" rel="stylesheet">
<script src="/public/home/swiper5/js/swiper.min.js"></script>
<script src="/public/home/swiper5/js/swiper.animate1.0.3.min.js"></script>
<script>
    var swiper = new Swiper('.swiper-container-b', {
        speed: 600,
        parallax: true,
        autoHeight: true, //高度随内容变化
        pagination: {
            el: '.swiper-pagination-b',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next-b',
            prevEl: '.swiper-button-prev-b',
        },
        loopedSlides: 5,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        on:{
            init: function(){
                swiperAnimateCache(this); //隐藏动画元素
                this.emit('slideChangeTransitionEnd');//在初始化时触发一次slideChangeTransitionEnd事件
            },
            slideChangeTransitionEnd: function(){
                swiperAnimate(this); //每个slide切换结束时运行当前slide动画
//                this.slides.eq(this.activeIndex).find('.ani').removeClass('ani');//动画只展示一次
            }
        },
    });


    var swiper = new Swiper('.news-l', {
        speed: 600,
        parallax: true,
        autoplay: {
            delay: 4000,//1秒切换一次
        },

        loop: true,
        pagination: {
            el: '.swiper-new-p',
            clickable: true,
        },
        navigation: {
            nextEl: '.jiant-pre',
            prevEl: '.jiant-next',
        },
        on:{
            init: function(){
                swiperAnimateCache(this); //隐藏动画元素
                this.emit('slideChangeTransitionEnd');//在初始化时触发一次slideChangeTransitionEnd事件
            },
            slideChangeTransitionEnd: function(){
                swiperAnimate(this); //每个slide切换结束时运行当前slide动画
                this.slides.eq(this.activeIndex).find('.ani').removeClass('ani');//动画只展示一次
            }
        }
    });



    if((navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i))){
        //phone
        var swiper=new Swiper('.cp-ser',{
            slidesPerView:1,
            spaceBetween:15,
            loopedSlides: 1,
            loop: true,
            autoplay: {
                delay: 6500,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.news-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.cp-ser-next',
                prevEl: '.cp-ser-prev',
            },
        })


        var swiper=new Swiper('.aboutcen',{
            slidesPerView:2,
            spaceBetween:15,
            loopedSlides: 1,
            loop: true,
            speed:3500,//匀速时间
            autoplay: {
                delay: 0,
                stopOnLastSlide: false,
                disableOnInteraction: false,
            },
        })

    }else {
        //PC
        var swiper=new Swiper('.cp-ser',{
            slidesPerView:3,
            spaceBetween:25,
            loopedSlides: 1,
            loop: true,
            autoplay: {
                delay: 6500,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.news-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.cp-ser-next',
                prevEl: '.cp-ser-prev',
            },
        });

        var swiper=new Swiper('.aboutcen',{
            slidesPerView:4,
            spaceBetween:15,
            loopedSlides: 1,
            loop: true,
            speed:4000,//匀速时间
            autoplay: {
                delay: 0,
                stopOnLastSlide: false,
                disableOnInteraction: false,
            },
        })



    }


    $(function(){
        var img = $(".cp-serz-img");
        realWidth =img.width();
        // alert(realWidth);
        realHeight = realWidth*0.6;
        img.css({height:realHeight});


        var img1 = $(".jk-img");
        realWidth =img1.width();
        // alert(realWidth);
        realHeight = realWidth*0.6;
        img1.css({height:realHeight});
    });

    $(window).resize(function(){
        var img = $(".cp-serz-img");
        realWidth =img.width();
        // alert(realWidth);
        realHeight = realWidth*0.6;
        img.css({height:realHeight});

        var img1 = $(".jk-img");
        realWidth =img1.width();
        // alert(realWidth);
        realHeight = realWidth*0.6;
        img1.css({height:realHeight});
    })


</script>


</body>

</html>