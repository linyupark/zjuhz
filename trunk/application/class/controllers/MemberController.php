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
			// 确保操作是指定的班级
			$this->view->class_id = $this->getRequest()->getParam('c',false);
			// 非成员跳转到自己的班级
			if(false == $this->view->class_id || $this->_sessClass->data[$this->view->class_id] == null)
			$this->_redirect('/home');
		}
		
		# 班级通讯录 ---------------------------------------------
		function addressbookAction()
		{
			
		}
	}