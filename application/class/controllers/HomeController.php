<?php

class HomeController extends Zend_Controller_Action
{
	function init()
	{
		// 注册全局SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessClass = Zend_Registry::get('sessClass');
		$this->view->login = $this->_sessCommon->login;
		// 需要重新写入session
		if($this->_sessClass->data == null)
		{
			$rows = DbModel::hasClass($this->view->login['uid']);
			if(false != $rows)
			$this->_sessClass->data = $rows;
		}
	}
	
	function indexAction()
	{
		$request = $this->getRequest();
		// 载入班级基础信息
		$this->view->class = $this->_sessClass->data;
		// 选择页面显示的班级
		$this->view->class_id = $request->getParam('c',$this->_sessClass->data[0]['class_id']);
		
	}
}
