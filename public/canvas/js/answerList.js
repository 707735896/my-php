//画布宽高
var canvasWidth;
var canvasHeight;
//画布偏移量
var canvasTop;
var canvasLeft;
//初始画笔大小颜色
var size = 1;
var color = '#000000';
//橡皮擦大小
var radius = 10;
//初始字体大小
var font = 12;
//撤销数组
var arr = [];
var nextDrawAry = [];
var lockDraw = false;
//缩放变量
var imgi = 0;
var zoomVal = 1;
var shadowColor = '#000000';
var subWidth = $(subject).width();
var subHeight = $(subject).height();
//if(subWidth == 0 && subHeight == 0) {
//	canvasWidth = $(subject).minwidth();
//	canvasHeight = $(subject).minHeight();
canvasWidth = window.innerWidth;
canvasHeight = DrawCanvasHeight;
//} 
//else {
//	canvasWidth = subWidth;
//	canvasHeight = subHeight;
//}
//工具栏高
//$(drawController).css({
//	"height": canvasHeight,
//})
$(content).css({
	"height": canvasHeight,
	"width": canvasWidth
})
$(box).css({
	"height": canvasHeight,
	"width": canvasWidth
})

/**
 **********初始化画板**********
 */
var initCanvas = function(canvas, context) {
	//共有属性
	this.canvas = canvas;
	this.context = context;
	canvasTop = $(canvas).offset().top
	canvasLeft = $(canvas).offset().left;
	canvas.width = canvasWidth;
	canvas.height = canvasHeight;
	//共有方法
	this.draw_graph = function(graphType, obj) {
		$('#subject').css('z-index', '-1');
		var position = $(content).position();
		lockDraw = true;
		$(div_text).css("visibility", "hidden");
		$(div_text).css("z-index", "5");
		$(text_input).css("visibility", "hidden");

		$(div_img).css("visibility", "hidden");
		$(div_img).css("z-index", "5");
		$("#file-3").css("visibility", "hidden");
		//置空
		$(text_input).val("");
		//先画在蒙版上 再复制到画布上

		chooseImg(obj);
		var canDraw = false;

		var startX;
		var startY;
		var isFirst = true;
		var isInput = true;
		var bbox = canvas.getBoundingClientRect();
		startX = startX - bbox.left * (canvas.width / bbox.width);
		startY = startY - bbox.top * (canvas.width / bbox.width);

		//滚动
		$(container).on('scrollstop', function() {
			if(graphType == 'pencil') {
				draw_graph('pencil', $(pencil).get()[0]);
			} else if(graphType == 'rubber') {
				draw_graph('rubber', $(rubber).get()[0]);
			} else if(graphType == 'square') {
				draw_graph('square', $(square).get()[0]);
			} else if(graphType == 'circle') {
				draw_graph('circle', $(circle).get()[0]);
			} else if(graphType == 'line') {
				draw_graph('line', $(line).get()[0]);
			} else if(graphType == 'input_text') {
				draw_graph('input_text', $(input_text).get()[0]);
			}
		});

		var input_x; //文本输入x坐标
		var input_y;
		//鼠标按下获取 开始xy开始画图
		var mousedown = function(e) {
			$('#subject').udraggable('disable');
			context.strokeStyle = color;
			context.lineWidth = size;
			e = e || window.event;
			startX = e.clientX / zoomVal - canvasLeft + 0.5;
			startY = e.clientY / zoomVal - canvasTop + 0.5;
			context.moveTo(startX, startY);
			canDraw = true;
			if(graphType == 'pencil') { //铅笔
				context.beginPath();
			} else if(graphType == 'rubber') {
				eraser(e, startX, startY);
			} else if(graphType == 'input_text') {
				$('#container').removeClass("pencil circle rubber square line text move").addClass("text");
				//文本输入框
				if(isFirst) { //是第一次点击
					$(div_text).css("visibility", "visible");
					$(div_text).css("z-index", "15");
					$(text_input).css("visibility", "visible");
					$(text_input).css("position", "absolute");
					$(text_input).css("margin-left", startX + (-position.left));
					$(text_input).css("margin-top", startY + (-position.top));
					input_x = startX + (-position.left + 5);
					input_y = startY + (-position.top);
					isFirst = false;
				} else { //如果点击地方不在控件上，就让控件失去焦点
					isFirst = true;
					$(div_text).css("visibility", "hidden");
					$(div_text).css("z-index", "5");
					$(text_input).css("visibility", "hidden");
					var input_width = $(text_input).width();
					var input_val = $(text_input).val();
					context.fillStyle = color;
					context.shadowBlur = 0;
					context.font = font + "px Arial";
					wrapText(context, input_val, input_x, input_y, input_width, 14);
					//置空
					$(text_input).val("");
				}
			} else if(graphType == 'upload_img') {
				$(div_img).css("visibility", "visible");
				$(div_img).css("z-index", "15");
				$("#file-3").css("visibility", "visible");
				$("#file-3").css("position", "absolute");
				$("#file-3").css("left", startX + (-position.left));
				$("#file-3").css("top", startY + (-position.top));
				$("label").css("display", "block");
				$("label").css("position", "absolute");
				$("label").css("left", startX + (-position.left) - 30);
				$("label").css("top", startY + (-position.top) - 10);
				$("dl").css("left", startX + (-position.left));
				$("dl").css("top", startY + (-position.top));
				input_x = startX + (-position.left);
				input_y = startY + (-position.top + 10);
				isInput = false;
				$("#file-3").on("change", function() {
					$('#container').removeClass("pencil circle rubber square line text move")
					var docObj = document.getElementById("file-3");
					var imgObjPreview = document.getElementById("preview");
					$("label").css("display", "none");
					if(docObj.files && docObj.files[0]) {
						$(box).addClass("boxShow");
						$(imgObjPreview).attr('src', window.URL.createObjectURL(docObj.files[0]));
					}
					return true;
				})
				$("#box").add("#drawController").one("mouseup", function() {
					console.log("ces”")
					var img = new Image();
					img.src = $(preview)[0].src;
					context.shadowBlur = 0;
					context.drawImage(img, startX + (-position.left) + 0.5, startY + (-position.top) + 0.5);
					var preData = context.getImageData(0, 0, canvasWidth, canvasHeight);
					arr.push(preData);
					$(div_img).css("visibility", "hidden");
					$(div_img).css("z-index", "5");
					$("#file-3").css("visibility", "hidden");
					$(preview).attr('src', '');
					$(box).removeClass("boxShow");
				})
			}
		};
		// 鼠标移动
		var mousemove = function(e) {
			if(!lockDraw) return;
			e = e || window.event;
			var x = e.clientX / zoomVal - canvasLeft + 0.5;
			var y = e.clientY / zoomVal - canvasTop + 0.5;
			//输入框样式变化
			if(graphType == 'input_text') {
				$('#container').removeClass("pencil circle rubber square line text move").addClass("text");
			}
			if(graphType == 'upload_img') {
				$('#container').removeClass("pencil circle rubber square line text move");
			}
			if(graphType == 'line' || graphType == 'circle' || graphType == 'square') {
				context.clearRect(0, 0, canvasWidth, canvasHeight);
				if(arr.length != 0) {
					context.putImageData(arr[arr.length - 1], 0, 0, 0, 0, canvasWidth, canvasHeight);
				}
			}
			//矩形  4条直线搞定
			if(graphType == 'square') {
				$('#container').removeClass("pencil circle rubber square line text move").addClass("square");
				if(canDraw) {
					context.beginPath();
					context.lineWidth = size + 2;
					context.shadowBlur = 1; // 模糊尺寸
					context.shadowColor = shadowColor; // 颜色
					context.moveTo(startX + (-position.left), startY + (-position.top) + 12);
					context.lineTo(x + (-position.left), startY + (-position.top) + 12);
					context.lineTo(x + (-position.left), y + (-position.top) + 12);
					context.lineTo(startX + (-position.left), y + (-position.top) + 12);
					context.lineTo(startX + (-position.left), startY + (-position.top) + 12);
					context.closePath();
					context.stroke();
				}
				//直线
			} else if(graphType == 'line') {
				$('#container').removeClass("pencil circle rubber square line text move").addClass("line");
				if(canDraw) {
					context.beginPath();
					context.lineWidth = size + 2;
					context.shadowBlur = 1; // 模糊尺寸
					context.shadowColor = shadowColor; // 颜色
					context.moveTo(startX + (-position.left), startY + (-position.top));
					context.lineTo(x + (-position.left), y + (-position.top));
					context.stroke();
				}
				//画笔
			} else if(graphType == 'pencil') {
				$('#container').removeClass("pencil circle rubber square line text move").addClass("pencil");
				if(canDraw) {
					lockDraw = true;
					context.lineWidth = size;
					context.shadowBlur = 2; // 模糊尺寸
					context.shadowColor = shadowColor; // 颜色
					context.lineCap = "round";
					context.lineTo(x + (-position.left), y + (-position.top));
					context.stroke();
				}
				//圆 未画得时候 出现一个小圆
			} else if(graphType == 'circle') {
				$('#container').removeClass("pencil circle rubber square line text move").addClass("circle");
				if(canDraw) {
					lockDraw = true;
					context.beginPath();
					context.lineWidth = size + 2;
					context.shadowBlur = 1; // 模糊尺寸
					context.shadowColor = shadowColor; // 颜色
					var radii = Math.sqrt((startX - x) * (startX - x) + (startY - y) * (startY - y));
					context.arc(startX + (-position.left), startY + (-position.top) + 12, radii, 0, Math.PI * 2, false);
					context.stroke();
				}
				//橡皮擦 
			} else if(graphType == 'rubber') {
				eraser(e, x, y);
			}
		};

		//橡皮擦方法
		var eraser = function(e, x, y) {
			$('#container').removeClass("pencil circle rubber square line text move").addClass("rubber");
			if(canDraw) {
				context.save();
				context.beginPath();
				context.arc(x + (-position.left), y + (-position.top) + 12, radius, 0, Math.PI * 2, false)
				context.clip();
				context.clearRect(0, 0, canvas.width, canvas.height);
				context.restore();
			}
		}

		//鼠标离开 把蒙版canvas的图片生成到canvas中
		var mouseup = function(e) {
			if(!lockDraw) return;
			e = e || window.event;
			canDraw = false;
			var preData = context.getImageData(0, 0, canvasWidth, canvasHeight);
			arr.push(preData);
			nextDrawAry = [];
		};

		//鼠标离开区域以外 除了涂鸦 都清空
		var mouseout = function(e) {
			if(!lockDraw) return;
			//			if(canDraw) {
			//				saveImageToAry();
			//	}
			canDraw = false;
		}

		$(canvas).unbind();
		$(canvas).bind('mousedown', mousedown);
		$(canvas).bind('mousemove', mousemove);
		$(canvas).bind('mouseup', mouseup);
		$(canvas).bind('mouseout', mouseout);
	}

}

