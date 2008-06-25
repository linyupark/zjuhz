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
		if($request->isPost())
		{
			$V = new Lp_Valid();
			$title = $V->of($request->getPost('title'), 'title', '话题标题', 'trim|strip_tags|str_between[2,200]');
			$content = $V->of($request->getPost('title'), 'content', '话题内容', 'trim|required|str_between[20,20000]');
			$tags = $V->of($request->getPost('tags'), 'tags', '话题标签', 'trim|required');
			if($V->getMessages() != false)
			{
				$this->_helper->layout->setLayout('error');
				echo '<ul class="error">'.$V->getMessages('<li>','</li>').'</ul>';
			}
			else
			{
				$data = array(
					'pub_time' => time(),
					'reply_time' =>time(),
					'pub_user' => $this->view->uid,
					'title' => $title,
					'content' => $content,
					'tags' => $tags
				);
				GroupTopicModel::add($this->view->uid, $this->view->gid, $data);
				$this->_helper->layout->setLayout('success');
				echo '<div class="success">成功发布话题，增加1点群组积分。
				<a href="javascript:history.go(0)">继续发表</a>，
				<a href="/group/topic?gid='.$this->view->gid.'">返回论坛首页</a></div>';
			}
		}
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
