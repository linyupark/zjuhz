<?php

class IndexController extends Zend_Controller_Action
{
	function init()
	{
		// 注册全局SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->view->login = Zend_Registry::get('sessCommon')->login;
	}
	
	function indexAction()
	{
		// 没有班级的会员
		if(false == DbModel::hasClass($this->view->login['uid']))
		{
			$this->render('noclass');
		}
		// 班级主页
		else 
		{
			$this->render('home');
		}
	}
}
