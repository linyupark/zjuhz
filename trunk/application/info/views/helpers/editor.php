<?php

	class Zend_View_Helper_editor
	{
		function editor($content_id = 'content', $width='650', $height='480')
		{
			return '<iframe src="/static/editor/sina/Edit/editor.htm?id='.$content_id.'&ReadCookie=0" frameBorder="0" marginHeight="0" marginWidth="0" scrolling="No" width="'.$width.'" height="'.$height.'"></iframe>';
		}
	}