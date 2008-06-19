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

	$.post("/company/my/dojoin/", formdata, function(msg) {
		ajaxloading();
        $("#btnJoin").attr("disabled", false);

		if ("message" == msg) {
			ajaxhint(false);
			popup_message("/company/index/message/");
			goToUrl("/company/my/company/type/auditing/", 2000);
			return false;
		}

		ajaxhint(true, msg);
	});

	return false;
}
