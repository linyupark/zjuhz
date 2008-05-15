<?php

	class ManageController extends Zend_Controller_Action 
	{
		function init()
		{
			// 注册全局SESSION
			$this->_sessClass = Zend_Registry::get('sessClass');
			
			$this->view->class_id = $this->getRequest()->getParam('c');
			$this->view->class_base_info = DbModel::getClassInfo($this->view->class_id);
			
			// 不是管理员就跳转
			if(false == Cmd::isManager($this->view->class_id))
			$this->_redirect('/home?c='.$this->view->class_id);
		}

		# 成员管理 ----------------------------------------------------------
		function memberAction()
		{
			$this->view->headTitle('成员管理');
			// 默认显示条目
			if($this->_sessClass->default['managerMember'] == null)
			$this->_sessClass->default['managerMember'] = "applyList({$this->view->class_id})";
		}
	}