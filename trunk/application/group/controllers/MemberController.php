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
		$this->view->gid = $this->getRequest()->getParam('gid');
		$this->view->uid = $this->_getParam('uid', null);
		$this->view->role = GroupMemberModel::role($this->view->uid, $this->view->gid);
		$this->view->controller_name = $this->getRequest()->getControllerName();
        $this->view->action_name = $this->getRequest()->getActionName();
		$this->view->groupInfo = GroupModel::info($this->view->gid);
		$this->view->page = $this->_getParam('p', 1);
	}
	
    /**
     * 发送邀请到我加入的群组
     * */
    public function inviteAction()
    {
        $this->view = $this->getHelper('viewRenderer')->view;
        
        $R = $this->getRequest();
        if($R->isPost()) // 批量邀请
        {
            $uid = $R->getPost('uid');
            $gids = $R->getPost('gid');
            foreach($gids as $g)
            {
                UserModel::invite($g, $uid);
            }
            echo '<div class="success">成功邀请'.UserModel::fetch($uid,'realName').'</div>';
        }
        
        else
        {
            // 邀请对方已经加入的群组
            $uid = (int)$this->_getParam('uid');
            $user_groups = GroupMemberModel::fetchByUid($uid);
            $filter_groups = array();
            foreach($user_groups as $g)
            {
                $filter_groups[] = $g['group_id']; // 需要过滤掉的群组
            }
            // 获取我加入的群组
            $this->view->uid = $uid;
            $this->view->filter_group = $filter_groups;
            $this->view->my_groups = GroupMemberModel::fetchByUid(Cmd::myid());
            $this->render('invite');
        }
    }
    
	/**
	 * 成员列表
	 * */
	public function indexAction()
	{
		$this->view->pagesize = 32;
	}
	
	/**
	 * 删除好友
	 * */
	public function removefriendAction()
	{
		if(!UserModel::delFriend(Cmd::myid(), $this->_getParam('uid', null)))
		echo '执行失败';
	}
	
	/**
	 * 加为好友
	 *
	 */
	public function befriendwithAction()
	{
		if(UserModel::isFriend(Cmd::myid(), $this->view->uid))
		{
			echo '<p class="notice">已经加为好友</p>';
		}
		
		else
		{
			 UserModel::addFriend(Cmd::myid(), $this->view->uid);
			 echo '<p class="success">添加成功</p>
			 <iframe src="/group/pm/system?type=friend&uid='.$this->view->uid.'" class="hide"></iframe>
			 ';
		}
	}
	
	# 指定id用户的信息(加入的群组，好友列表，给这个用户发站内短信，群组状态)
	public function profileAction()
	{
		if($this->view->uid == Cmd::myid()) // 是自己转到我的群组资料
		{
			$this->_forward('profile', 'my');
		}
		else
		{
			$this->view->groups = GroupMemberModel::fetchByUid($this->view->uid);
		}
	}
}
