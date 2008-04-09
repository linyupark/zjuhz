// 获取验证码
function getVerifyCode() {
	verify('verify','/member/index/verify/');
	$("#vcode").val("");
}