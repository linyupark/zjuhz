<?php

/**
 * 群组成员信息页 MemberController
 * 
 * @author
 * @version 
 */

class MemberController extends Zend_Controller_Action
{
	
	public function init()
	{
		// 默认获取用户id到视图
		$this->view->uid = $this->_getParam('uid', null);
	}
	
	# 指定id用户的信息(加入的群组，好友列表，给这个用户发站内短信，群组状态)
	public function profileAction()
	{
		if($this->view->uid == $this->view->passport('uid')) // 是自己转到我的群组资料
		{
			$this->_forward('profile','my');
		}
		else
		{
			$this->view->groups = GroupMemberModel::fetchByUid($this->view->uid);
		}
	}

}
