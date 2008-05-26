<?php
	
	/**
	 * INFO模块的权限管理类,并根据不同情况在分配前进行ACTION调配
	 *
	 */
	class InfoAcl extends Zend_Controller_Plugin_Abstract
	{
		private $_sessCommon;
		private $_acl;
		
		/**
		 * 给予必要参数实例化INFO模块的权限管理类
		 *
		 * @param object $auth 给予SESSION注册对象
		 * @param object $iniAcl 给予INI相应区域
		 */
		function __construct($_sessCommon)
		{
			$this->_sessCommon = $_sessCommon;
			// 没有设置 acl ?
			if(!Zend_Registry::isRegistered('acl'))
			{
				$acl = new Zend_Acl();
				// 增加所有控制角色,决定继承关系
				$acl->addRole(new Zend_Acl_Role('guest'))
				    ->addRole(new Zend_Acl_Role('member', 'guest'))
				    ->addRole(new Zend_Acl_Role('admin'));
				// 增加所要控制的资源(Controller)
				$acl->add(new Zend_Acl_Resource('view'))
				    ->add(new Zend_Acl_Resource('index'))
				    ->add(new Zend_Acl_Resource('support'))
				    ->add(new Zend_Acl_Resource('subject'))
				    ->add(new Zend_Acl_Resource('login'))
				    ->add(new Zend_Acl_Resource('logout'))
				    ->add(new Zend_Acl_Resource('search'))
				    ->add(new Zend_Acl_Resource('admin'));
				// 权限设置
				$acl->deny(array('guest','member'))
					->allow(array('guest','member'), array('view','login','logout','support','index','search','subject'))
				    ->allow('member', 'admin', array('entity_add', 'entity_mod','index'))
				    ->allow('admin');
				// 寄存
				Zend_Registry::set('acl', $acl);
				$this->_acl = $acl;
			}
			else $this->_acl = Zend_Registry::get('acl');
		}
		
		/**
		 * 判断当前角色session是否有所请求地址的权限,根据条件进行处理
		 *
		 * @param object $request
		 */
		public function preDispatch($request)
		{
			$sessRole = $this->_sessCommon->role;
			
			if(NULL == $sessRole)
			{
				$sessRole = 'guest';
				$this->_sessCommon->role = $sessRole;
			}
				
			// 默认分配的CA
			$controller = $request->controller;
			$action = $request->action;
			$resource = $controller;
			
			// 无权限的请求,重新分配CA
			if (!$this->_acl->isAllowed($sessRole, $resource, $action))
			{
				$request->setControllerName('error');
				$request->setActionName('relogin');
			}
			else // 自动将会员信息载入info数据库
			{
				$sessInfo = Zend_Registry::get('sessInfo');
				$inited = $sessInfo->inited;
				// 是会员的话进行INFO数据库初始化工作
				if($sessRole == 'member' && $inited != TRUE) 
				{
					$data = $this->_sessCommon->login;
					$User = new UserModel();
					$inited = $User->fetchRow("user_name = '{$data['username']}'");
					if(FALSE == $inited) // 没有初始化过则
					{
						$row = array(
							'user_id' => $data['uid'],
							'user_name' => $data['username'],
							'user_password' => $data['password'],
							'user_role' => 'member'
						);
						$info_user_id = $User->insert($row);
						$sessInfo->user_id = $info_user_id;
					}
					$sessInfo->user_id = $data['uid'];
					$sessInfo->inited = TRUE;
				}
			}
		}
	}