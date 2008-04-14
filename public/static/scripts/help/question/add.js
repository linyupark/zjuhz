$(function() {
	// sort
	sort_init();

	// form
    $("#vcode").focus( function() {
		putVerifyImg();
    });

    $("#btnSubmit").click( function() {
		question_add();		
    });
});

// 提交问题
function question_add() {
	ajaxloading(true);
	$("#btnSubmit").attr("disabled", true);
	var formdata = $("#myform").fastSerialize();

	$.ajax( {
		type   : "POST",
        url    : "/help/question/doadd/",
        data   : formdata,
	    success: function(msg) {
			$("#btnSubmit").attr("disabled", false);
			getVerifyCode();
			ajaxhint(true,msg);
			ajaxloading();
		}
	});

	return false;
}
