<?php

class HomeController extends Zend_Controller_Action
{
	function init()
	{
		// 注册全局SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessClass = Zend_Registry::get('sessClass');
		$this->view->login = $this->_sessCommon->login;
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
		$class_id = $this->getRequest()->getParam('c');
		if(null == $class_id) 
		{
			$class_id = array_keys($this->_sessClass->data);
			$class_id = $class_id[0];
		}
		
		// 没有相关的班级信息
		if(!$class = DbModel::getClassInfo($class_id))
		{
			$this->_forward('error','error','class',array('message'=>'该班级并不存在！'));
		}
		
		else
		{
			echo $this->view->headTitle($class['class_name']);
			// 不是班级成员
			if(false == DbModel::isJoined($class_id,$this->view->login['uid']))
			{
				if($this->_sessClass->data[$class_id] != null) // 刚刚踢出的
				$this->_sessClass->data = null;
				$this->_forward('visitor',null,null,array('class'=>$class));
			}
			else // 是成员
			{				
				$uid = $this->view->login['uid'];
				if($this->_sessClass->data[$class_id] == null) // 刚刚加入的,没初始化新的班级数据
				$this->_sessClass->data = null;
				// 更新最后访问时间
				DbModel::updateLastAccessTime($uid, $class_id);
				// 不是管理员
				if($this->_sessClass->data[$class_id]['class_charge'] != $uid && 
				   $this->_sessClass->data[$class_id]['class_member_charge'] != $uid)
					$this->_forward('member',null,null,array('class'=>$class));
				
				else $this->_forward('manager',null,null,array('class'=>$class));
			}
		}	
	}
}
