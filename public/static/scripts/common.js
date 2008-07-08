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
function copyToClipBoard(value, msg) {
	if (window.clipboardData) {
		window.clipboardData.setData("Text", value);
	}
	else if (window.netscape) {
		netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if (!clip) return;
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if (!trans) return;
        trans.addDataFlavor('text/unicode');
		var str = new Object();
        var len = new Object();
        var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var valuetext = value;
		str.data = valuetext;
        trans.setTransferData("text/unicode",str,valuetext.length*2);
        var clipid = Components.interfaces.nsIClipboard;
        if (!clip) return false;
        clip.setData(trans,null,clipid.kGlobalClipboard);
	}

	alert(msg);
    return true;
}

// 验证码不重复
function verify(img_id, url){
   var t = new Date();
   t = t.getTime();
   $("#"+img_id).attr("src", url+"?"+t);
}

// ajax loading
function ajaxloading(show){
	show ? $("#ajaxloading").show() : $("#ajaxloading").hide();
	$("#ajaxloading").text("Loading...");
}

// ajax hint
function ajaxhint(show, msg, id){
	var id = id ? id : "ajaxhint";
	show ? $("#"+id).show() : $("#"+id).hide();
	$("#"+id).text(msg);
}

function input_focus(class_name) {
	$("."+class_name).css("background", "#ffc");
	$("."+class_name).css("color", "#333");
}

function input_blur(class_name) {
	$("."+class_name).css("background", "#fff");
	$("."+class_name).css("color", "#aaa");
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
	var pop = new Popup({ contentType:1, isReloadOnClose:false, width:340, height:80 });
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
	$("#btnLogin").attr("disabled", true);
	var formdata = $("#relogin_form").fastSerialize();

	$.post("/member/login/dologin/", formdata, function(msg) {
		if ("redirect" == msg) {
			var redirect = $("#redirect").val();
			if (redirect) {
				goToUrl(redirect, 0);
			}
			else {
				history.go(0);
			}
			return false;
		}

		$("#btnLogin").attr("disabled", false);
		alert(msg);
	});

	return false;
}

// 载入时默认焦点
function onLoadToInputFocus(objname){
	getObject(objname).focus();
}

// 滚动
function AutoScroll(obj){
    $(obj).find("ul:first").animate({
            marginTop:"-25px"
    },500,function(){
        $(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
    });
}


// 载入用户名片
function ucard(uid) {

}

function ucardInit() {
	$('a[href^="javascript:ucard"]').bind('mouseover', function() {
		$('.cardContainer').remove();
		var uid = $(this).attr('href');
		uid = uid.replace('javascript:ucard(','');
		uid = parseInt(uid.replace(')',''));
		var x = $(this).offset().left;
		var y = $(this).offset().top+20;
		var w = $('body').width();
		var h = $('body').height();
		var card_w = 250;
		$(this).after('<div class="cardContainer"><img src="/static/images/loading1.gif"></div>');
		$('.cardContainer').css({border:'1px solid #ccc', opacity:'0.9', position:'absolute', width:card_w, top:y, background:'#f5f5f5'});
		if((card_w+x) > w) {
			x = x-card_w;
		}

		$('.cardContainer').css({left:x});
		$('.cardContainer').load('/member/alumni/card/uid/' + uid + '/');
	});
}
