//获取DOM对象
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

//复制进剪贴板
function copyToClipBoard(obj,msg){
	window.clipboardData.setData("Text",getObject(obj).value);
	alert(msg);
}

//验证码不重复
function verify(img_id,url){
   var t = new Date();
   t = t.getTime();
   $('#'+img_id).attr('src',url+'?'+t);
}

//ajax loading
function ajaxloading(show){
	show ? $("#ajaxloading").show() : $("#ajaxloading").hide();
	$("#ajaxloading").text('Loading...');
}

//ajax hint
function ajaxhint(show,msg,id){
	var id = id ? id : 'ajaxhint';
	show ? $("#"+id).show() : $("#"+id).hide();
	$("#"+id).text(msg);
}

