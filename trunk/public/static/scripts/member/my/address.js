document.write("<script type=\"text/javascript\" src=\"/static/scripts/member/my/my.js\"></script>");

$(function() {
    $("#frmCard").submit( function() {
		docard();
		return false;
    });

    $("#frmGroup").submit( function() {
		dogroup();
		return false;
    });
});


// 通讯录名片操作

// 名片操作
function docard() {
	$("#btnCard").attr("disabled", true);
	var formdata = $("#frmCard").fastSerialize();

	$.post("/member/my/docard/", formdata, function(msg) {
        ajaxloading();
		$("#btnCard").attr("disabled", false);

		if ("message" == msg) {
			ajaxhint(false);
			popup_message("/member/index/message/");
			goToUrl("/member/my/address/type/card/", 1000);
			return false;
		}

		ajaxhint(true, msg);
	});

	return false;
}

// 组修改之前
function dogroupmodiBefore(gid, gname) {
	if ("重命名" == $("#modi"+gid).html()) {
		$("#gname").val(gname);
		$("#gid").val(gid);
		$("#btnGroup").val("重命名");
		$("#modi"+gid).html("取消");
	}
	else {
		$("#gname").val("");
		$("#gid").val($("#tmpgid").val());
		$("#btnGroup").val("新建组");
		$("#modi"+gid).html("重命名");
	}
}


// 通讯录组操作

// 组操作
function dogroup() {
	ajaxloading(true);
	$("#btnGroup").attr("disabled", true);
	var formdata = $("#frmGroup").fastSerialize();

	$.post("/member/my/dogroup/", formdata, function(msg) {
		ajaxloading();
		$("#btnGroup").attr("disabled", false);

		if ("message" == msg) {	
			popup_message("/member/index/message/");
			goToUrl("reload", 1000);
			return false;
		}
	});

	return false;
}

// 组删除之前
function dogroupdelBefore(gid) {
	var popup = new Popup({ contentType:3, isReloadOnClose:false, width:340, height:105});
	popup.setContent("title", "校友会提示您");
	popup.setContent("confirmCon", "您确定要删除该组吗？(请先确认已无名片)");
	popup.setContent("callBack", dogroupdel);
	popup.setContent("parameter", { gid : gid, popup : popup });
	popup.build();
	popup.show();
	return false;
}

// 组删除
function dogroupdel(para) {
	ajaxloading(true);

	$.post("/member/my/dogroupdel/", { gid : para["gid"] }, function(msg) {
		ajaxloading();
		para["popup"].close();

		if ("hide" == msg) {
			$("#group" + para["gid"]).hide(0);
			return false;
		}
	});

	return false;
}
