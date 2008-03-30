<?php

	class RoleController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->_helper->layout->setLayout('info-console');
			$this->view->headTitle('角色管理模块');
			Acl::roleCheck('role');
		}
		
		#角色控制面版 ------------------------------------------
		function indexAction()
		{
			$User = new UserModel(array('db'=>'db_info'));
			$this->view->users = $User->fetchAll();
			
			$db = Zend_Registry::get('db_info');
			$select = $db->select()->from('tbl_user',array('user_role'))
						 ->group('user_role');
			
			$this->view->roles = $db->fetchAll($select);
			
			$Categories = new CategoryModel(array('db'=>'db_info'));
			$this->view->categories = $Categories->fetchAll();
			
			$this->render('bar', null, true);
			$this->render('index');
		}
		
		#增加新角色 --------------------------------------------
		function addAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			if(!$this->getRequest()->isPost())
			exit();
			//获取数值
			$user_name = trim($this->getRequest()->getPost('user_name'));
			$user_power = @implode('|',$this->getRequest()->getPost('user_power'));
			$user_role = $this->getRequest()->getPost('user_role');
			$password = trim($this->getRequest()->getPost('password'));
			$password2 = trim($this->getRequest()->getPost('password2'));
			
			$tips = '';
			if(!Valid::chkUsername($user_name)) 
				$tips .= '* 登录帐号必须为3-16位字母数字下划线<br />';
			if(!Valid::chkPasswd($password) || $password != $password2)
				$tips .= '* 登录密码必须为6-16位不含空格,并确认密码一致性';
			if($tips != '')
			{
				echo $tips;
			}
			else 
			{
				$User = new UserModel(array('db'=>'db_info'));
				$User->insert(array(
				'user_name' => $user_name,
				'user_password' => md5($password),
				'user_role' => $user_role,
				'user_power' => $user_power
				));
				echo '成功创建新成员帐户';
				echo Commons::js_jump('/info/role/',1);
			}
		}
		
		#修改角色权限 ------------------------------------------
		function modAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			if(!$this->getRequest()->isPost())
			exit();
			//获取数值
			$user_name = trim($this->getRequest()->getPost('user_name'));
			$user_power = @implode('|',$this->getRequest()->getPost('user_power'));
			$user_role = $this->getRequest()->getPost('user_role');
			$password = trim($this->getRequest()->getPost('password'));
			$password2 = trim($this->getRequest()->getPost('password2'));
			$user_id = (int)$this->getRequest()->getPost('user_id');
			
			if(!Valid::chkUsername($user_name))
			{
				echo '* 登录帐号格式为3-16位字母数字或下划线';
				exit();
			}
			
			$User = new UserModel(array('db'=>'db_info'));
			$row = $User->fetchRow($User->getAdapter()->quoteInto('user_id = ?',$user_id));
			if($password != null && $password == $password2)
			$row->user_password = md5($password);
			
			$row->user_name = $user_name;
			$row->user_role = $user_role;
			$row->user_power = $user_power;
			$row->save();
			echo "更新成功！";
		}
		
		#删除角色 -------------------------------------------
		function delAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			if(!$this->getRequest()->isPost())
			exit();
			$user_id = (int)$this->getRequest()->getPost('user_id');
			$User = new UserModel(array('db'=>'db_info'));
			$User->delete($User->getAdapter()->quoteInto('user_id = ?',$user_id));
			echo "该成员帐号删除成功！";
		}
	}
	