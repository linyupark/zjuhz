// 互助随机问题广播
function showNotice() {
	var notice_num = parseInt($(".help_notice a").length);
	var show_num = parseInt($(".help_notice").attr("start"));

	$(".help_notice a").hide();
	$(".help_notice a:eq("+(show_num-1)+")").show("slow");
	show_num++;

	if(show_num > notice_num) {
		show_num = 1;
	}

	$(".help_notice").attr("start", show_num);
}

// 获取验证码
function getVerifyCode() {
	verify("verify", "/help/index/verify/");
	$("#vcode").val("");
}

// 跳转
function goToHelp(name) {
	var url = "/help/";

	if ("question_insert" == name) {
		url = url + "question/insert/title/" + encodeURIComponent($("#wd").val());
	}

	window.location.href = url;
}

// 广播延时
function helpNotice() {
	var t = setInterval("showNotice()", 5000);
}

// 排行榜
function helpRank() {
	$(".help_rank").each(function(i) {
		$(".help_rank:eq("+i+") a.rank").each(function(j) {
		    $(this).css("background", "url(/static/images/icon/rank/n"+(j+1)+".jpg) no-repeat left center");
	    }); 
	});
}

// 收藏问题
function collection_insert(qid) {

	if (0 < qid) {
		$.post("/help/collection/doinsert/", { qid: qid }, function(msg) {
			alert("已收藏成功！"); $("#collection").hide() }
		); 
	}
}