/**
 **********功能模块**********
 */
var tools = function() {

	//展开颜色选择器
	this.showColor = function(e, obj) {
		var top = $(obj).offset().top;
		var left = $(obj).offset().left;
		$(color_tool)[0].style.left = left + 50 + "px";;
		$(color_tool)[0].style.top = top - 8 + "px";

		if($(color_tool).is(":hidden")) {
			$(color_tool).show();
			$(color_tool).children("input").on('click touchstart', chooseLineColor);
		} else {
			$(color_tool).hide();
		}

		$(document).one("click touchend", function() {
			$(color_tool).hide();
		});
		//阻止事件冒泡
		e.stopPropagation();
		$(color_tool).on("click touchend", function(e) {
			e.stopPropagation();
		});
	}

	//展开字体大小选择器
	this.showFont = function(e, obj) {
		var top = $(obj).offset().top;
		var left = $(obj).offset().left;
		$(font_size)[0].style.left = left + 52 + "px";;
		$(font_size)[0].style.top = top + 5 + "px";
		if($(font_size).is(":hidden")) {
			$(font_size).show();
		} else {
			$(font_size).hide();
		}
		$(document).one("click touchend", function() {
			$(font_size).hide();
		});
		//阻止事件冒泡
		e.stopPropagation();
		$(font_size).on("click touchend", function(e) {
			e.stopPropagation();
		});
	}

	//展开线条大小选择器
	this.showLineSize = function(e, obj) {
		var top = $(obj).offset().top;
		var left = $(obj).offset().left;
		$(line_size)[0].style.left = left + $(obj).width() + 27; + "px";
		$(line_size)[0].style.top = top - 3 + "px";
		if($(line_size).is(":hidden")) {
			$(line_size).show();
		} else {
			$(line_size).hide();
		}

		$(document).one("click touchend", function() {
			$(line_size).hide();
		});
		//阻止事件冒泡
		e.stopPropagation();
		$(line_size).on("click touchend", function(e) {
			e.stopPropagation();
		});
	}
	//展开橡皮擦大小选择器
	this.showRubberSize = function(e, obj) {
		var top = $(obj).offset().top;
		var left = $(obj).offset().left;
		$(rubber_size)[0].style.left = left + $(obj).width() + 27; + "px";
		console.log($(rubber_size)[0].style.left)
		$(rubber_size)[0].style.top = top - 3 + "px";
		if($(rubber_size).is(":hidden")) {
			$(rubber_size).show();
		} else {
			$(rubber_size).hide();
		}

		$(document).one("click touchend", function() {
			$(rubber_size).hide();
		});

		e.stopPropagation();
		$(rubber_size).on("click touchend", function(e) {
			e.stopPropagation();
		});
	}
	//选择颜色
	this.chooseLineColor = function(obj) {
		var objClass = $(this).attr("class");
		$(chooseColor).attr("class", "");
		$(chooseColor).addClass(objClass).addClass('border_nochoose');
		color = $(this).css('background-color');
		shadowColor = $(this).css('background-color');
		$(color_tool).hide();
	}

	//选择线条大小
	this.chooseLineSize = function(_size) {
		$(chooseSize).attr("src", "/public/canvas/images/line_size_" + _size + "@2x.png");
		$(chooseSize).css('padding-top', '8px');
		size = _size;
		$(line_size).hide();
	}

	//选择橡皮擦大小
	this.chooseRubberSize = function(_size) {
		$(rubberSize).attr("src", "/public/canvas/images/rubber_size_" + _size + ".png");
		$(rubberSize).css('padding-top', '8px');
		radius = _size;
		$(rubber_size).hide();
	}

	//选择字体大小
	$(selected).on("change", function() {
		var _font = $(selected).val();
		chooseFontSize(_font);
	})

	this.chooseFontSize = function(_font) {
		font = _font;
		$("textarea").css("font-size", font + "px");
		$(font_size).hide();
	}

}

