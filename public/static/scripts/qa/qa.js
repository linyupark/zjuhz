// 写入img空标签
function putVerifyImg() {
	var isPutVerifyImg = $("#isPutVerifyImg").val();
	if(isPutVerifyImg == 'show')
	{
		$("#putVerifyImg").html("<a href=javascript:getVerifyCode();><img id='verify'></a>");
		$("#isPutVerifyImg").remove();
		//$("#isPutVerifyImg").val("none");
		getVerifyCode();		
	}
}

// 获取验证码
function getVerifyCode() {
	verify('verify','/ask/index/verify/');
	$("#vcode").val("");
}