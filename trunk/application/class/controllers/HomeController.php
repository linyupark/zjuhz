<?php

class HomeController extends Zend_Controller_Action
{
	function init()
	{
		// 注册全局SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessClass = Zend_Registry::get('sessClass');
		$this->view->login = $this->_sessCommon->login;
		$this->view->class_id = $this->getRequest()->getParam('c');
		$this->view->class_base_info = $this->getRequest()->getParam('class');
	}
	
	# 游客身份访问班级信息
	function visitorAction()
	{

	}
	
	# 成员身份访问班级信息
	function memberAction()
	{

	}
	
	# 管理员身份访问班级信息
	function managerAction()
	{
		
	}
	
	function indexAction()
	{	
		$class_id = $this->view->class_id;
		// 没有相关的班级信息
		if(!$class = DbModel::getClassInfo($class_id))
		{
			$this->_forward('error','error','class',array('message'=>'该班级并不存在！'));
		}
		
		else
		{
			echo $this->view->headTitle($class['class_name']);
			// 不是班级成员
			if($this->_sessClass->data[$this->view->class_id] == null)
			{
				$this->_forward('visitor',null,null,array('class'=>$class));
			}
			else // 是成员
			{
				$uid = $this->view->login['uid'];
				// 不是管理员
				if($this->_sessClass->data[$class_id]['class_charge'] != $uid && 
				   $this->_sessClass->data[$class_id]['class_member_charge'] != $uid)
					$this->_forward('member',null,null,array('class'=>$class));
				
				else $this->_forward('manager',null,null,array('class'=>$class));
			}
		}	
	}
}
