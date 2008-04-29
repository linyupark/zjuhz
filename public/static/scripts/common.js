// 获取DOM对象
function getObject(obj){
	if (document.getElementById(obj))	{
		return document.getElementById(obj);
	}
	else if(document.all)	{
		return document.all[obj];
	}
	else if(document.layers)	{
		return document.layers[obj];
	}
}

// 复制进剪贴板
function copyToClipBoard(obj,msg){
	window.clipboardData.setData("Text",getObject(obj).value);
	alert(msg);
}

// 验证码不重复
function verify(img_id,url){
   var t = new Date();
   t = t.getTime();
   $("#"+img_id).attr("src",url+"?"+t);
}

// ajax loading
function ajaxloading(show){
	show ? $("#ajaxloading").show() : $("#ajaxloading").hide();
	$("#ajaxloading").text("Loading...");
}

// ajax hint
function ajaxhint(show,msg,id){
	var id = id ? id : "ajaxhint";
	show ? $("#"+id).show() : $("#"+id).hide();
	$("#"+id).text(msg);
}

function input_focus(class_name) {
	$("."+class_name).css("background","#ffc");
	$("."+class_name).css("color","#333");
}

function input_blur(class_name) {
	$("."+class_name).css("background","#fff");
	$("."+class_name).css("color","#aaa");
}

// 写入img空标签
function putVerifyImg() {
	var isPutVerifyImg = $("#isPutVerifyImg").val();
	if("show" == isPutVerifyImg)
	{
		$("#putVerifyImg").html("<a href=javascript:getVerifyCode();><img id='verify'></a>");
		$("#isPutVerifyImg").remove();
		//$("#isPutVerifyImg").val("none");
		getVerifyCode();		
	}
}

// 信息提示
function popup_message(url) {
	var pop = new Popup({ contentType:1, isReloadOnClose:true, width:340, height:80 });
	pop.setContent("title", "校友会提示您");
	pop.setContent("contentUrl", url);
	pop.build();
	pop.show();
	return false;
}

// 延时跳转
function goToUrl(url, sec) {
	setTimeout("to('" + url + "')", sec);
}

// 直接跳转
function to(url) {
	if ("reload" == url) {
		window.location.reload();
	}
	else {
		top.location.href = url;
	}
}

// 会员登录
function dologin() {
    ajaxloading(true);
	$("#btnLogin").attr("disabled", true);
	var formdata = $("#relogin_form").fastSerialize();

	$.post("/member/login/dologin/", formdata, function(msg) {
		ajaxloading();

		if ("redirect" == msg) {
			history.go(0);
			return false;
		}

		$("#btnLogin").attr("disabled", false);
		alert(msg);
	});

	return false;
}
