<?php 

	class LogoutController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->headTitle('后台登录');
			$this->view->headLink()->appendStylesheet('/static/styles/info_console.css','screen');
			
			// 获取全局SESSION 
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			
			// info专署SESSION
			$this->_sessInfo = Zend_Registry::get('sessInfo');
			
			// 分配后台布局
			$this->_helper->layout->setLayout('info-console');	
		}
		
		#后台登录入口 --------------------------------
		function indexAction()
		{
			Zend_Session::destroy();
			$this->_redirect('/');
		}
	}