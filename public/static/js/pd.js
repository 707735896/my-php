// jQuery(function($) {  
var uuid = document.getElementById('newsubject-body').getAttribute('uuid');
var ue = UE.getEditor('editor');
ue.ready(function() {
	ue.execCommand('serverparam', {
		'uuid': uuid
	});

});

function submit() {
	var title = $('#input-title').val();
	var content = ue.getContent();
//	var seleitem = $('input[name="seleitem"]:checked').val();

	if(!title || !content 
//		|| !seleitem
	) {
		return alert('内容填写不完整!');
	}
	/**
	 * [success description]
	 * @param  {[type]} argument) {               			    		}    	} [description]
	 * @return {[type]}           [description]
	 */
	$.ajax({
		url: $('#frm').attr('action'),
		type: 'post',
		data: {
			title: title,
			content: content,
//			rightkey: seleitem,
			uuid: uuid
		},
		success: function(res) {
			if(res.code == 100) {
			var id=getQueryStringByName('id')?getQueryStringByName('id'):0;
				  new QWebChannel(qt.webChannelTransport, function (channel){
				    var Dialog = channel.objects.Dialog;
                Dialog.cpp_SaveQuestion(res.path,id);
               });
			} else {
				alert('添加失败！');
			}
		}
	});

}

// })


/*c++ & js*/
/**
 * c++点击添加按钮后调用此函数
 */
function js_ClickAddBtn() {
	submit();
}
/**
 * C++点击取消按钮调用此函数
 */
function js_Cancel() {

	$.ajax({
		type: 'post',
		url: '/Index/delFile',
		data:{uuid:uuid},
		success: function(data, textStatus) {
				new QWebChannel(qt.webChannelTransport, function (channel){
				var Dialog = channel.objects.Dialog;
 				Dialog.cpp_CloseFrm();
 			});
		},
		error: function() {
				new QWebChannel(qt.webChannelTransport, function (channel){
				var Dialog = channel.objects.Dialog;
 				Dialog.cpp_CloseFrm();
 			});
		}
	});

}

/**
 * C++点击保存按钮调用此函数生成json文件
 */
function js_SavaSubject(title, imagepath, type) {
    var data = {
        uuid: uuid,
        title: title,
        imagepath: imagepath,
        type: type
    };
    $.ajax({
        type: 'post',
        url: '/Subjectsave/savePrtScr',
        data:data,
        success: function(res) {
            if(res.code == 100) {
                //alert(res.data);
                var id=getQueryStringByName('id')?getQueryStringByName('id'):0;
					new QWebChannel(qt.webChannelTransport, function (channel){
				    var Dialog = channel.objects.Dialog;
                Dialog.cpp_SaveQuestion(res.path,id);
                });
            } else {
                alert('添加失败！');
            }
        }
    });

}