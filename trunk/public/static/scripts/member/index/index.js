$(document).ready(function(){
    $("#btnSubmit").click( function() {
		login();
    });
});

// µÇÂ¼Ìá½»
function login() {
	ajaxloading(true);
	$("#btnSubmit").attr("disabled", true);
	var formdata = $("#frmLogin").fastSerialize();

	$.ajax( {
		type   : "POST",
        url    : "/member/login/dologin/",
        data   : formdata,
	    success: function(msg) {
			if (msg == 'message') {
				window.location.href="/help/";
			}
			else {
				$("#btnSubmit").attr("disabled", false);
				ajaxhint(true,msg);
				ajaxloading();
			}
		}
	});

	return false;
}