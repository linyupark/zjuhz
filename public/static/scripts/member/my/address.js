document.write("<script type=\"text/javascript\" src=\"/static/scripts/member/my/my.js\"></script>");

$(function() {
    $("#frmCard").submit( function() {
		docard();
		return false;
    });

    $("#frmInvite").submit( function() {
		return false;
    });

    $("#frmGroup").submit( function() {
		dogroup();
		return false;
    });
});

// 卡修改之前
function docardBefore(cid)
{
	var pop = new Popup({ contentType:1, isReloadOnClose:false, width:543, height:350 });
	pop.setContent("title", "名片新建/修改/查看");
	pop.setContent("contentUrl", "/member/my/popup/type/card/cid/"+cid);
	pop.build();
	pop.show();
	return false;
}

// 邀请之前
function doinviteBefore(cid)
{
	$.post("/member/my/doinvite/", { cid : cid }, function(msg) {

		var pop = new Popup({ contentType:1, isReloadOnClose:false, width:350, height:242 });
		pop.setContent("title", "邀请好友加入校友会");
		pop.setContent("contentUrl", "/member/my/popup/type/invite/cid/"+cid);
		pop.build();
		pop.show();
		return false;

	});

}

// 组修改之前
function dogroupmodiBefore(gid, gname)
{
	if ($("#modi"+gid).html() == "改名")
	{
		$("#gname").val(gname);
		$("#gid").val(gid);
		$("#btnGroup").val("重命名");
		$("#modi"+gid).html("取消");
	}
	else
	{
		$("#gname").val("");
		$("#gid").val($("#tmpgid").val());
		$("#btnGroup").val("新建组");
		$("#modi"+gid).html("改名");
	}
}

// 组删除之前
function dogroupdelBefore(gid)
{
	var popup = new Popup({ contentType:3, isReloadOnClose:false, width:340, height:105});
	popup.setContent("title", "删除组");
	popup.setContent("confirmCon", "您确定要彻底删除这个组吗？");
	popup.setContent("callBack", dogroupdel);
	popup.setContent("parameter", { gid : gid, popup : popup });
	popup.build();
	popup.show();
	return false;
}

// 名片操作
function docard() {
	$("#btnCard").attr("disabled", true);
	var formdata = $("#frmCard").fastSerialize();

	$.post("/member/my/do"+type+"/", formdata, function(msg) {

		if (msg == "message") {
			popup_message("/member/index/message/");
			goToUrl("/member/my/address/type/"+type+"/", 1000);
			return false;
		}

		$("#btnCard").attr("disabled", false);
		alert(msg);
	});

	return false;
}

// 组操作
function dogroup() {
	ajaxloading(true);
	$("#btnGroup").attr("disabled", true);
	var formdata = $("#frmGroup").fastSerialize();

	$.post("/member/my/do"+type+"/", formdata, function(msg) {
		ajaxloading();

		if (msg == "message") {	
			popup_message("/member/index/message/");
			goToUrl("reload", 1000);
			return false;
		}

		$("#btnGroup").attr("disabled", false);
	});

	return false;
}

// 组删除
function dogroupdel(para) {
	ajaxloading(true);

	$.post("/member/my/do"+type+"del/", { gid : para["gid"] }, function(msg) {
		ajaxloading();
		para["popup"].close();

		if (msg == "hide") {
			$("#group" + para["gid"]).hide(0);
			return false;
		}
	});

	return false;
}
