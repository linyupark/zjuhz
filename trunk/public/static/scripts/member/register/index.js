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

    $("#rname").focus( function() {
		putVerifyImg();
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

		if ("redirect" == msg) {
            ajaxhint(false);
			goToUrl("/member/register/welcome/", 0);
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
	if (2 <= uname.length)
	{
		ajaxhint(true, "", "chkmsg");

		$("#btnCheck").attr("disabled", true);

		$.post("/member/register/docheck/", { uname: uname }, function(msg) {
			$("#btnCheck").attr("disabled", false);
			$("#btnRegister").attr("disabled", false);
			ajaxhint(true, msg, "chkmsg");
		});
	}

	return false;
}
