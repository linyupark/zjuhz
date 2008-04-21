$(function() {
	helpRank();

	// form
    $("#vcode").focus( function() {
		putVerifyImg();
    });

    $("#btnReply").click( function() {
		reply_insert();		
    });
});

// 提交回答
function reply_insert() {
	ajaxloading(true);
	$("#btnReply").attr("disabled", true);
	var formdata = $("#myform").fastSerialize();

	$.ajax( {
		type   : "POST",
        url    : "/help/reply/doinsert/",
        data   : formdata,
	    success: function(msg) {
			if (msg == "reload") {
				window.location.reload();
			}
			else {
				$("#btnReply").attr("disabled", false);
				getVerifyCode();
				ajaxhint(true,msg);
				ajaxloading();
			}

		}
	});

	return false;
}
