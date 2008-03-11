<?php

	class PostController extends Zend_Controller_Action 
	{
		protected  $sess_info;
		protected  $acl_info;
		
		function init()
		{
			$this->sess_info = Zend_Registry::get('sess_info');
			$this->acl_info = Zend_Registry::get('acl_info');
			$this->view->header_title = '信息管理!';
			if(!$this->acl_info->isAllowed($this->sess_info->role, null, 'login'))
			{
				Zend_Session::destroy();
				$this->_redirect('/console/login/');
			}
		}
		
		#讯息主版 -----------------------------------------
		function indexAction()
		{
			$this->render('header', null, true);
			$this->render('bar', null, true);
			$this->render('index');
			$this->render('footer', null, true);
		}
		
		#添加新的讯息 -------------------------------------
		function addAction()
		{
			if($this->getRequest()->isPost())
			{
				$this->view->content = $this->getRequest()->getPost('content');
			}
			
			$Categories = new CategoryModel(array('db'=>'db_info'));
			
			//获取相应的可添加分类 - admin
			if($this->sess_info->role == 'admin')
			{	
				$row_set = $Categories->fetchAll();
			}
			else //根据acl判断 
			{
				if($this->acl_info->isAllowed($this->sess_info->role, null, 'post'))
				{
					$power_arr = explode('|', $this->sess_info->power); //权力数组
					$row_set = $Categories->find($power_arr);
				}
			}
			
			$this->view->categories = $row_set;
			
			$this->render('header', null, true);
			$this->render('bar', null, true);
			$this->render('add');
			$this->render('footer', null, true);
		}
		
		#修改讯息 ------------------------------------------
		function modAction()
		{
			
		}
		
		#删除讯息 ------------------------------------------
		function delAction()
		{
			
		}
	}