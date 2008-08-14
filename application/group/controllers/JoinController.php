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
		$uid = $this->view->passport('uid');
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
	            $this->view->message = '<div class="success f14">成功加入该群组，系统将自动跳转到
				<a href="/group/home?gid='.$gid.'">群组首页</a></div>';
				echo Commons::js_jump('/group/home?gid='.$gid.',1);
			}
			
			// 针对半公开群组 2
			if($private == 2)
			{
				if(!GroupModel::joinApply($uid, $gid))
				$this->view->message = '<div class="error f14">递交申请失败，可能您已经申请过了。
					<button class="btn" onclick="history.back()">知道了，返回上一页</button></div>';
				else $this->view->message = '<div class="success f14">加入申请已经递交，请耐心等待审核。
				<button class="btn" onclick="history.back()">知道了，返回上一页</button></div>';
			}
			// 针对私密群组 3
			if($private == 3)
			{
				$this->view->message = '<div class="error f14">抱歉，该群组为私密群组，只有被该群组邀请才能加入。 
					<button class="btn" onclick="history.back()">知道了，返回上一页</button></div>';
			}
		}
	}
}
