<?php 

	class LoginController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->headTitle('后台登陆');
			$this->view->headLink()->appendStylesheet('/static/styles/info_console.css','screen');
			
			// 获取全局SESSION 
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			
			// info专署SESSION
			$this->_sessInfo = Zend_Registry::get('sessInfo');
			
			// 分配后台布局
			$this->_helper->layout->setLayout('info-console');	
		}
		
		#后台登陆入口 --------------------------------
		function indexAction()
		{
			//强制转换用户身份,重新登陆
			$this->_sessCommon->role = 'guest';
				
			if($this->getRequest()->isPost())
			{
				//收集表单信息
				$username = trim($this->getRequest()->getPost('username'));
				$password = trim($this->getRequest()->getPost('password'));
				$verify = trim($this->getRequest()->getPost('verify'));

				//错误提示初始化
				$this->view->error_tips = null;
				
				//各项校验
				if(!Zend_Validate::is($username,'StringLength',array(3,50)))
				$this->view->error_tips = '* 帐号长度必须在3-12位之间,不能包含中文以及下划线<br />';
				
				if(!Zend_Validate::is($password,'NotEmpty'))
				$this->view->error_tips .= '* 密码不能为空<br />';
				
				if(!Zend_Validate::is($verify,'StringLength',array(4)) || 
					md5($verify) != $this->_sessCommon->verify)
				$this->view->error_tips .= '* 请正确输入验证码';
				
				//数据库匹配
				if($this->view->error_tips == null)
				{
					$User = new UserModel();
					$row = $User->fetchRow(array('user_name=?' => $username,
												'user_password=?'=>md5($password)));
					if($row == null)
					{
						$this->view->error_tips = '* 帐号密码错误';
					}
					else 
					{
						$this->_sessCommon->role = $row->user_role; //角色输入session
						$this->_sessInfo->user_id = $row->user_id; //用户名输入session
						Zend_Session::rememberMe(3600*24);
						$this->view->error_tips = '* 登陆成功~2秒后自动转向<a href="/info/admin/">控制页</a>';
						echo Commons::js_jump('/info/admin/',2);
					}
				}
			}
		}
	}