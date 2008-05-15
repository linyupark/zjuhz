<?php

/**
 * 我的班级,班级主页
 *
 */
class HomeController extends Zend_Controller_Action
{
	function init()
	{
		$this->_sessClass = Zend_Registry::get('sessClass');
		$this->view->class_base_info = $this->getRequest()->getParam('class');
	}
	
	# 班级会员进入班级主页后所要做的一些处理
	private function enterHandler()
	{
		$passport = $this->view->passport();
		$uid = $passport['uid'];
		$class_id = $this->view->class_base_info['class_id'];

		// 没有初始化过班级通讯录判断的
		if($this->_sessClass->addressInit == null)
		{
			if(false == AddressModel::isInit($class_id, $uid))
			{
				// 尝试更新同班id 同名的记录,将uid从0变成实际uid
				$data = array('uid' => $uid);
				$where = array('`class_id` = '.$class_id, "`cname` = '{$passport['realName']}'");
				$affect_row = AddressModel::update($data, $where);
				if($affect_row != 1) // 该班管理员没有导入过，则初始化
				{
					$data = array(
						'uid' => $uid, 
						'class_id'=> $class_id, 
						'cname' => $passport['realName'],
					);
					$db = Zend_Registry::get('dbClass');
					$db->insert('tbl_class_addressbook', $data);
				}
			}
			$this->_sessClass->addressInit = 'Y';
		}
	}
	
	# 公共分配视图
	private function commonView()
	{
		$class_id = $this->view->class_base_info['class_id'];
		$rows = TopicModel::fetchList($class_id, $this->getRequest()->getActionName(), null, 5, 1);
		$this->view->topics = $rows['rows'];
	}
	
	# 游客身份访问班级信息
	function visitorAction()
	{
		$this->commonView();
		$this->render('index');
		$this->render('visitor');
		$this->render('baseinfo');
	}
	
	# 成员身份访问班级信息
	function memberAction()
	{
		$this->enterHandler();
		$this->commonView();
		$this->render('index');
		$this->render('member');
		$this->render('baseinfo');
	}
	
	# 管理员身份访问班级信息
	function managerAction()
	{
		$this->enterHandler();
		$this->commonView();
		$this->render('index');
		$this->render('manager');
		$this->render('baseinfo');
	}
	
	function indexAction()
	{	
		$uid = $this->view->passport('uid');
		// 确认显示的班级ID
		$class_id = $this->getRequest()->getParam('c');
		if(null == $class_id) 
		{
			// 没有加入班级，也没有建立过班级，返回班级列表页
			if(count($this->_sessClass->data) == 0)
			{
				$this->_redirect('/');
			}
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
			if(false == MemberModel::isJoined($class_id, $uid))
			{
				if($this->_sessClass->data[$class_id] != null) // 刚刚踢出的
				$this->_sessClass->data = null;
				$this->_forward('visitor',null,null,array('class'=>$class));
			}
			else // 是成员
			{				
				if($this->_sessClass->data[$class_id] == null) // 刚刚加入的,没初始化新的班级数据
				$this->_sessClass->data = null;
				// 更新最后访问时间
				MemberModel::lastAccess($uid, $class_id);
				
				// 不是管理员
				if($this->_sessClass->data[$class_id]['class_charge'] != $uid && 
				   $this->_sessClass->data[$class_id]['class_member_charge'] != 1)
					$this->_forward('member',null,null,array('class'=>$class));
				
				else $this->_forward('manager',null,null,array('class'=>$class));
			}
		}	
	}
}
