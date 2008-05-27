<?php 
	
	/**
	 * 校友发表评论
	 *
	 */
	class CommentController extends Zend_Controller_Action 
	{
		/**
		 * 角色
		 *
		 * @var string
		 */
		private $_sessCommon;
		
		function init()
		{
			if(!$this->getRequest()->isXmlHttpRequest()) exit();
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		}
		
		private function noguest()
		{
			if($this->_sessCommon->role == 'guest')
			{
				$this->_helper->layout->setLayout('error');
				$this->view->err_tip = '只有登陆后才能进行评论!';
			}
		}
		
		# 显示评论带分页
		function viewAction()
		{
			$request = $this->getRequest();
			$entity_id = $request->getPost('entity_id');
			$page = $request->getPost('page',1);
			$Db = Zend_Registry::get('dbInfo');
			Page::$pagesize = 8;
			$rowset = $Db->fetchAll('SELECT `comment_id` FROM `tbl_comment` WHERE `entity_id` = '.(int)$entity_id);
			
			if(FALSE != $rowset)
			{
				Page::create(array(
				'href_open' => '<a href="javascript:commentView('.$entity_id.',%d)">',
				'href_close' => '</a>',
				'num_rows' => count($rowset),
				'cur_page' => $page
				));
				
				$offset = ($page-1)*Page::$pagesize;
				
				$rows = $Db->fetchAll('SELECT * FROM `tbl_comment` 
										WHERE `entity_id` = '.(int)$entity_id.' 
										ORDER BY `comment_time` DESC 
										LIMIT '.$offset.','.Page::$pagesize);
				
				$this->view->comments = $rows;
				$this->view->pagination = Page::$page_str;
				$this->view->pages = Page::$num_pages;
			}
			$this->render('view');
		}
		
		# 提交评论信息
		function postAction()
		{
			$this->noguest();
			$request = $this->getRequest();
			$entity_id = $request->getPost('entity_id');
			$username = $this->_sessCommon->login['username'];
			$time = time();
			$content = nl2br(strip_tags(htmlspecialchars($request->getPost('content'))));
			
			$Comment = new CommentModel();
			$Comment->insert(array(
				'entity_id' => $entity_id,
				'comment_username' => $username,
				'comment_time' => time(),
				'comment_content' => $content
			));
			$this->_helper->layout->setLayout('success');
			$this->view->suc_tip = '评论成功!';
		}
		
		# 显示评论提交表单
		function formAction()
		{
			$this->noguest();
			$this->view->entity_id = $this->getRequest()->getPost('entity_id');
			$this->render('form');
		}
	}
	
	
	
	
	