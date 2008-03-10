// 获取DOM对象
function getObject(obj)
{
	if (document.getElementById(obj))
	{
		return document.getElementById(obj);
	}
	else if(document.all)
	{
		return document.all[obj];
	}
	else if(document.layers)
	{
		return document.layers[obj];
	}
}

// 复制进剪贴板
function copyToClipBoard(obj,msg)
{
	window.clipboardData.setData("Text",getObject(obj).value);
	alert(msg);
}
