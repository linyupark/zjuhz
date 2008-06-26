<?php

/**
 * 群组的申请加入相关操作 JoinController
 * 
 * @author zjuhz.com
 * @version 
 */


class JoinController extends Zend_Controller_Action 
{
	# 发送新的加入请求
	public function newAction() 
	{
		// 获取群组类型
		$gid = $this->getRequest()->getParam('gid',null);
		$uid = $this->view->passport('uid', null);
		$private = GroupModel::info($gid, 'private');
		
		if($gid == null || $uid == null)
		{
			$this->_forward('error', 'error');
		}
		else 
		{
			// 针对公开群组 1
			if($private == 1)
			{
				$data = array(
	                        'group_id' => $gid,
	                        'user_id' => $uid,
	                        'join_time' => time(),
	                        'last_access' => time(),
	                        'role' => 'member'
	                    );
	            GroupMemberModel::join($uid, $gid, $data);
	            GroupModel::update(array('member_num'=>new Zend_Db_Expr('member_num+1')), $gid);
	            UserModel::haveGroup($uid);
	            Cmd::flushGroupSession();
	            $this->view->message = '<div class="success">成功加入该群组，返回<a href="/group/home?gid='.$gid.'">群组首页</a></div>';
			}
			
			// 针对半公开群组 2
			
			// 针对私密群组 3
		}
	}
}
