$(function() {
    $("#frmJoin").submit( function() {
		dojoin();
		return false;
    });
});

// 申请加入
function dojoin() {
	ajaxloading(true);
	$("#btnJoin").attr("disabled", true);
	var formdata = $("#frmJoin").fastSerialize();
	formdata.push({name:'content', value:editor.data()});

	$.post("/biz/my/dojoin/", formdata, function(msg) {
		ajaxloading();
        $("#btnJoin").attr("disabled", false);

		if ("message" == msg) {
			ajaxhint(false);
			popup_message("/biz/index/message/");
			goToUrl("/biz/my/company/type/auditing/", 2000);
			return false;
		}

		ajaxhint(true, msg);
	});

	return false;
}
