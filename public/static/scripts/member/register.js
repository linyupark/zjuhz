$(document).ready(function(){
    $("#btnSubmit").click( function() {
		register();
    });
    $("#btnChkUserName").click( function() {
		check();
    });
    $("#uname").blur( function() {
		check();
    });
    $("#vcode").focus( function() {
		putVerifyImg();
    });
});

// 注册提交
function register() {
	ajaxloading(true);
	$("#btnSubmit").attr("disabled", true);
	var formdata = $("#frmRegister").fastSerialize();
	$.ajax( {
		type   : "POST",
        url    : "/member/register/register/",
        data   : formdata,
	    success: function(msg) {
			$("#btnSubmit").attr("disabled", false);
			ajaxhint(true,msg);
			ajaxloading();
		}
	});

	return false;
}

// 检查帐号是否可用
function check() {
	var uname = $("#uname").val();
	if (uname.length >= 3)
	{
		ajaxloading(true);
		$("#btnChkUserName").attr("disabled", true);

		$.ajax( {
			type   : "POST",
	        url    : "/member/register/check/",
		    data   : 'uname='+uname,
			success: function(msg) {
				$("#btnChkUserName").attr("disabled", false);
				ajaxhint(true,msg,'chkmsg');
				ajaxloading();
			}
		});
	}
	else
	{
		ajaxhint(true,'','chkmsg');
	}

	return false;
}
