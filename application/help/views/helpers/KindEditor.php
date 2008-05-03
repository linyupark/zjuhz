<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:KindEditor.php
 */

class Zend_View_Helper_KindEditor
{
	/**
     * 载入KindEditor编辑器
     * 
     * @param string $width
     * @param string $height
     * @return string
     */
	public function kindEditor($width, $height)
	{
		return '<script type="text/javascript" src="/static/scripts/kind/KindEditor.js"></script>
		    <script type="text/javascript">
	        var editor = new KindEditor("editor");
	        editor.uploadMode   = false;
	        editor.hiddenName   = "content";
			editor.editorWidth  = "'.$width.'";
			editor.editorHeight = "'.$height.'";
			editor.show();
			</script>';
	}
}
