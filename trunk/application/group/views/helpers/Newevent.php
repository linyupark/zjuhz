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
					$str .= '<li class="dashBorder pd5" style="line-height:150%"><a onclick="location.href=\'/group/member/profile?uid='.$e['user_id'].'\'" href="javascript:ucard('.$e['user_id'].')">'.UserModel::fetch($e['user_id'], 'realName').'</a>
					<span class="quiet">'.Lp_Date::timespan($e['time']).'前</span><br />
					<a target="_blank" href="/group/home?gid='.$e['group_id'].'">'.GroupModel::info($e['group_id'], 'name').'</a> &gt;
					<a target="_blank" href="/group/topic?gid='.$e['group_id'].'">论坛</a> &gt; 
					<a href="'.$e['url'].'">'.$e['name'].'</a></li>';
				}
				if($e['type'] == 2) //图片
				{
					$str .= '<li class="dashBorder pd5" style="line-height:150%"><a onclick="location.href=\'/group/member/profile?uid='.$e['user_id'].'\'" href="javascript:ucard('.$e['user_id'].')">'.UserModel::fetch($e['user_id'], 'realName').'</a>
					<span class="quiet">'.Lp_Date::timespan($e['time']).'前</span><br />
					<a target="_blank" href="/group/home?gid='.$e['group_id'].'">'.GroupModel::info($e['group_id'], 'name').'</a> &gt;
					<a target="_blank" href="/group/album?gid='.$e['group_id'].'">相册</a> &gt; 
					发布了<span style="color:red">'.$e['name'].'</span>张图片';
				}
				if($e['type'] == 3) //活动
				{}
			}
		}
		return $str.'</ul>';
	}
}

?>