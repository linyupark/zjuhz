<?php

/**
 * IndexController
 * 
 * @author
 * @version 
 */


class IndexController extends Zend_Controller_Action {

	public function init()
	{
		$this->view->sessCommon = Zend_Registry::get('sessCommon');
		$this->view->title = "企业留言本";
		$this->view->company_name = "xxxx企业";
		$this->view->company_logo = "/static/users/...";
		$this->view->company_url = "http://xxxx.cccc.mmm";
	}

	public function indexAction()
	{
		
	}
	
	# 企业展示页面
	public function showAction()
	{
		$this->_helper->layout->setLayout('show'); // 更换使用的布局
	}
	
	# 留言本
	public function guestbookAction()
	{
		$this->_helper->layout->setLayout('show');
	}
	
	# 行业分类
	public function tradeAction()
	{
	}
	
	# 搜索结果页
	public function searchAction()
	{
		
	}
	
	# 个人平台管理
	public function myAction()
	{
		$request = $this->getRequest();
		$mtab = $request->getParam('m', 'mycompany');
		$stab = $request->getParam('s', 'index');
		$this->view->mtab = $mtab;
		$this->view->stab = $stab;
		$this->_helper->layout->setLayout('admin');
		$this->getResponse()->insert('nav', $this->view->render('my-nav.phtml'));
		$this->render($mtab.'-'.$stab);
	}
	
	# 管理页
	public function adminAction()
	{
		// 菜单判断部分参考个人平台的 my-nav
		$this->_helper->layout->setLayout('admin');
		$this->getResponse()->insert('nav', $this->view->render('admin-nav.phtml'));
	}
}
