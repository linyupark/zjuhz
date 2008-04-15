<?php
	
	/**
	 * CLASS模块的权限管理类,并根据不同情况在分配前进行ACTION调配
	 *
	 */
	class ClassAcl extends Zend_Controller_Plugin_Abstract
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
				    ->addRole(new Zend_Acl_Role('member'))
				    ->addRole(new Zend_Acl_Role('staff'))
				    ->addRole(new Zend_Acl_Role('admin'));
				// 增加所要控制的资源(Controller)
				$acl->add(new Zend_Acl_Resource('index'))
				    ->add(new Zend_Acl_Resource('new'));
				// 权限设置
				$acl->allow('guest', 'index', 'home')
					->allow('member', array('index','new'))
				    ->allow('staff', null)
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
			// 根据SESSION确定当前的角色
			if(!$sessRole = $this->_sessCommon->role)
				$sessRole = 'guest';
				
			// 默认分配的CA
			$controller = $request->controller;
			$action = $request->action;
			$resource = $controller;
			
			// 无权限的请求,重新分配CA
			if (!$this->_acl->isAllowed($sessRole, $resource, $action))
			{
				$request->setControllerName('error');
				$request->setActionName('error');
				$request->setParam('message','deny');
				echo $sessRole.':'.$resource.':'.$action;
			}
		}
	}