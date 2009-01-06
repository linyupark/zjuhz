<?php

	class MemberController extends Zend_Controller_Action 
	{
		function init()
		{
			// 注册全局SESSION
			$this->_sessClass = Zend_Registry::get('sessClass');
			
			$this->view->class_id = $this->getRequest()->getParam('c');
			$this->view->class_base_info = DbModel::getClassInfo($this->view->class_id);
		}

		# 成员管理 ----------------------------------------------------------
		function listAction()
		{
			$this->view->headTitle('成员列表');
			$db = Zend_Registry::get('dbClass');
			$rows = $db->fetchAll('SELECT `class_member_id`,`class_charge`,`class_member_charge`,`realName`,`class_member_last_access` 
									   FROM `vi_class_member` 
									   WHERE `class_id` = ?', $this->view->class_id);
			$this->view->members = $rows;
		}
	}