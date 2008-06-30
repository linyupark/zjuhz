<?php
/**
 * 显示群组最新的X条事件
 **/
class Zend_View_Helper_Newevent
{
	function newevent($limit)
	{
		$str = '<ul class="mglf10 mgd10">';
		$events = GroupEventModel::fetch($limit);
		if(count($events) == 0)
		{
			$str .= '<li>没有任何群组事件发生。</li>';
		}
		else
		{
			foreach($events as $e)
			{
				if($e['type'] == 1) //话题
				{
					$str .= '<li class="dashBorder pd5" style="line-height:150%"><a href="/group/member/profile?id='.$e['user_id'].'">'.UserModel::fetch($e['user_id'], 'realName').'</a>
					'.Lp_Date::timespan($e['time']).'前<br />
					在 <a href="/group/topic?gid='.$e['group_id'].'">'.GroupModel::info($e['group_id'], 'name').'</a> 发布：
					<a href="'.$e['url'].'">'.$e['name'].'</a></li>';
				}
				if($e['type'] == 2) //图片
				{}
				if($e['type'] == 3) //活动
				{}
			}
		}
		return $str.'</ul>';
	}
}

?>