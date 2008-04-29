document.write("<script type=\"text/javascript\" src=\"/static/scripts/passwdcheck.js\"></script>");

$(function() {
    $("#frmRegister").submit( function() {
		doregister();
		return false;
    });

    $("#btnCheck").click( function() {
		docheck();
    });

    $("#uname").blur( function() {
		docheck();
    });

    $("#vcode").focus( function() {
		putVerifyImg();
    });
});

// 会员注册
function doregister() {
	ajaxloading(true);
	$("#btnRegister").attr("disabled", true);
	var formdata = $("#frmRegister").fastSerialize();

	$.post("/member/register/doregister/", formdata, function(msg) {
		ajaxloading();

		if (msg == "redirect") {
            ajaxhint(false);
			goToUrl("/member/index/welcome/", 0);
			return false;
		}

		$("#btnRegister").attr("disabled", false);
		ajaxhint(true, msg);
		getVerifyCode();
	});

	return false;
}

// 账号是否可用
function docheck() {
	var uname = $("#uname").val();
    ajaxhint(true, "", "chkmsg");

	ajaxloading(true);
	$("#btnCheck").attr("disabled", true);

	$.post("/member/register/docheck/", { uname: uname }, function(msg) {
		ajaxloading();
		$("#btnCheck").attr("disabled", false);
		ajaxhint(true, msg, "chkmsg");
	});

	return false;
}
