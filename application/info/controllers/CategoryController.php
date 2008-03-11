<?php

	class CategoryController extends Zend_Controller_Action 
	{
		protected  $sess_info;
		protected  $acl_info;
		protected  $tbl_category;
		
		function init()
		{
			$this->sess_info = Zend_Registry::get('sess_info');
			$this->acl_info = Zend_Registry::get('acl_info');
			$this->view->header_title = '信息分类管理!';
			if(!$this->acl_info->isAllowed($this->sess_info->role, null, 'category'))
			{
				Zend_Session::destroy();
				$this->_redirect('/console/login/');
			}
			$this->tbl_category= new CategoryModel(array('db'=>'db_info'));
		}
		
		#归类管理主页
		function indexAction()
		{
			$this->view->categories = $this->tbl_category->fetchAll();
			
			$this->render('header', null, true);
			$this->render('bar', null, true);
			$this->render('index');
			$this->render('footer', null, true);
		}
		
		#增加新类别
		function addAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$category_name = trim($this->getRequest()->getPost('category_name'));
			
			if(!Zend_Validate::is($category_name, 'NotEmpty'))
			{
				echo '类别名称不能为空！';
				exit();
			}
			if($this->tbl_category->fetchRow(array('category_name = ?'=>$category_name)))
			{
				echo '该分类已经存在！';
				exit();
			}
			if($this->tbl_category->insert(array('category_name'=>$category_name)))
			echo '创建成功';
			echo Commons::lp_jump('/info/category/',2);
		}
		
		#修改类别
		function modAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$category_name = trim($this->getRequest()->getPost('category_name'));
			$category_id = trim($this->getRequest()->getPost('category_id'));
			
			if(!Zend_Validate::is($category_name, 'NotEmpty'))
			{
				echo '类别名称不能为空';
				exit();
			}
			
			$where = $this->tbl_category->getAdapter()->quoteInto('category_id = ?',$category_id);
			if(!$this->tbl_category->update(array('category_name'=>$category_name),$where))
			echo '没有做任何修改';
			else echo '修改成功';
		}
		
		#删除类别
		function delAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$category_id = trim($this->getRequest()->getPost('category_id'));
			
			$where = $this->tbl_category->getAdapter()->quoteInto('category_id = ?',$category_id);
			if($this->tbl_category->delete($where))
			echo '该类别已成功删除';
		}
	}