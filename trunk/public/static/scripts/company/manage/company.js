$(function() {
    $("#frmBasic").submit( function() {
		dobasic();
		return false;
    });
});

// 基础信息
function dobasic() {
	ajaxloading(true);
	$("#btnBasic").attr("disabled", true);
	var formdata = $("#frmBasic").fastSerialize();
	formdata.push({name:'content', value:editor.data()});

	$.post("/company/manage/dobasic/", formdata, function(msg) {
		ajaxloading();
        $("#btnBasic").attr("disabled", false);

		if ("message" == msg) {
			ajaxhint(false);
			popup_message("/company/index/message/");
			goToUrl("reload", 1000);
			return false;
		}

		ajaxhint(true, msg);
	});

	return false;
}
