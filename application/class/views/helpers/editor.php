<?php

	class Zend_View_Helper_editor
	{
		function editor($class_id, $width='100%', $height='300px')
		{
			return '<script type="text/javascript" src="/static/editor/kind/KindEditor.js"></script>
					<script type="text/javascript">
					var editor = new KindEditor("editor");
					editor.uploadMode = true;
					editor.imageUploadCgi = "/static/editor/kind/upload_cgi/upload.php?fpath='.$_SERVER['DOCUMENT_ROOT'].'/static/classes/'.$class_id.'/images/&ipath=/static/classes/'.$class_id.'/images/&size=100000";
					editor.imageAttachPath = "/static/classes/'.$class_id.'/images/";
					editor.hiddenName = "content";
					editor.editorWidth = "'.$width.'"; 
					editor.editorHeight = "'.$height.'"; 
					editor.show();
					</script>';
		}
	}