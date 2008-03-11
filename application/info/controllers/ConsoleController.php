<?php

	class ConsoleController extends Zend_Controller_Action 
	{
		protected  $sess;
		protected  $sess_info;
		protected  $acl_info;
		
		function init()
		{
			$this->sess = Zend_Registry::get('sess');
			$this->sess_info = Zend_Registry::get('sess_info');
			$this->acl_info = Zend_Registry::get('acl_info');
		}
		
		#后台登陆入口 --------------------------------
		function loginAction()
		{
			//已经登陆过的直接跳转
			if(isset($this->sess_info->role))
				$this->_redirect('/console/');
				
			if($this->getRequest()->isPost())
			{
				//收集表单信息
				$username = trim($this->getRequest()->getPost('username'));
				$password = trim($this->getRequest()->getPost('password'));
				$verify = trim($this->getRequest()->getPost('verify'));

				//错误提示初始化
				$this->view->error_tips = null;
				
				//各项校验
				if(!Zend_Validate::is($username,'StringLength',array(3,12)))
				$this->view->error_tips = '* 帐号长度必须在3-12位之间,不能包含中文以及下划线<br />';
				
				if(!Zend_Validate::is($password,'NotEmpty'))
				$this->view->error_tips .= '* 密码不能为空<br />';
				
				if(!Zend_Validate::is($verify,'StringLength',array(4)) || 
					md5($verify) != $this->sess->verify)
				$this->view->error_tips .= '* 请正确输入验证码';
				
				//数据库匹配
				if($this->view->error_tips == null)
				{
					$User = new UserModel(array('db'=>'db_info'));
					$row = $User->fetchRow(array('user_name=?' => $username,
												'user_password=?'=>md5($password)));
					if($row == null)
					{
						$this->view->error_tips = '* 帐号密码错误';
					}
					else 
					{
						$this->sess_info->role = $row->user_role; //角色输入session
						$this->sess_info->user = $row->user_name; //用户名输入session
						$this->sess_info->power = $row->user_power; //权利输入
						Zend_Session::rememberMe(3600*24);
						$this->view->error_tips = '* 登陆成功~2秒后自动转向控制页';
						echo Commons::lp_jump('/info/console/',2);
					}
				}
			}
			$this->view->header_title = '登陆';
			$this->render('header', null, true);
			$this->render('login');
			$this->render('footer', null, true);
		}
		
		#登出 ----------------------------------------
		function logoutAction()
		{
			$this->_helper->ViewRenderer->setNoRender();
			Zend_Session::destroy();
			$this->_redirect('/console/login/');
		}
		
		#默认显示页,登陆成功或转向登陆入口 -------------------
		function indexAction()
		{
			if(!$this->acl_info->isAllowed($this->sess_info->role, null, 'login'))
			{
				Zend_Session::destroy();
				$this->_redirect('/console/login/');
			}
			$this->view->header_title = '登陆成功!';
			$this->render('header', null, true);
			$this->render('bar', null, true);
			$this->render('index');
			$this->render('footer', null, true);
		}
		
		#验证图片
		function verifyAction()
		{
			$this->_helper->ViewRenderer->setNoRender();
			ImageHandle::verify('common');
		}
	}