<?php
	
	/**
	 * INFO模块的权限管理类,并根据不同情况在分配前进行ACTION调配
	 *
	 */
	class InfoAcl extends Zend_Controller_Plugin_Abstract
	{
		private $_sessInfo;
		private $_acl;
		
		/**
		 * 给予必要参数实例化INFO模块的权限管理类
		 *
		 * @param object $auth 给予SESSION注册对象
		 * @param object $iniAcl 给予INI相应区域
		 */
		function __construct($_sessInfo)
		{
			$this->_sessInfo = $_sessInfo;
			// 没有设置 acl ?
			if(!Zend_Registry::isRegistered('acl'))
			{
				$acl = new Zend_Acl();
				// 增加所有控制角色,决定继承关系
				$acl->addRole(new Zend_Acl_Role('guest'))
				    ->addRole(new Zend_Acl_Role('staff', 'guest'))
				    ->addRole(new Zend_Acl_Role('admin'));
				// 增加所要控制的资源(Controller)
				$acl->add(new Zend_Acl_Resource('news'))
				    ->add(new Zend_Acl_Resource('support'))
				    ->add(new Zend_Acl_Resource('login'))
				    ->add(new Zend_Acl_Resource('logout'))
				    ->add(new Zend_Acl_Resource('admin'));
				// 权限设置
				$acl->allow('guest', array('news','login','support'))
				    ->allow('staff', 'admin')
				    ->deny('staff', 'admin', array('category','cache','delete','public','manager'))
				    ->allow('admin');
			}
			else $this->_acl = Zend_Registry::isRegistered('acl');
		}
		
		/**
		 * 判断当前角色session是否有所请求地址的权限,根据条件进行处理
		 *
		 * @param object $request
		 */
		public function preDispatch($request)
		{
			// 根据SESSION确定当前的角色
			if(!$sessRole = $this->_sessInfo->role)
				$sessRole = 'guest';
				
			// 默认分配的CA
			$controller = $request->controller;
			$action = $request->action;
			$resource = $controller;
			
			// 无权限的请求,重新分配CA
			if (!$this->_acl->isAllowed($sessRole, $resource, $action))
			{
				$request->setControllerName('index');
				$request->setActionName('index');
			}
		}
	}