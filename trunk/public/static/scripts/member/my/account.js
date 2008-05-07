document.write("<script type=\"text/javascript\" src=\"/static/scripts/member/my/my.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/passwdcheck.js\"></script>");

$(function() {
    $("#frmAccount").submit( function() {
		doaccount();
		return false;
    });

    $("#pswd").focus( function() {
		putVerifyImg();
    });

    $("#vcode").focus( function() {
		putVerifyImg();
    });
});

// 账号安全
function doaccount() {
	ajaxloading(true);
	$("#btnAccount").attr("disabled", true);
	var formdata = $("#frmAccount").fastSerialize();

	$.post("/member/my/do"+type+"/", formdata, function(msg) {
		ajaxloading();
        $("#btnAccount").attr("disabled", false);

		if ("message" == msg) {
			ajaxhint(false);
			popup_message("/member/index/message/");
			goToUrl("/member/my/account/type/"+type+"/", 1000);
			return false;
		}
		
		ajaxhint(true, msg);
		getVerifyCode();
	});

	return false;
}
