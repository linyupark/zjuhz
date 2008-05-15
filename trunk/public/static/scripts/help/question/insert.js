document.write("<script type=\"text/javascript\" src=\"/static/scripts/help/sort.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/jquery.selectboxes.js\"></script>");

$(function() {
	// sort
	sort_init();

	// form
    $("#vcode").focus( function() {
		putVerifyImg();
    });

    $("#offer").focus( function() {
		putVerifyImg();
    });

    $("#myform").submit( function() {
		question_insert();
		return false;
    });
});

// 提交问题
function question_insert() {
	ajaxloading(true);
	$("#btnQuestion").attr("disabled", true);
	var formdata = $("#myform").fastSerialize();
    formdata.push({name:'content', value:editor.data()});

	$.post("/help/question/doinsert/", formdata, function(msg) {
        ajaxloading();
		$("#btnQuestion").attr("disabled", false);

		if ("message" == msg) {
			ajaxhint(false);
			popup_message("/help/index/message/");
			goToUrl("/help/", 2000);
			return false;
		}

		getVerifyCode();
		ajaxhint(true, msg);
	});

	return false;
}
