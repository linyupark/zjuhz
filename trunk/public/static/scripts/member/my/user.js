document.write("<script type=\"text/javascript\" src=\"/static/scripts/member/my/my.js\"></script>");

$(function() {
    $("#frmUser").submit( function() {
		douser();
		return false;
    });
});

// 我的资料
function douser() {
	ajaxloading(true);
	$("#btnUser").attr("disabled", true);
	var formdata = $("#frmUser").fastSerialize();

	$.post("/member/my/do"+type+"/", formdata, function(msg) {
		ajaxloading();

		if (msg == "message") {
			ajaxhint(false);
			popup_message("/member/index/message/");
			goToUrl("/member/my/user/type/"+type+"/", 1000);
			return false;
		}

		$("#btnUser").attr("disabled", false);
		ajaxhint(true, msg);
	});

	return false;
}
