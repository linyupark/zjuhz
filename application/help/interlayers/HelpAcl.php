<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:HelpAcl.php
 */


/**
 * 你问我答
 * help子系统-资源访问列表定义及控制类
 */
class HelpAcl extends Zend_Controller_Plugin_Abstract
{
    /**
     * 当前访问者角色名称
     * @var string
     */
    private $_roleVisit;

    /**
     * Zend_Acl对象
     * @var object
     */
    private $_aclHelp;

    /**
     * 析构方法
     * 
     * @param string $sessRole
     * @return void
     */
	public function __construct($sessRole='guest')
	{
		$this->_setRoleVisit($sessRole); // 访客角色

		$this->_aclHelp = Zend_Registry::get('aclHelp');

		$this->_setAclRole(); // 设置角色
		$this->_setAclRes();  // 设置资源
		$this->_setAclPriv(); // 设置权限

		/* preDispatch */
	}

    /**
     * 检查并最终设置当前访问者角色
     * 
     * @param  string $sessRole
     * @return void
     */
	private function _setRoleVisit($sessRole)
	{
		$this->_roleVisit = ((!empty($sessRole)) ? $sessRole : 'guest');
	}

    /**
     * 设置Acl访问角色
     * 
     * @return void
     */
	private function _setAclRole()
	{
		$this->_aclHelp->addRole(new Zend_Acl_Role('guest'))
		               ->addRole(new Zend_Acl_Role('member', 'guest')); // 继承于guest
	}

    /**
     * 设置Acl访问资源(ctrl)
     * 
     * @return void
     */
	private function _setAclRes()
	{
		$this->_aclHelp->add(new Zend_Acl_Resource('collection'))
		               ->add(new Zend_Acl_Resource('error'))
		               ->add(new Zend_Acl_Resource('index'))
		               ->add(new Zend_Acl_Resource('my'))		               
		               ->add(new Zend_Acl_Resource('question'))
		               ->add(new Zend_Acl_Resource('reply'))		               
		               ->add(new Zend_Acl_Resource('sort'));
	}

    /**
     * 设置Acl访问权限
     *
     * @return void
     */
	private function _setAclPriv()
	{
		$this->_aclHelp->deny('guest')
			           ->allow('guest', 'index')
		               ->allow('member');
	}

    /**
     * 分派动作前对权限进行校验
     * 
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
	public function preDispatch($request)
	{
		// 检查当前ctrl和action下的角色权限
		if (!$this->_aclHelp->isAllowed($this->_roleVisit, $request->controller, $request->action))
		{
			// 权限校验未通过则强制修改ctrl和act
			$request->setControllerName('Index');
			$request->setActionName('login'); // 要求访问者先(重)登录
			// 获取本次未能打开的ctrl和act告之登录口以求登录后转回
			// do it
		}
	}
}
