$(document).ready(function(){
    $("#btnSubmit").click( function() {
		question_add();
    });
    $("#vcode").focus( function() {
		putVerifyImg();
    });
});

// 提交问题
function question_add() {
	ajaxloading(true);
	$("#btnSubmit").attr("disabled", true);
	var formdata = $("#frmQuestion").fastSerialize();
	$.ajax( {
		type   : "POST",
        url    : "/help/question/doadd/",
        data   : formdata,
	    success: function(msg) {
			$("#btnSubmit").attr("disabled", false);
			ajaxhint(true,msg);
			ajaxloading();
		}
	});

	return false;
}
