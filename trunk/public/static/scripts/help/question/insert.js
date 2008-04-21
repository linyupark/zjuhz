﻿document.write("<script type=\"text/javascript\" src=\"/static/scripts/help/sort.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/jquery.selectboxes.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/tinymce/tiny_mce_gzip.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/tinymce/tiny_mce_help.js\"></script>");

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
			if (msg == "message") {
				popup_message("/help/index/message/");
				goToUrl("/help/", 3000);
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