$.fn.extend({
	//---元素拖动插件
	dragging: function(data) {
		var $this = $(this);
		var xPage;
		var yPage;
		var X; //
		var Y; //
		var xRand = 0; //
		var yRand = 0; //
		var father = $this.parent();
		var defaults = {
			move: 'both',
			randomPosition: true,
			hander: 1
		}
		var opt = $.extend({}, defaults, data);
		var movePosition = opt.move;
		var random = opt.randomPosition;

		var hander = opt.hander;

		if(hander == 1) {
			hander = $this;
		} else {
			hander = $this.find(opt.hander);
		}

		//---初始化
		father.css({
			"position": "relative",
			"overflow": "hidden"
		});
		$this.css({
			"position": "absolute"
		});
		hander.css({
			"cursor": "move"
		});

		var faWidth = father.width();
		var faHeight = father.height();
		var thisWidth = $this.width() + parseInt($this.css('padding-left')) + parseInt($this.css('padding-right'));
		var thisHeight = $this.height() + parseInt($this.css('padding-top')) + parseInt($this.css('padding-bottom'));
		var mDown = false; //
		var positionX;
		var positionY;
		var moveX;
		var moveY;

		hander.mousedown(function(e) {
			console.log("hander.mousedown");
			father.children().css({
				"zIndex": "0"
			});
			$this.css({
				"zIndex": "1"
			});
			mDown = true;
			X = e.pageX;
			Y = e.pageY;
			positionX = $this.position().left;
			positionY = $this.position().top;
			return false;
		});

		hander.mouseup(function(e) {
			xPage = e.pageX; //--
			moveX = positionX + xPage - X;
			yPage = e.pageY; //--
			moveY = positionY + yPage - Y;
			console.log("hander.mouseup");
			var img = new Image();
			img.src = $(preview)[0].src;
			context.shadowBlur = 0;
			context.drawImage(img, moveX + 0.5, moveY + 0.5);
			$(preview).attr('src', '');
			$(box).removeClass("boxShow");
			mDown = false;
		});

		$(document).mousemove(function(e) {
			xPage = e.pageX; //--
			moveX = positionX + xPage - X;

			yPage = e.pageY; //--
			moveY = positionY + yPage - Y;

			function thisAllMove() { //移动
				if(mDown == true) {
					$this.css({
						"left": moveX,
						"top": moveY
					});
				} else {
					return;
				}
				if(moveX < 0) {
					$this.css({
						"left": "0"
					});
				}
				if(moveX > (faWidth - thisWidth)) {
					$this.css({
						"left": faWidth - thisWidth
					});
				}

				if(moveY < 0) {
					$this.css({
						"top": "0"
					});
				}
				if(moveY > (faHeight - thisHeight)) {
					$this.css({
						"top": faHeight - thisHeight
					});
				}
			}
			thisAllMove();
		});
	}
});