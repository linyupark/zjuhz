function showTab(num) {
	$('#help_tab_btn a').css({borderColor:'#C4DBBF',backgroundColor:'#fff',color:'#5C7B23',borderBottomColor:'#fff'});
	$('#help_tab_btn a:eq('+num+')').css({borderColor:'#FAD1A5',backgroundColor:'#FFFCF0',color:'#C26400',borderBottomColor:'#fff'});
	$('.tab_content').hide();
	$('.tab_content:eq('+num+')').show();
}

function showNotice() {
	var notice_num = parseInt($('.help_notice a').length);
	var show_num = parseInt($('.help_notice').attr('start'));
	$('.help_notice a').hide();
	$('.help_notice a:eq('+(show_num-1)+')').show('slow');
	show_num++;
	if(show_num > notice_num) 
	{
		show_num = 1;
	}
	$('.help_notice').attr('start',show_num);
}

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
	verify('verify','/help/index/verify/');
	$("#vcode").val("");
}

// 跳转到提问页
function redirectAsk()
{
	var url = '/help/question/add/title/' + encodeURIComponent($("#keywords").val());
	window.location.href = url;
}