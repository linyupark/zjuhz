document.write("<script type=\"text/javascript\" src=\"/static/scripts/passwdcheck.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/jquery.selectboxes.js\"></script>");

$(function() {
    $("#frmRegister").submit( function() {
		doregister();
		return false;
    });

    $("#btnCheck").click( function() {
		docheck();
    });

    $("#btnReselect").click( function() {
		$("#selectbox").selectOptions("", true);
		$("#classes").val("");
    });

    $("#uname").blur( function() {
		docheck();
    });

    $("#major").focus( function() {
		putVerifyImg();
    });

    $("#rname").blur( function() {
		doclasses();
    });

    $("#ikey").blur( function() {
		doclasses();
    });

    $("#vcode").focus( function() {
		putVerifyImg();
    });

    $("#selectbox").blur( function() {
		$("#classes").val($("#selectbox").selectedValues());
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

	if (2 <= uname.length) {
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

// 好友班级显示
function doclasses() {
	var ikey = $("#ikey").val();

    if (10 == ikey.length && !$("#ikey").attr("readonly")) {
        $("#btnRegister").attr("disabled", true);

        $.getJSON("/member/register/doclasses/", { cid: ikey }, function(msg) {
			$("#selectbox").addOption(msg, false);

			if (msg) {
				$("#joinClass").show();
				$("#ikey").attr("readonly", true);
			}

        });

		$("#btnRegister").attr("disabled", false);
	}

	return false;
}
