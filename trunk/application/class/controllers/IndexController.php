<?php

class IndexController extends Zend_Controller_Action
{
	function init()
	{
		// 注册全局SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessClass = Zend_Registry::get('sessClass');
		$this->view->login = Zend_Registry::get('sessCommon')->login;
	}
	
	function indexAction()
	{
		if($this->_sessClass->data == null)
		{
			$rows = DbModel::hasClass($this->view->login['uid']);
			// 没有班级的会员
			if(!$rows)
				$this->render('index');
			else // 重新加载一遍会员的班级信息->sessClass
			{
				$this->_sessClass->data = $rows;
				$this->_redirect('/home/');
			}
		}
		else // 跳转到HOME
		{
			$this->_redirect('/home/');
		}
	}
}
