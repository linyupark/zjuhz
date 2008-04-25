<?php

	class Zend_View_Helper_Editor
	{
		function editor($width='100%', $height='300px')
		{
			return '<script type="text/javascript" src="/static/scripts/kind/KindEditor.js"></script>
					<script type="text/javascript">
					var editor = new KindEditor("editor");
					editor.hiddenName = "content"; //上面hidden的名字
					editor.editorWidth = "'.$width.'"; //宽度
					editor.editorHeight = "'.$height.'"; //高度
					editor.show();
					</script>';
		}
	}