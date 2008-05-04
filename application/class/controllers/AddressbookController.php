<?php

	class AddressbookController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			$this->_sessClass = Zend_Registry::get('sessClass');
			$this->view->class_id = $this->getRequest()->getParam('c');
			$this->view->class_base_info = DbModel::getClassInfo($this->view->class_id);
			$this->view->login = $this->_sessCommon->login;
		}
		
		# 通讯录页面
		function indexAction()
		{
			$this->view->headTitle('班级通讯录');
		}
	}