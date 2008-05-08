<?php

	class TopicController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->_sessClass = Zend_Registry::get('sessClass');
			$this->view->class_id = (int)$this->_getParam('c'); // 班级id
			$this->view->topic_id = (int)$this->_getParam('tid'); // 话题id
			$this->view->login = Zend_Registry::get('sessCommon')->login;
			if(!$this->view->class_id) //没有指定正确的参数
			{
				$this->_redirect('/home');
			}
		}
		
		# 话题列表
		function listAction()
		{
			$request = $this->getRequest();
			$type = $request->getParam('type');
			$page = (int)$request->getParam('p',1);
			
			$role = 'member';
			if(false == Cmd::isMember($this->view->class_id)) 
			$role = 'visitor';
			
			// 获取话题列
			$pagesize = 20;
			$result = TopicModel::fetchList($this->view->class_id, $role, $type, $pagesize, $page);
			Page::$pagesize = $pagesize;
			$pagination = Page::create(array(
				'href_open' => '<a href="list?c='.$this->view->class_id.'&type='.$type.'&p=%d">',
				'href_close' => '</a>',
				'num_rows' => $result['numrows'],
				'cur_page' => $page
			));
			$this->view->topics = $result['rows'];
			$this->view->pagination = $pagination;
			$this->view->type = $type;
			
		}
		
		# 修改话题 ---------------------------------------------------------
		function modifyAction()
		{
			// 获取话题的所有信息
			$topic = TopicModel::fetchDetail($this->view->topic_id);
			
			// 检查是否是作者在编辑自己的话题
			if($this->view->login['uid'] != $topic['class_topic_author'])
			$this->_redirect('/home');
			
			// 显示编辑页面
			$this->view->topic = $topic;
			$this->render('modify');
		}
		
		# 发布新话题 -------------------------------------------------------
		function postAction()
		{
			// 没有发布权限
			if(!Cmd::isMember($this->view->class_id)) $this->_redirect('/home');
			$this->view->title = '发布新话题';
			$this->render('index');
			$this->render('post');
		}
		
		# 查看话题详细 ----------------------------------------------------
		function viewAction()
		{
			// 获取话题可看人群范围(阅读权限判断)
			$topic = TopicModel::fetchDetail($this->view->topic_id);
			if($topic['class_topic_public'] == 0 && !Cmd::isMember($this->view->class_id))
			{
				//没有权力阅读
				$this->view->headTitle('没有权限阅读该班级话题');
				$this->render('nopower');
			}
			else // 有权力阅读
			{
				if($this->_sessClass->topic != $this->view->topic_id)
				{
					// 被阅读次数累计+1
					TopicModel::viewNumInc($this->view->topic_id);
					$this->_sessClass->topic = $this->view->topic_id;
				}
				$this->view->topic = $topic;
				$this->view->title = $topic['class_topic_title'];
				
				// 回复部分
				$request = $this->getRequest();
				$pagesize = 5;
				$page = (int)$request->getParam('p',1);
				$rows = TopicModel::fetchReply($this->view->topic_id, $pagesize, $page);
				Page::$pagesize = $pagesize;
				Page::create(array(
					'href_open' => '<a href="/class/topic/view?c='.
									$this->view->class_id.'&tid='.
									$this->view->topic_id.'&p=%d">',
					'href_close' => '</a>',
					'num_rows' => $rows['numrows'],
					'cur_page' => $page
				));
				$this->view->pages = Page::$num_pages;
				if($rows['numrows']%$pagesize == 0 || Page::$num_pages == 0)
				$this->view->pages += 1;
				$this->view->page = $page;
				$this->view->pagination = Page::$page_str;
				$this->view->replies = $rows['rows'];
				
				// 获取对应的回复内容
				$this->render('index');
				$this->render('view');
			}
			// 
			
			// 面对班级的
			
			// 面对全部校友
		}
	}