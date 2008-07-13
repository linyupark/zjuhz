$(function() {
    $("#frmInsert").submit( function() {
        doinsert();
        return false;
    });
});

// 发表留言
function doinsert() {
	ajaxloading(true);
	$("#btnInsert").attr("disabled", true);
	var formdata = $("#frmInsert").fastSerialize();

	$.post("/biz/message/doinsert/", formdata, function(msg) {
		ajaxloading();        

		if ("reload" == msg) {
			ajaxhint(false);
			goToUrl(msg, 1000);
			return false;
		}

		ajaxhint(true, msg);
		$("#btnInsert").attr("disabled", false);
	});

	return false;
}

// 回复留言
function doreply(mid) {
	ajaxloading(true);
	$("#btnReply"+mid).attr("disabled", true);
	var formdata = $("#frmReply"+mid).fastSerialize();

	$.post("/biz/message/doreply/", formdata, function(msg) {
		ajaxloading();        

		if ("reload" == msg) {
			goToUrl(msg, 1000);
			return false;
		}

		alert(msg);
        $("#btnReply"+mid).attr("disabled", false);
	});

	return false;
}

// 留言删除之前
function dodeleteBefore(mid, cid) {
	var popup = new Popup({ contentType:3, isReloadOnClose:false, width:340, height:105});
	popup.setContent("title", "校友会提示您");
	popup.setContent("confirmCon", "您确定要删除该留言吗？");
	popup.setContent("callBack", dodelete);
	popup.setContent("parameter", { mid : mid, cid : cid, popup : popup });
	popup.build();
	popup.show();
	return false;
}

// 留言删除
function dodelete(para) {
	ajaxloading(true);

	$.post("/biz/message/dodelete/", { mid : para["mid"], cid : para["cid"] }, function(msg) {
		ajaxloading();
		para["popup"].close();

		if ("hide" == msg) {
			$("#msg" + para["mid"]).hide(0);
			return false;
		}
	});

	return false;
}