var myChart = echarts.init(document.getElementById('main'));
var myChart2 = echarts.init(document.getElementById('main2'));

function setMonthInfo(cmonth,jsonData){
    var month_option = {
    		tooltip : {
				show:false,
				trigger: 'axis',
				axisPointer : {            // 坐标轴指示器，坐标轴触发有效
					type : 'line'        // 默认为直线，可选为：'line' | 'shadow'
				}
			},
			color:['#00a0e9','#0dc91a','#006a65'],
			legend: {
				bottom:10,
				data:['支付宝','微信','线下支付']
			},
			 grid: {
				 top:10,
			     left: '3%',
			     right: '3%',
			     bottom:38,
			     containLabel: true
			},
			xAxis : [
				{
					type : 'category',
					data : cmonth
				}
			],
			yAxis : [
				{
					type : 'value'
				}
			],
			series : jsonData
		};
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(month_option);
}

function setWeekInfo(t,jsonData){
    var week_option = {
		tooltip : {
			show:false,
			trigger: 'axis',
			axisPointer : {            // 坐标轴指示器，坐标轴触发有效
				type : 'line'        // 默认为直线，可选为：'line' | 'shadow'
			}
		},
		color:['#00a0e9','#0dc91a','#006a65'],
		legend: {
            bottom:10,
            data:['支付宝','微信','线下支付']
        },
        grid: {
			 top:10,
		     left: '3%',
		     right: '3%',
		     bottom:38,
		     containLabel: true
		},
        xAxis : [
            {
                type : 'category',
                data : t
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : jsonData
    };
    // 使用刚指定的配置项和数据显示图表。
    myChart2.setOption(week_option);
}

function setLoading(id){
	var _html='<div style="background-color: rgba(50, 50, 50, 0.701961);width: 100%;height: 100%;z-index: 100;position: fixed;text-align: center;padding-top: 200px;"><img src="loading.gig"></div>';
	$('#'+id).appen(_html);
}