$(function() {
	// sort
	sort_init();

	// form
    $("#vcode").focus( function() {
		putVerifyImg();
    });

    $("#btnSubmit").click( function() {
		question_insert();		
    });
});

// 提交问题
function question_insert() {
	ajaxloading(true);
	$("#btnSubmit").attr("disabled", true);
	var formdata = $("#myform").fastSerialize();

	$.ajax( {
		type   : "POST",
        url    : "/help/question/doinsert/",
        data   : formdata,
	    success: function(msg) {
			if (msg == 'redirect') {
				window.location.href="/help/index/message/";
			}
			else {
				$("#btnSubmit").attr("disabled", false);
				getVerifyCode();
				ajaxhint(true,msg);
				ajaxloading();
			}

		}
	});

	return false;
}
