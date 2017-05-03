<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title><?php if(!empty($title)): echo ($title); else: ?>{meta.$title}<?php endif; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="keywords" content="<?php echo ($meta["keywords"]); ?>"/>
    <meta name="description" content="<?php echo ($meta["description"]); ?>"/>
    <link rel="stylesheet" href="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/css/weui.min.css">
    <link rel="stylesheet" href="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/css/jquery-weui.min.css">
    <link rel="stylesheet" href="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/css/iconfont.css">
    <link rel="stylesheet" href="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/css/global.css">
    <link rel="stylesheet" href="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/css/font-awesome.min.css">
	
	<script src="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/js/jquery-2.1.4.js"></script>
	<script src="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/js/jquery-weui.min.js"></script>
</head>

<body ontouchstart>
<!--header-->
<header class='weui-header'>
    <a href="javascript:history.back(-1);" class="turn" title=""><i class="iconfont">&#xe601;</i>订阅号</a>
    <h1 class="weui-title">我的账本</h1>
</header>

<!--account-->
<div class="account_item">
    <a class="filter" href="<?php echo U('search');?>">筛选</a>
    <div class="account_user">
        <div class="account_head"><a href=""><img src="http://img.ycg.qq.com/201832/0/df03acb1-728f-495d-b636-40603f99a8f1/preview"/></a></div>
        <h2>Andy.chen</h2>
        <p><small>总余额(元)</small></p>
        <h1>2,508.89</h1>
    </div>
    <div class="weui-row">
        <div class="weui-col-33"><a href=""><span>405.20</span><p class="black_color">昨日收入</p></a></div>
        <div class="weui-col-33"><a href=""><span>405.20</span><p class="black_color">7日收入</p></a></div>
        <div class="weui-col-33"><a href=""><span>405.20</span><p class="black_color">月日收入</p></a></div>
    </div>
