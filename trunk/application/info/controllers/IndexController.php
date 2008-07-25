<?php

	class IndexController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->headTitle('信息中心');
			$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen')
								   ->appendStylesheet('/static/styles/info_front.css','screen');

			// 获取全局SESSION 
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			
			// 分配当前角色信息
			$this->view->role = $this->_sessCommon->role;
			
			// 分配角色详细信息
			$this->view->login = $this->_sessCommon->login;
			
		}
		
		# 资讯首页
		function indexAction()
		{
			$this->view->db = new EntityModel();
		}
		
		#验证图片
		function verifyAction()
		{
			$this->_helper->layout->disableLayout();
			$this->_helper->ViewRenderer->setNoRender();
			ImageHandle::verify('common');
		}
	}