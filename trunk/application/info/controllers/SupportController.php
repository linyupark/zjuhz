<?php 
	
	/**
	 * 提供帮助信息(静态页)
	 *
	 */
	class SupportController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen')
								   ->appendStylesheet('/static/styles/info_front.css','screen');

			// 获取全局SESSION 
			$this->_sessCommon = Zend_Registry::get('sessCommon');
								   
			// 当前所属模块分配
			$this->view->request = $this->getRequest();
			
			// 分配当前角色信息
			$this->view->role = $this->_sessCommon->role;
			
			// 分配角色详细信息
			$this->view->login = $this->_sessCommon->login;
		}
		
		function contactusAction(){}
		
		function aboutAction(){}
		
		function helpAction(){}
		
		function privacyclaimAction(){}
	}