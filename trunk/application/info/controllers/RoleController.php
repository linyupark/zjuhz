<?php

	class RoleController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->header_title = '角色管理!';
			if(!Zend_Registry::get('acl_info')
				->isAllowed(Zend_Registry::get('sess_info')->role, null, 'role'))
			{
				Zend_Session::destroy();
				$this->_redirect('/console/login/');
			}
			
			$this->tbl_user = new UserModel(array('db'=>'db_info'));
		}
		
		#角色控制面版 ------------------------------------------
		function indexAction()
		{
			$this->view->users = $this->tbl_user->fetchAll();
			
			$db = Zend_Registry::get('db_info');
			$select = $db->select()->from('tbl_user',array('user_role'))
						 ->group('user_role');
			
			$this->view->roles = $db->fetchAll($select);
			
			$Categories = new CategoryModel(array('db'=>'db_info'));
			$this->view->categories = $Categories->fetchAll();
			
			$this->render('header', null, true);
			$this->render('bar', null, true);
			$this->render('index');
			$this->render('footer', null, true);
		}
		
		#增加新角色 --------------------------------------------
		function addAction()
		{
			
		}
		
		#修改角色权限 ------------------------------------------
		function modAction()
		{
			
		}
		
		#删除角色 -------------------------------------------
		function delAction()
		{
			
		}
	}
	