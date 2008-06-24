<?php

/**
 * 论坛 TopicController
 * 
 * @author
 * @version 
 */

class TopicController extends Zend_Controller_Action
{
	function init()
	{
		$this->view->gid = $this->getRequest()->getParam('gid');
        $this->view->uid = Zend_Registry::get('sessCommon')->login['uid'];
        $this->view->role = GroupMemberModel::role($this->view->uid, $this->view->gid);
		$this->view->controller_name = $this->getRequest()->getControllerName();
        $this->view->action_name = $this->getRequest()->getActionName();
        $this->view->groupInfo = GroupModel::info($this->view->gid);
		$this->view->page = $this->_getParam('p', 1);
		$this->view->tid = $this->_getParam('tid');
	}
	
	# 列表
	public function indexAction() 
	{
		
	}
	
	# 详细帖
	public function showAction()
	{
		
	}
	
	# 发布帖
	public function newAction()
	{
		$request = $this->getRequest();
		$V = new Lp_Valid();
		$title = $V->of($request->getPost('title'), 'title', '话题标题', 'trim|strip_tags|num_between[2,200]');
	}
	
	# 编辑帖
	public function editAction()
	{
		
	}
	
	# 删除帖
	private function deleteAction()
	{
		
	}
}
