// 基于准备好的dom，初始化echarts实例
var myChart = echarts.init(document.getElementById('main'));

var xAxis = [{
	type: 'category',
	data: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
	axisTick: {
		alignWithLabel: true
	},
	axisLabel: {
		textStyle: {
			// color:'#75C4F6',
			fontSize: 20
		}

	}

}];
var ChartDatas = [{
	value: 56,
	UName: '张三<br/>李四<br/>王五',
	//自定义标签样式，仅对该数据项有效
	label: {},
	//自定义特殊 itemStyle，仅对该数据项有效
	itemStyle: {},

}, {
	value: 26,
	UName: '张三2',
	//自定义标签样式，仅对该数据项有效
	label: {},
	//自定义特殊 itemStyle，仅对该数据项有效
	itemStyle: {},

}, 20, 30, 40, 50];
// 指定图表的配置项和数据
var option = {
	tooltip: {
		show: true,

		formatter: function(params, ticket, callback) {
			console.log('!', params[0].data.UName);
			//			var str='';
			//			for(var i=0;i<params.length;i++){
			//				str+=params[i].data.UName+'<br/>';
			//			}

			return params[0].data.UName;
			//return 'Loading';
		},
		trigger: 'axis',
		axisPointer: { // 坐标轴指示器，坐标轴触发有效
			type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
		}
	},
	title: {
		text: '投票结果',
		textStyle: {
			fontWeight: 'normal'
		}
	},
	textStyle: {
		color: '#585858',
		fontSize: 20
	},
	label: {
		normal: {
			show: true,
			position: 'top'
		}
	},
	//	tooltip: {
	//		
	//	},
	// grid: {
	//     left: '0%',
	//     right: '0%',
	//     bottom: '0%',
	//     containLabel: true
	// },
	xAxis: xAxis,
	yAxis: [{
		type: 'value',
		axisLabel: {
			textStyle: {
				color: '#585858',
				fontSize: 20
			}

		}
	}],
	series: [{
		name: '人数',
		type: 'bar',
		barWidth: '60%',
		itemStyle: {
			normal: {
				color: new echarts.graphic.LinearGradient(
					0, 0, 0, 1, [{
						offset: 0,
						color: '#f99595'
					}, {
						offset: 0.5,
						color: '#f35d5e'
					}, {
						offset: 1,
						color: '#f25252'
					}]
				)
			},
		},
		tooltip: {
			name: ' mm'
		},
		data: ChartDatas,
		// itemStyle: {
		//   normal:{color:function(params){
		//     console.log(params);
		//     return '#f60'
		//   }}
		// },
	}, ]
};
// 使用刚指定的配置项和数据显示图表。
myChart.setOption(option);

function refersh() {

//	ChartDatas[0] = 5;
//	ChartDatas[2] = 2;
//	console.log(ChartDatas[0]);
//	var option = myChart.getOption();
//	ChartDatas[0] = 5;
//	ChartDatas[2] = 2;
//	console.log(ChartDatas[0]);
//	var option = myChart.getOption();
//	option.series[0].data = ChartDatas;
	myChart.setOption(option);
}