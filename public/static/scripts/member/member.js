// д��img�ձ�ǩ
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

// ��ȡ��֤��
function getVerifyCode() {
	verify('verify','/member/index/verify/');
}