</div>
<div class="account_tab weui_tab">
    <div class="weui_navbar">
        <a class="weui_navbar_item weui_bar_item_on" href="#tab1"><span>明细</span></a>
        <a id="tab2-btn" class="weui_navbar_item" href="#tab2"><span>按月统计</span></a>
        <a id="tab3-btn" class="weui_navbar_item" href="#tab3"><span>按周统计</span></a>
    </div>
    <div class="weui_tab_bd">
        <div id="tab1" class="weui_tab_bd_item weui_tab_bd_item_active">
            <div class="weui_cell_title">
                <p>2016.08.12<span><b class="blue">630</b>/<b class="green">399</b>/<b class="gay">100</b></span></p>
            </div>
            <div class="weui_cells weui_cells_access">
                <a class="weui_cell" href="javascript:;">
                    <div class="weui_cell_hd">
                       <i class="iconfont">&#xe605;</i>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>支付成功</p>
                        <small>19 : 35 :43</small>
                    </div>
                    <div class="weui_cell_ft">
                        + 1,260.00
                        <p class="org">-0.65</p>
                    </div>
                </a>
                <a class="weui_cell" href="javascript:;">
                    <div class="weui_cell_hd">
                        <i class="iconfont">&#xe605;</i>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>支付成功</p>
                        <small>19 : 35 :43</small>
                    </div>
                    <div class="weui_cell_ft">
                        + 1,260.00
                        <p class="org">-0.65</p>
                    </div>
                </a>
                <a class="weui_cell" href="javascript:;">
                    <div class="weui_cell_hd">
                        <i class="iconfont">&#xe605;</i>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>支付成功</p>
                        <small>19 : 35 :43</small>
                    </div>
                    <div class="weui_cell_ft">
                        + 1,260.00
                        <p class="org">-0.65</p>
                    </div>
                </a>
                <a class="weui_cell" href="javascript:;">
                    <div class="weui_cell_hd">
                        <i class="iconfont">&#xe605;</i>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>支付成功</p>
                        <small>19 : 35 :43</small>
                    </div>
                    <div class="weui_cell_ft">
                        + 1,260.00
                        <p class="org">-0.65</p>
                    </div>
                </a>
            </div>

        </div>
        <div id="tab2" class="weui_tab_bd_item">
            <div class="mychart_box">
                <div class="mychart">
                    <div id="main" style="height: 250px;width: 100%;"></div>
                </div>
            </div>
            <div class="mychart_content">
                <dl>
                    <dt class="title_box">月份</dt>
                    <dt class="title_items"><span>支付宝</span><span>微信</span><span class="bor_non">现在支付</span></dt>
                    <dt class="title_items"><span>支付宝</span><span>微信</span><span class="bor_non">现在支付</span></dt>
                </dl>
                <dl>
                    <dd class="title_box">7月</dd>
                    <dd class="tips_items"><span><b class="blue">630</b>/<b class="green">399</b>/<b class="gay">100</b></span></dd>
                    <dd class="tips_items"><span><b class="blue">5</b>/<b class="green">3</b>/<b class="gay">4</b></span></dd>
                </dl>
                <dl>
                    <dd class="title_box">7月</dd>
                    <dd class="tips_items"><span><b class="blue">630</b>/<b class="green">399</b>/<b class="gay">100</b></span></dd>
                    <dd class="tips_items"><span><b class="blue">5</b>/<b class="green">3</b>/<b class="gay">4</b></span></dd>
                </dl>
                <dl>
                    <dd class="title_box">7月</dd>
                    <dd class="tips_items"><span><b class="blue">630</b>/<b class="green">399</b>/<b class="gay">100</b></span></dd>
                    <dd class="tips_items"><span><b class="blue">5</b>/<b class="green">3</b>/<b class="gay">4</b></span></dd>
                </dl>
            </div>
        </div>
        <div id="tab3" class="weui_tab_bd_item">
            <div class="mychart_box">
                <div class="mychart">
                    <div id="main2" style="height: 250px;width: 80%;"></div>
                </div>
            </div>
            <div class="mychart_content">
                <dl>
                    <dt class="title_box">月份</dt>
                    <dt class="title_items"><span>支付宝</span><span>微信</span><span>现在支付</span></dt>
                    <dt class="title_items"><span>支付宝</span><span>微信</span><span>现在支付</span></dt>
                </dl>
                <dl>
                    <dd class="title_box">7月</dd>
                    <dd class="tips_items"><span><b class="blue">630</b>/<b class="green">399</b>/<b class="gay">100</b></span></dd>
                    <dd class="tips_items"><span><b class="blue">5</b>/<b class="green">3</b>/<b class="gay">4</b></span></dd>
                </dl>
                <dl>
                    <dd class="title_box">7月</dd>
                    <dd class="tips_items"><span><b class="blue">630</b>/<b class="green">399</b>/<b class="gay">100</b></span></dd>
                    <dd class="tips_items"><span><b class="blue">5</b>/<b class="green">3</b>/<b class="gay">4</b></span></dd>
                </dl>
                <dl>
                    <dd class="title_box">7月</dd>
                    <dd class="tips_items"><span><b class="blue">630</b>/<b class="green">399</b>/<b class="gay">100</b></span></dd>
                    <dd class="tips_items"><span><b class="blue">5</b>/<b class="green">3</b>/<b class="gay">4</b></span></dd>
                </dl>
            </div>
        </div>
    </div>
</div>




</body>
</html>
<!--<script src="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan//Public/js/echarts.min.js"></script>-->
<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js?_v_=1471406922963"></script>
<script src="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan//Public/js/touch.min.js"></script>
<script>
    touch.on('#main2', 'touchstart', function(ev){
        ev.preventDefault();
    });

    var target = document.getElementById("main");
    //target.style.webkitTransition = 'all ease 0.2s';

    touch.on('#main', 'swiperight', function(ev){
        //this.style.webkitTransform = "translate3d(" + rt + "px,0,0)";
        //alert("月统计向右滑动.");
        cmonth++;
        setzinfo();
    });

    touch.on('#main', 'swipeleft', function(ev){
        //alert("月统计向左滑动.");
        cmonth--;
        setzinfo();
        //this.style.webkitTransform = "translate3d(-" + this.offsetLeft + "px,0,0)";
    });

    touch.on('#main', 'touchstart', function(ev){
        ev.preventDefault();
    });
    var mtarget = document.getElementById("main2");
    //target.style.webkitTransition = 'all ease 0.2s';

    touch.on('#main2', 'swiperight', function(ev){
        //this.style.webkitTransform = "translate3d(" + rt + "px,0,0)";
        //alert("周统计向右滑动.");
        setminfo(0);
    });

    touch.on('#main2', 'swipeleft', function(ev){
        //alert("周统计向左滑动.");
        setminfo(1);
        //this.style.webkitTransform = "translate3d(-" + this.offsetLeft + "px,0,0)";
    });

