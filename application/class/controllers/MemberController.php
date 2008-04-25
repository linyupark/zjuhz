<?php

	/**
	 * 班级成员所具有的权限
	 */
	class MemberController extends Zend_Controller_Action 
	{
		function init()
		{
			// 注册全局SESSION
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			$this->_sessClass = Zend_Registry::get('sessClass');
			$this->view->login = $this->_sessCommon->login;
			// 确保操作是指定的班级
			$this->view->class_id = $this->getRequest()->getParam('c',false);
			// 非成员跳转到自己的班级
			if(false == $this->view->class_id || $this->_sessClass->data[$this->view->class_id] == null)
			$this->_redirect('/home');
			
			// 获取每个控制器所要做的行动参数
			$this->view->do = $this->getRequest()->getParam('do');
		}
		
		# 班级通讯录 ---------------------------------------------
		function addressbookAction()
		{
			
		}
		
		# 班级话题讨论 ---------------------------------------------
		function topicAction()
		{
			switch ($this->view->do)
			{
				case 'view' :
					$this->topicView();
					break;
				default :
					$this->topicNew(); // 默认是新话题
					break;
			}
		}
		
		function topicNew()
		{
			$this->view->title = '发布新话题';
			$this->render('index');
			$this->render('topic-new');
		}
		
		function topicView()
		{
			
		}
		
		# 班级活动讨论 ---------------------------------------------
		function activityAction()
		{
			
		}
	}