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
				    ->addRole(new Zend_Acl_Role('admin'));
				// 权限设置
				$acl->deny('guest', null)
					->allow(array('member','admin'));
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
			
			// 自动转换成资源
		   if(false == $this->_acl->has($resource))
		   {
		    	$this->_acl->add(new Zend_Acl_Resource($resource));
		   }
		   
		   // 权限再定义
			
			// 无权限的请求,重新分配CA
			if (!$this->_acl->isAllowed($sessRole, $resource, $action))
			{
				$request->setControllerName('error');
				$request->setActionName('relogin');
			}
			
			// 一些分配前的动作 ----------------------------------------------------
			else 
			{
				$sessClass = Zend_Registry::get('sessClass');
				$uid = $this->_sessCommon->login['uid'];
				
				// 每个页面都要进行检查的session处理[班级信息]
				if($sessClass->data == null)
				{
					$classes = DbModel::hasClass($uid);
					// 该用户有班级,则载入班级信息
					if(false != $classes)
					{
						foreach ($classes as $v)
						{
							$sessClass->data[$v['class_id']]['class_name'] = $v['class_name'];
							$sessClass->data[$v['class_id']]['class_college'] = $v['class_college'];
							$sessClass->data[$v['class_id']]['class_year'] = $v['class_year'];
							$sessClass->data[$v['class_id']]['class_charge'] = $v['class_charge'];
							$sessClass->data[$v['class_id']]['class_member_charge'] = $v['class_member_charge'];
						}
						Cmd::cacheClass($uid);
					}
				}
				
				// 用户信息初始化
				if($sessClass->userInit == null)
				{
					
					// 初始化过的用户
					if(DbModel::isUserInit($uid))
					{
						$sessClass->userInit = 'Y';
						//可加入一些session写入操作.
					}
					else 
					{
						$data = array(
							'uid' => $uid,
							'realName' => $this->_sessCommon->login['realName'],
							'sex' => $this->_sessCommon->login['sex']
						);
						DbModel::userInit($data);
						$sessClass->userInit = 'Y';
					}
				}
			}
		}
	}