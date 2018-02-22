$(function() {
	var map = [{
		id: 'pencil',
		node: '<div class="img-center"><i id="pencil" class="border_nochoose iconfont icon-huaban_bi" title="画笔"></i></div>'
	}, {
		id: 'input_text',
		node: '<div class="img-center"><i id="input_text" class="border_nochoose iconfont icon-wenzi" title="文本框"></i></div>'
	}, {
		id: 'line',
		node: '<div class="img-center"><i id="line" class="border_nochoose iconfont icon-zhixian" title="直线"></i></div>'
	}, {
		id: 'square',
		node: '<div class="img-center"><i id="square" class="border_nochoose iconfont icon-rectangle" title="矩形"></i></div>'
	}, {
		id: 'circle',
		node: '<div class="img-center"><i id="circle" class="border_nochoose iconfont icon-yuanquan" title="圆"></i></div>'
	}, {
		id: 'chooseSize',
		node: '<div class="img-center"><img id="chooseSize" src="/public/canvas/images/line_size@2x.png" class="img border_nochoose" title="线条大小"></div>'
	}, {
		id: 'chooseColor',
		node: '<div class="img-center"><input id="chooseColor" class="i1 border_nochoose" readonly unselectable="on" title="选择颜色" /></div>'
	}, {
		id: 'chooseFont',
		node: '<div class="img-center"><i id="chooseFont" class="border_nochoose iconfont icon-font" title="字体大小"></i></div>'
	}, {
		id: 'rubber',
		node: '<div class="img-center"><i id="rubber" class="border_nochoose iconfont icon-mianxingtubiao1xiangpica" title="橡皮擦"></i></div>'
	}, {
		id: 'clear',
		node: '<div class="img-center"><i id="clear" class="border_nochoose iconfont icon-qingping" title="清屏"></i></div>'
	}, {
		id: 'dragID',
		node: '<div class="img-center"><i id="dragID" class="border_nochoose iconfont icon-tuodong" title="拖动"></i></div>'
	}, {
		id: 'cancelNext',
		node: '<div class="img-center"><i id="cancelID" class="border_nochoose iconfont icon-iconfonthoutui" title="撤销"></i><i id="nextID" class="border_nochoose iconfont icon-qianjin" title="前进"></i></div>'
	}, {
		id: 'enlarge',
		node: '<div class="img-center" id="enlarge"><i class="border_nochoose iconfont icon-fangda" title="放大"></i></div>'
	}, {
		id: 'narrow',
		node: '<div class="img-center" id="narrow"><i class="border_nochoose iconfont icon-minus" title="缩小"></i></div>'
	}]

	//json数组
	var array = ['input_text', 'chooseFont', 'line', 'pencil', 'chooseSize', 'chooseColor', 'rubberSize', 'rubber', 'clear', 'enlarge', 'narrow', 'cancelNext'];
	//		array.sort(function() {
	//			return Math.random() > 0.5 ? -1 : 1;
	//		});
	for(i = 0; i < array.length; i++) {
		for(j = 0; j < map.length; j++) {
			if(array[i] == map[j].id) {
				$("#tools-all").append(map[j].node);
			}

		}
	}
	//初始化画板
	var canvas = document.getElementById("canvas");
	var context = canvas.getContext('2d');

	initCanvas(canvas, context);
	tools();
	$('#drawController').udraggable();
	//canvas宽高自适应浏览器窗口
	//	window.onload = function() {
	//		w = canvas.width = window.innerWidth;
	//		h = canvas.height = window.innerHeight;
	//	}
	//监听浏览器变化时
	//	window.addEventListener("resize", resizeCanvas, false);
	//
	//	function resizeCanvas() {
	//		console.log("aa");
	//		var nc = document.createElement("canvas");
	//		nc.width = canvas.width;
	//		nc.height = canvas.height;
	//		nc.getContext("2d").drawImage(canvas, 0, 0);
	//		if($("#subject").height() != 0) {
	//			DrawCanvasHeight = $("#subject").height();
	//		} else {
	//			DrawCanvasHeight = window.innerHeight;
	//		}
	//		w = canvas.width = window.innerWidth;
	//		h = canvas.height = DrawCanvasHeight;
	//		canvas.width = w;
	//		canvas.height = h;
	//		context.drawImage(nc, 0, 0);
	//	}

	if($("#pencil").length > 0) {
		$(pencil).on("click touchstart", function() {
			draw_graph('pencil', this);
		})
	}
	if($("#line").length > 0) {
		$(line).on("click touchstart", function() {
			draw_graph('line', this);
		})
	}
	if($("#square").length > 0) {
		$(square).on("click touchstart", function() {
			draw_graph('square', this);
		})
	}
	if($("#circle").length > 0) {
		$(circle).on("click touchstart", function() {
			draw_graph('circle', this);
		})
	}
	if($("#rubber").length > 0) {
		$(rubber).on("click touchstart", function() {
			draw_graph('rubber', this);
		})
	}
	if($("#input_text").length > 0) {
		$(input_text).on("click touchstart", function() {
			draw_graph('input_text', this);
		})
	}
	if($("#chooseSize").length > 0) {
		$(chooseSize).on("click touchstart", function(e) {
			$(font_size).add(color_tool).add(rubber_size).hide();
			showLineSize(e, this);
		})
	}
	if($("#chooseColor").length > 0) {
		$(chooseColor).on("click touchstart", function(e) {
			$(font_size).add(line_size).add(rubber_size).hide();
			showColor(e, this);
		})
	}
	if($("#chooseFont").length > 0) {
		$(chooseFont).on("click touchstart", function(e) {
			$(color_tool).add(line_size).add(rubber_size).hide();
			showFont(e, this);
		})
	}
	if($("#rubberSize").length > 0) {
		$(rubberSize).on("click touchstart", function(e) {
			$(font_size).add(line_size).add(color_tool).hide();
			showRubberSize(e, this);
		})
	}

	if($("#dragID").length > 0) {
		$(dragID).on("click touchstart", function() {
			drag(this);
		})
	}
	if($("#clear").length > 0) {
		$(clear).on("click touchstart", function() {
			clearContext('1');
		})
	}
	if($("#cancelID").length > 0) {
		$(cancelID).on("click touchstart", function() {
			cancel(this);
		})
	}
	if($("#nextID").length > 0) {
		$(nextID).on("click touchstart", function() {
			next(this);
		})
	}
	if($("#upload_img").length > 0) {
		$(upload_img).on("click touchstart", function() {
			draw_graph('upload_img', this);
		})
	}

	//图片拖动
	$('#box dl').each(function() {
		$(this).dragging({
			move: 'both',
			randomPosition: false
		});
	});

	if($("#enlarge").length > 0) {
		$(enlarge).on("click touchstart", function() {
			$('#container').removeClass("pencil circle rubber square line text move");
			if(imgi < 10) {
				imgi++;
				zoomVal = 1 + imgi / 10;
				$(content).css("zoom", zoomVal);
				draw_graph("");
				$(this).find('i').removeClass("border_nochoose").addClass("border_choose");

			} else if(imgi == 10) {
				alert('已放大到最大比例')
			}
		});
	}

	if($("#narrow").length > 0) {
		$(narrow).on("click touchstart", function() {
			$('#container').removeClass("pencil circle rubber square line text move");
			if(imgi > 0) {
				imgi--;
				zoomVal = 1 + imgi / 10;
				$(content).css("zoom", zoomVal);
				draw_graph("");
				$(this).find('i').removeClass("border_nochoose").addClass("border_choose");
			} else if(imgi == 0) {
				alert('已缩小到最小比例')
			}
		});
	}

});