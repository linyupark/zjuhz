<?php

	class ManageController extends Zend_Controller_Action 
	{
		function init()
		{
			// 注册全局SESSION
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			$this->_sessClass = Zend_Registry::get('sessClass');
			
			$this->view->login = $this->_sessCommon->login;
			$this->view->class_id = $this->getRequest()->getParam('c');
			$this->view->class_base_info = DbModel::getClassInfo($this->view->class_id);
			
			// 不是管理员就跳转
			if($this->_sessClass->data[$this->view->class_id]['class_charge'] != $this->view->login['uid'] && 
			   $this->_sessClass->data[$this->view->class_id]['class_member_charge'] != $this->view->login['uid'])
			$this->_redirect('/class/home?c='.$this->view->class_id);
		}
		
		# 成员管理 ----------------------------------------------------------
		function memberAction()
		{
			$this->view->headTitle('成员管理');
		}
	}