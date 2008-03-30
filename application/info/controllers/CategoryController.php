<?php

	class CategoryController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->sess_info = Zend_Registry::get('sess_info');
			$this->acl_info = Zend_Registry::get('acl_info');
			$this->_helper->layout->setLayout('info-console');
			$this->view->headTitle('信息分类管理模块');
			Acl::roleCheck('category');
			$this->Category= new CategoryModel(array('db'=>'db_info'));
		}
		
		#归类管理主页
		function indexAction()
		{
			$this->view->categories = $this->Category->fetchAll();
			$this->render('bar', null, true);
			$this->render('index');
		}
		
		#增加新类别
		function addAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$category_name = trim($this->getRequest()->getPost('category_name'));
			$category_icon = trim($this->getRequest()->getPost('category_icon'));
			if(!Zend_Validate::is($category_icon, 'NotEmpty'))
			{
				echo '选择使用的icon名称不能为空';
				exit();
			}
			if(!Zend_Validate::is($category_name, 'NotEmpty'))
			{
				echo '类别名称不能为空！';
				exit();
			}
			if($this->Category->fetchRow(array('category_name = ?'=>$category_name)))
			{
				echo '该分类已经存在！';
				exit();
			}
			if($this->category->insert(array(
			'category_name'=>$category_name,
			'category_icon'=>$category_icon
			)))
			echo '创建成功';
			echo Commons::js_jump('/info/category/',1);
		}
		
		#修改类别
		function modAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$category_icon = trim($this->getRequest()->getPost('category_icon'));
			$category_name = trim($this->getRequest()->getPost('category_name'));
			$category_id = trim($this->getRequest()->getPost('category_id'));
			
			if(!Zend_Validate::is($category_name, 'NotEmpty'))
			{
				echo '类别名称不能为空';
				exit();
			}
			
			if(!Zend_Validate::is($category_icon, 'NotEmpty'))
			{
				echo '选择使用的icon名称不能为空';
				exit();
			}
			
			$where = $this->Category->getAdapter()->quoteInto('category_id = ?',$category_id);
			if(!$this->Category->update(array(
				'category_name'=>$category_name,
				'category_icon'=>$category_icon
				),$where))
			echo '没有做任何修改';
			else echo '修改成功';
		}
		
		#删除类别
		function delAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$category_id = (int)$this->getRequest()->getPost('category_id');
			
			$where = $this->Category->getAdapter()->quoteInto('category_id = ?',$category_id);
			if($this->Category->delete($where))
			echo '该类别已成功删除';
		}
	}