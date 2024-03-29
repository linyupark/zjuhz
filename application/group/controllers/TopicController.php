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
        $this->view->elite = $this->_getParam('elite');
        $this->view->pagesize = 20;
	}
	
	# 详细帖
	public function showAction()
	{
		$this->view->topic = GroupTopicModel::fetch($this->view->tid);
		// 没有主题就转向错误提示
		if($this->view->topic == FALSE) 
		{
			$this->_forward('error', 'error');
		}
		else
		{
			Cmd::flushGroupSession();
			Page::$pagesize = 10;
			$reply = GroupReplyModel::topicIndex($this->view->tid, Page::$pagesize, $this->view->page);
			Page::create(array(
				'href_open' => '<a href="/group/topic/show?gid='.$this->view->gid.'&tid='.$this->view->tid.'&p=%d">',
				'href_close' => '</a>',
				'num_rows' => $reply['numrows'],
				'cur_page' => $this->view->page
			));
			if(Page::$num_pages == 0) $to_page = 1;
			else 
			{ 
				$to_page = Page::$num_pages;
				if(Page::$num_pages*Page::$pagesize == $reply['numrows'])
				$to_page += 1;
			}
			$this->view->to_page = $to_page; // 回帖后转到的页
			$this->view->replies = $reply['rows'];
			$this->view->pages = Page::$num_pages;
			$this->view->pagination = Page::$page_str;
			$this->view->hash = $this->getRequest()->getParam('hash', null);
			
			// 增加点击判断
			if(Zend_Registry::get('sessGroup')->topic[$this->view->tid] == null)
			{
				GroupTopicModel::update(array(
					'click_num' => new Zend_Db_Expr('click_num + 1')
				), $this->view->tid);
				Zend_Registry::get('sessGroup')->topic[$this->view->tid] = 1;
			}
		}
	}
	
	# 发布帖
	public function newAction()
	{
		$request = $this->getRequest();
		if($request->isPost())
		{
			$V = new Lp_Valid();
			$title = $V->of($request->getPost('title'), 'title', '话题标题', 'trim|strip_tags|str_between[2,200]');
			$content = $V->of($request->getPost('content'), 'content', '话题内容', 'trim|required');
			$tags = $V->of($request->getPost('tags'), 'tags', '话题标签', 'trim|required');
			$usenickname = (int)$request->getPost('usenickname'); // 是否使用匿名
			if($V->getMessages() != false)
			{
				$this->_helper->layout->setLayout('error');
				echo '<ul class="error">'.$V->getMessages('<li>','</li>').'</ul>';
			}
			elseif(GroupTopicModel::isposted($title) == true)
			{
				$this->_helper->layout->setLayout('error');
				echo '<ul class="error"><li>有相同的标题已经存在，请更换标题</li></ul>';
			}
			else
			{
				$data = array(
					'is_nickname' => $usenickname,
					'group_id' => $this->view->gid,
					'pub_time' => time(),
					'reply_time' =>time(),
					'pub_user' => $this->view->uid,
					'title' => $title,
					'content' => $content,
					'tags' => $tags
				);
				$id = GroupTopicModel::add($this->view->uid, $this->view->gid, $data);
				$this->_helper->layout->setLayout('success');
				echo '<div class="success">成功发布话题，增加1点群组积分。
				<a href="javascript:history.go(0)">继续发表</a>，1秒后自动跳转
				<a href="/group/topic/show?gid='.$this->view->gid.'&tid='.$id.'">刚发表的话题</a></div>';
                echo Commons::js_jump('/group/topic/show?gid='.$this->view->gid.'&tid='.$id, 1);
			}
		}
	}
	
    # 编辑回复
    public function replyeditAction()
    {
        $page = $this->_getParam('p', 1);
        $reply_id = $this->_getParam('rid');
        $reply = GroupReplyModel::fetch($reply_id);
        if($reply['user_id'] != Cmd::myid()) $this->_redirect('/');
        
        $R = $this->getRequest();
        if($R->isPost()) // 修改
        {
            $data = array(
                'title' => $R->getPost('title'),
                'content' => $R->getPost('content')
            );
            GroupReplyModel::update($data, $reply_id);
            echo $R->getPost('content');
            $this->_redirect('/topic/show?gid='.$this->view->gid.'&tid='.$this->view->tid.'&p='.$page.'#topic_title_'.$reply_id);
        }

        $this->view->reply = $reply;
        $this->render('reply-edit');
    }
    
	# 编辑帖
	public function editAction()
	{
		if(!Cmd::isMyTopic($this->view->tid) && !Cmd::isManager($this->view->gid))
        $this->_forward('error', 'error');
        else
        {
        	$request = $this->getRequest();
			if($request->isPost())
			{
				$V = new Lp_Valid();
				$title = $V->of($request->getPost('title'), 'title', '话题标题', 'trim|strip_tags|str_between[2,200]');
				$content = $V->of($request->getPost('content'), 'content', '话题内容', 'trim|required');
				$tags = $V->of($request->getPost('tags'), 'tags', '话题标签', 'trim|required');
				if($V->getMessages() != false)
				{
					$this->_helper->layout->setLayout('error');
					echo '<ul class="error">'.$V->getMessages('<li>','</li>').'</ul>';
				}
				else
				{
					$data = array(
						'title' => $title,
						'content' => $content,
						'tags' => $tags,
						'mod_time' => time()
					);
					GroupTopicModel::update($data, $this->view->tid);
                    $url = '/group/topic/show?gid='.$this->view->gid.'&tid='.$this->view->tid;
                    GroupEventModel::update(array('name'=>$title), $url);
					echo Commons::js_jump($url);
				}
			}
			
            //提取主题内容，加入表单
            $this->view->topic = GroupTopicModel::fetch($this->view->tid);
        }
	}
	
	# 删除帖
	private function deleteAction()
	{
		
	}
}
