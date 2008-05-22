<?php

	class Zend_View_Helper_Albumcategory
	{
		/**
		 * 返回当前班级相册的分类
		 *
		 */
		function albumcategory($class_id, $f)
		{
			$categories = AlbumModel::fetchCategory($class_id);
			$options = '<option value="'.urlencode('未分类').'">未分类</option>';
			foreach ($categories as $v)
			{
				if($v['class_album_category'] != '未分类')
				{
					$selected = '';
					if($v['class_album_category'] == $f)
					$selected = 'selected="selected"';
					$options .= '<option '.$selected.' value="'.urlencode($v['class_album_category']).'">'.$v['class_album_category'].'</option>';
				}		
			}
			return $options;
		}
	}
?>