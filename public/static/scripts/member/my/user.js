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
        $("#btnUser").attr("disabled", false);

		if ("message" == msg) {
			ajaxhint(false);
			popup_message("/member/index/message/");
			goToUrl("reload", 1000);
			return false;
		}
		
		ajaxhint(true, msg);
	});

	return false;
}