//撤销上一个操作
var cancel = function() {
	context.clearRect(0, 0, canvasWidth, canvasHeight);
	if(arr.length) {
		var popData = arr.pop();
		nextDrawAry.push(popData); //把删除后的数组存放到重做数组
		if(arr[arr.length - 1]) {
			context.putImageData(arr[arr.length - 1], 0, 0, 0, 0, canvasWidth, canvasHeight);
		}
	}
};

//重做上一个操作
var next = function() {
	if(nextDrawAry.length) {
		var popData = nextDrawAry.pop();
		arr.push(popData); //把删除后的数组存放到撤销数组
		context.putImageData(popData, 0, 0, 0, 0, canvasWidth, canvasHeight);
	}
};

//选择功能按钮 修改样式
function chooseImg(obj) {
	var imgAry = $(drawController).find("i");
	for(var i = 0; i < imgAry.length; i++) {
		$(imgAry[i]).removeClass('border_choose');
		$(imgAry[i]).addClass('border_nochoose');
	}
	$(obj).removeClass("border_nochoose");
	$(obj).addClass("border_choose");
}

//输入框失去焦点
var dismissFocus = function() {
	$(div_text).css("visibility", "hidden");
	$(div_text).css("z-index", "5");
	$(text_input).css("visibility", "hidden");
	$(text_input).val();
}

