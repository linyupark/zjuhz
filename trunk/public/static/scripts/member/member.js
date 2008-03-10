$(document).ready(function() {
    $("#btnSubmit").click( function() {
		register();
    });
});

// ×¢²áÌá½»
function register() {
	$("#btnSubmit").attr("disabled", true);
	var formdata = $("#frmRegister").fastSerialize();
	
	$.ajax( {
		type   : "POST",
        url    : "/member/index.php/account/register/",
        data   : formdata,
	    success: function(msg) {
			$("#loading").text(msg);
			$("#btnSubmit").attr("disabled", false);
		}
	});

	return false;
}