</script>
<script type="text/javascript">

    var body_width = $(document.body).width();
    $("#main").width(1175);
    $("#main2").width(1175);
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));
    option = {
       /* tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },*/

        legend: {
            bottom:5,
            data:['支付宝','微信','银联']
        },

		color:['#00a0e9', '#0dc91a','#006a65'],

        calculable : true,
        xAxis : [
            {
                type : 'category',
                data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'支付宝',
                type:'bar',
                stack: '去年',
                barWidth : 20,
				//itemStyle:{normal:{color:['#00a0e9']}},
                data:[100, 396, 158,100, 396, 158,100, 396, 158,100, 396,158]
            },
            {
                name:'微信',
                type:'bar',
                stack: '去年',
                barWidth : 20,
				//itemStyle:{normal:{color:['#0dc91a']}},
                data:[96, 296, 198,100, 396, 158,100, 396, 158,100, 396,158]
            },
            {
                name:'银联',
                type:'bar',
                stack: '去年',
                barWidth : 20,
				//itemStyle:{normal:{color:['#006a65']}},
                data:[26, 182, 40,100, 396, 158,100, 396, 158,100, 396, 158]
            },
            {
                 name:'支付宝',
                type:'bar',
                stack: '今年',
                barWidth : 20,
                data:[186, 286, 150,86, 286, 150,86, 286, 150,86, 286, 150]
            },
            {
                name:'微信',
                type:'bar',
                stack: '今年',
                barWidth : 20,
                data:[196, 188, 126,196, 188, 126,196, 188, 126,196, 188, 126],
                markLine : {
                    itemStyle:{
                        normal:{
                            lineStyle:{
                                type: 'dashed'
                            }
                        }
                    },
                    /*data : [
                        [{type : 'min'}, {type : 'max'}]
                    ]*/
                }
            },
            {
                name:'微信',
                type:'bar',
                stack: '今年',
                barWidth : 20,
                data:[196, 188, 126,196, 188, 126,196, 188, 126,196, 188, 126]
            },
            {
                name:'银联',
                type:'bar',
                stack: '今年',
                barWidth : 20,
                data:[36, 88, 166,36, 88, 166,36, 88, 166,36, 88, 166]
            }

        ]
    };

    myChart.setOption(option);
</script>
<script>
    // 基于准备好的dom，初始化echarts实例
    var myChart2 = echarts.init(document.getElementById('main2'));

    var option2 = {
        /* tooltip : {
         trigger: 'axis',
         axisPointer : {            // 坐标轴指示器，坐标轴触发有效
         type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
         }
         },*/
        legend: {
            bottom:5,
            data:['支付宝','微信','银联']
        },
		color:['#00a0e9', '#0dc91a','#006a65'],
        calculable : true,
        xAxis : [
            {
                type : 'category',
                data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'支付宝',
                type:'bar',
                stack: '去年',
                barWidth : 20,
                data:[100, 396, 158,100, 396, 158,100, 396, 158,100, 396,158]
            },
            {
                name:'微信',
                type:'bar',
                stack: '去年',
                barWidth : 20,
                data:[96, 296, 198,100, 396, 158,100, 396, 158,100, 396,158]
            },
            {
                name:'银联',
                type:'bar',
                stack: '去年',
                barWidth : 20,
                data:[26, 182, 40,100, 396, 158,100, 396, 158,100, 396, 158]
            },
            {
                name:'支付宝',
                type:'bar',
                stack: '今年',
                barWidth : 20,
                data:[186, 286, 150,86, 286, 150,86, 286, 150,86, 286, 150]
            },
            {
                name:'微信',
                type:'bar',
                stack: '今年',
                barWidth : 20,
                data:[196, 188, 126,196, 188, 126,196, 188, 126,196, 188, 126],
                markLine : {
                    itemStyle:{
                        normal:{
                            lineStyle:{
                                type: 'dashed'
                            }
                        }
                    },
                    /*data : [
                     [{type : 'min'}, {type : 'max'}]
                     ]*/
                }
            },
            {
                name:'微信',
                type:'bar',
                stack: '今年',
                barWidth : 20,
                data:[196, 188, 126,196, 188, 126,196, 188, 126,196, 188, 126]
            },
            {
                name:'银联',
                type:'bar',
                stack: '今年',
                barWidth : 20,
                data:[36, 88, 166,36, 88, 166,36, 88, 166,36, 88, 166]
            }

        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart2.setOption(option2);
</script>