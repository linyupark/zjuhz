<?php

class GroupTopicModel
{
	# 增加新的话题
	static function add($uid, $gid, $data)
	{
		$db = Zend_Registry::get('dbGroup');
		$row = $db->fetchRow('SELECT `topic_id` FROM `tbl_group_topic`
							 WHERE `title` = ?', $data['title']);
		//避免重复数据
		if($row != false) return false;
		else
		{
			$db->insert('tbl_group_topic', $data);
			$topic_id = $db->lastInsertId();
			// 更新群组话题数
			GroupModel::update(array(
				'topic_num'=>new Zend_Db_Expr('topic_num + 1'),
				'update_time'=>time()	
			), $gid);
			// 更新个人群组积分
			UserModel::coinMod($uid, '+1');
			// 更新最新事件
			GroupEventModel::insert(array(
				'group_id' => $gid,
				'user_id' => $uid,
				'time' => time(),
				'type' => 1, //类型1为话题
				'url' => '/group/topic/show?gid='.$gid.'&tid='.$topic_id // 转到事件地址
			));
		}
	}
	
	# 话题索引
    static function index($gid, $pagesize, $page)
    {
        $db = Zend_Registry::get('dbGroup');
		
		// 获取话题总数
		$row = $db->fetchRow('SELECT COUNT(`topic_id`) AS `numrows`
                             FROM `tbl_group_topic`
                             WHERE `group_id` = ?',$gid);
		
		$result['numrows'] = $row['numrows'];
		$offset = ($page-1)*$pagesize;
		
		$rows = $db->fetchAll('SELECT * FROM `vi_group_topic` 
					  WHERE `group_id` = '.$gid.'
                      ORDER BY `is_top` DESC, `pub_time` DESC
                      LIMIT '.$offset.','.$pagesize);
		$result['rows'] = $rows;
		
		return $result;
    }
}

?>