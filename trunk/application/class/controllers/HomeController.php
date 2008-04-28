<?php

/**
 * 我的班级,班级主页
 *
 */
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
	
	# 班级会员进入班级主页后所要做的一些处理
	private function enterHandler()
	{
		$uid = $this->view->login['uid'];
		$class_id = $this->view->class_base_info['class_id'];
		$login = $this->view->login;
		// 没有做过班级通讯录判断的
		if($this->_sessClass->addressInit == null)
		{
			if(false == DbModel::isAddressInit($class_id, $uid))
			{
				// 尝试更新同班id 同名的记录,将uid从0变成实际uid
				$data = array('uid' => $uid);
				$where = array('`class_id` = '.$class_id, "`cname` = '{$login['realName']}'");
				$affect_row = DbModel::updateAddress($data, $where);
				if($affect_row != 1) // 该班管理员没有导入过，则初始化
				{
					$data = array(
						'uid' => $uid, 
						'class_id'=> $class_id, 
						'cname' => $login['realName'],
					);
					$db = Zend_Registry::get('dbClass');
					$db->insert('tbl_class_addressbook', $data);
				}
			}
			$this->_sessClass->addressInit = 'Y';
		}
	}
	
	# 一些公共需要分配的参数
	private function commonParam()
	{
		$request = $this->getRequest();
		$action = $request->getActionName();
		$this->view->topic_type = $request->getParam('topic_type');
		// 获取话题列
		$this->view->topics = DbModel::getClassTopic($this->view->class_base_info['class_id'], $action, $this->view->topic_type);
	}
	
	# 游客身份访问班级信息
	function visitorAction()
	{
		$this->render('index');
		$this->render('visitor');
		$this->render('baseinfo');
	}
	
	# 成员身份访问班级信息
	function memberAction()
	{
		$this->enterHandler();
		$this->render('index');
		$this->render('member');
		$this->render('baseinfo');
	}
	
	# 管理员身份访问班级信息
	function managerAction()
	{
		$this->enterHandler();
		$this->commonParam();
		$this->render('index');
		$this->render('manager');
		$this->render('baseinfo');
	}
	
	function indexAction()
	{	
		// 确认显示的班级ID
		$class_id = $this->getRequest()->getParam('c');
		if(null == $class_id) 
		{
			$class_id = array_keys($this->_sessClass->data);
			$class_id = $class_id[0];
		}
		
		// 没有相关的班级信息显示错误信息
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
				   $this->_sessClass->data[$class_id]['class_member_charge'] != 1)
					$this->_forward('member',null,null,array('class'=>$class));
				
				else $this->_forward('manager',null,null,array('class'=>$class));
			}
		}	
	}
}