//图片框失去焦点
var dismissFocusInput = function() {
	$(div_img).css("visibility", "hidden");
	$(div_img).css("z-index", "5");
	$("#file-3").css("visibility", "hidden");
}

//清空图层
var clearContext = function() {
	arr = [];
	nextDrawAry = [];
	context.clearRect(0, 0, canvasWidth, canvasHeight);
}

//文本换行
function wrapText(context, text, x, y, maxWidth, lineHeight) {
	var words = text.split('\n');
	var currentLineText = '';
	var lineNo = 0;
	var tmpCurrentLineText = '';
	words.forEach(function(e, i) {

		tmpCurrentLineText = (tmpCurrentLineText + ' ' + e);

		var tmpCurrentLineTextObj = context.measureText(tmpCurrentLineText);

		context.fillText(currentLineText, x, y + lineNo * lineHeight);

		lineNo++;

		tmpCurrentLineText = e;

		currentLineText = tmpCurrentLineText;

	});

	context.fillText(currentLineText, x, y + lineNo * lineHeight);

}

function GetCurrentStrWidth(text, font) {
	var currentObj = $('<pre>').hide().appendTo(document.body);
	$(currentObj).html(text).css('font', font);
	var width = currentObj.width();
	currentObj.remove();
	return width;
}

$.fn.autoWidth = function() {

	function autoWidth(elem) {

		var text = $(text_input).val();
		var textFont = $(text_input).css("font");
		var textWidth = GetCurrentStrWidth(text, textFont);
		if(textWidth < 50) return;
		$(text_input).css("width", textWidth + 40);
	}

	this.each(function() {
		autoWidth(this);
		$(this).on('keydown', function() {
			autoWidth(this);
		});
	});

}
$(text_input).autoWidth();
//滚动
var os1 = new Optiscroll(document.getElementById('container'));
//拖动
var drag = function(obj) {
	$('#container').removeClass("pencil circle rubber square line text move").addClass("move");
	draw_graph(""); //初始化画板
	lockDraw = false;
	chooseImg(obj);
	$("#subject").css('z-index', '20');
	$('#subject').udraggable('enable');
}