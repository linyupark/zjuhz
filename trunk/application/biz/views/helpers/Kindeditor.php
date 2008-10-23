<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Kindeditor.php
 */

class Zend_View_Helper_Kindeditor
{
	/**
     * KindEditor编辑器Js文件
     * 
     * @param string $width
     * @param string $height
     * @return string
     */
	public function kindeditor($id = 'content', $width, $height)
	{
		return '<script type="text/javascript" src="/static/editor/kind/KindEditor.js"></script>
		    <script type="text/javascript">
	        var editor = new KindEditor("editor");
	        editor.uploadMode   = false;
	        editor.hiddenName   = "'.$id.'";
			editor.editorWidth  = "'.$width.'";
			editor.editorHeight = "'.$height.'";
			editor.show();
			</script>';
	}
}
