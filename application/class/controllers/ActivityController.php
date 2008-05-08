<?php
	
	class ActivityController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->login =Zend_Registry::get('sessCommon')->login;
			$this->_sessClass = Zend_Registry::get('sessClass');
			$this->view->class_id = (int)$this->_getParam('c'); // 班级id
			$this->view->act_id = (int)$this->_getParam('aid'); // 活动id
			if(!$this->view->class_id) //没有指定正确的参数
			{
				$this->_redirect('/home');
			}
		}
		
		# 活动列表
		function listAction()
		{
			
		}
	}

?>