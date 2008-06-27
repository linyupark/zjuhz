<?php

/**
 * 群组回复 ReplyController
 * 
 * @author zjuhz.com
 * @version 
 */

class ReplyController extends Zend_Controller_Action 
{
	function init()
	{
		$this->view->gid = $this->getRequest()->getParam('gid');
		$this->view->uid = Zend_Registry::get('sessCommon')->login['uid'];
	}
	
	function newAction()
	{
		if(false == Cmd::isGuest($this->view->gid))
		{
			$request = $this->getRequest();
			$album_id = (int)$request->getParam('aid',0);
			$topic_id = (int)$request->getParam('tid',0);
			$to_page = (int)$request->getParam('to',1);
			$title = strip_tags(trim($request->getPost('title')));
			$content = trim($request->getPost('content'));
			if(empty($content))
			{
				$this->_helper->layout->setLayout('error');
				echo '<div class="error">回复内容不能为空！</div>';
			}
			else
			{
				$data = array(
					'topic_id' => $topic_id,
					'album_id' => $album_id,
					'user_id' => $this->view->uid,
					'title' => $title,
					'content' => $content,
					'reply_time' => time()
				);
				
				$reply_id = GroupReplyModel::insert($data);

				$this->_helper->layout->setLayout('success');
					
				if($topic_id != 0) // 修改群组主题的回复状态
				{
					GroupTopicModel::update(array(
						'reply_time' => time(),
						'reply_num' => new Zend_Db_Expr('reply_num+1'),
						'reply_user' => $this->view->uid
					), $topic_id);
					UserModel::coinMod($this->view->uid, '+1');
					//GroupMemberModel::update($this->view->uid, array(
					//	'`active`' => new Zend_Db_Expr('`active` + 1')
					//)); // 增加成员在群组的活跃度
					echo '<div class="success">回复帖子成功！</div>';
					echo Commons::js_jump('/group/topic/show?gid='.$this->view->gid.'&tid='.$topic_id.'&p='.$to_page.'&hash='.$reply_id, 1);
				}
					
				if($album_id != 0) // 修改相册回复状态
				{
					
				}
			}
		}
	}
}
