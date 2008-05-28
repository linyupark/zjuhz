document.write("<script type=\"text/javascript\" src=\"/static/scripts/member/my/my.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/swfupload/swfupload.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/swfupload/swfupload.graceful_degradation.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/swfupload/fileprogress.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/swfupload/handlers.js\"></script>");

$(function() {
    $("#frmUser").submit( function() {
		douser();
		return false;
    });

    $("#showUpload").click( function() {
		$('#doUpload').toggle();
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