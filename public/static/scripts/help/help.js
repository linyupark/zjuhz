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
	if(show_num > notice_num) {
		show_num = 1;
	}
	$('.help_notice').attr('start',show_num);
}

// 获取验证码
function getVerifyCode() {
	verify('verify','/help/index/verify/');
	$("#vcode").val("");
}

// 跳转
function goUrl(name)
{
	var url = '/help/';

	if (name == 'question_insert') {
		url = url + 'question/insert/title/' + encodeURIComponent($("#keywords").val());
	}

	window.location.href = url;
}

// 广播延时
function helpNotice() {
	var t = setInterval("showNotice()",4000);
}

// 排行榜
function helpRank() {
	$('.help_rank').each(function(i) {
		$('.help_rank:eq('+i+') a.rank').each(function(j) {
		    $(this).css('background','url(/static/images/icon/rank/n'+(j+1)+'.jpg) no-repeat left center');
	    }); 
	});
}
