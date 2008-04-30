<?php

	class TopicController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			$this->_sessClass = Zend_Registry::get('sessClass');
			$this->view->class_id = (int)$this->_getParam('c'); // 班级id
			$this->view->topic_id = (int)$this->_getParam('tid'); // 话题id
			$this->view->login = $this->_sessCommon->login;
			if(!$this->view->class_id) //没有指定正确的参数
			{
				$this->_redirect('/home');
			}
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
			$topic = DbModel::fetchTopic($this->view->topic_id);
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
					DbModel::topicViewNumInc($this->view->topic_id);
					$this->_sessClass->topic = $this->view->topic_id;
				}
				$this->view->topic = $topic;
				$this->view->title = $topic['class_topic_title'];
				
				// 获取对应的回复内容
				$this->render('index');
				$this->render('view');
			}
			// 
			
			// 面对班级的
			
			// 面对全部校友
		}
	}