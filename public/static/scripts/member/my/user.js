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

// 工作删除之前
function doworkdelBefore(wid) {
	var popup = new Popup({ contentType:3, isReloadOnClose:false, width:340, height:105});
	popup.setContent("title", "校友会提示您");
	popup.setContent("confirmCon", "您确定要删除该工作经验吗？");
	popup.setContent("callBack", doworkdel);
	popup.setContent("parameter", { wid : wid, popup : popup });
	popup.build();
	popup.show();
	return false;
}

// 工作删除
function doworkdel(para) {
	ajaxloading(true);

	$.post("/member/my/doworkdel/", { wid : para["wid"] }, function(msg) {
		ajaxloading();
		para["popup"].close();

		if ("hide" == msg) {
			$("#work" + para["wid"]).hide(0);
			return false;
		}
	});

	return false;
}