<?php

/**
 * 返回来过此gid群组之前用户到过哪些群组
 *
 */
class Zend_View_Helper_Associate
{
	function associate($gid)
	{
		$str = '<h3 class="pd10">来本群组的人还常去</h3>
		<ul class="mglf10 pdd10 sidebar">';
		$gids = GroupModel::info($gid, 'associate');
		if($gids == null) $str .= '<li>还没有相关记录</li>';
		else 
		{
			$gid_arr = explode(',', $gids);
			foreach ($gid_arr as $g)
			{
				$str .='<li>'.Cmd::icon('bullet_orange.png').' <a href="/group/home?gid='.$g.'">'.GroupModel::info($g, 'name').'</a></li>';
			}
		}
		return $str.'</ul>';
	}
}

?>