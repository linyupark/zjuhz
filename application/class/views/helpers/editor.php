<?php

	class Zend_View_Helper_Editor
	{
		function editor($width='100%', $height='300px')
		{
			return '
				<script type="text/javascript" src="/static/scripts/kind/KindEditor.js"></script>
<script type="text/javascript">
var editor = new KindEditor("editor");
editor.safeMode = true; // true 或 false
editor.uploadMode = false; // true 或 false
editor.hiddenName = "content"; //上面hidden的名字
editor.editorType = "simple"; // simple 或 full
editor.skinPath = "/static/scripts/kind/skins/tiny/"; //编辑器图片目录
editor.iconPath = "/static/scripts/kind/icons/";
editor.editorWidth = "'.$width.'"; //宽度
editor.editorHeight = "'.$height.'"; //高度
editor.show();
function KindSubmit() {
	alert(editor.data()); //把编辑器的内容放在content隐藏Form里。
	return false;
}
</script>';
		}
	}