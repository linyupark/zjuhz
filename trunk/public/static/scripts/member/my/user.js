document.write("<script type=\"text/javascript\" src=\"/static/scripts/member/my/my.js\"></script>");

$(function() {
    $("#btnSubmit").click( function() {
		douser();
    });
});

// 注册提交
function douser() {
	ajaxloading(true);
	$("#btnSubmit").attr("disabled", true);
	var formdata = $("#frmUser").fastSerialize();

	$.ajax( {
		type   : "POST",
        url    : "/member/my/do"+type+"/",
        data   : formdata,
	    success: function(msg) {
			if (msg == "message") {
                ajaxhint(false);
				ajaxloading();
				popup_message("/member/index/message/");
				goToUrl("/member/my/user/type/"+type+"/", 1000);
			}
			else {
				$("#btnSubmit").attr("disabled", false);
				ajaxhint(true,msg);
				ajaxloading();
			}
		}
	});

	return false;
}