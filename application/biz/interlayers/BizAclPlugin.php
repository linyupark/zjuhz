<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:BizAclPlugin.php
 */


/**
 * 校友企业
 * biz子系统-资源访问列表定义及控制类
 */
class BizAclPlugin extends Zend_Controller_Plugin_Abstract
{
	/**
     * 公用Session
     * 
     * @var object
     */
	private $_sessCommon = null;

	/**
     * 项目Session
     * 
     * @var object
     */
	private $_sessCompany = null;

    /**
     * Zend_Acl对象
     * @var object
     */
    private $_aclCompany;

    /**
     * 当前访问者角色名称
     * @var string
     */
    private $_roleVisit;

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __construct()
	{
		$this->_sessCommon  = Zend_Registry::get('sessCommon');
		$this->_sessCompany = Zend_Registry::get('sessCompany');
		$this->_aclCompany  = Zend_Registry::get('aclCompany');

		$this->_setRoleVisit($this->_sessCommon->role); // 访客角色		

		$this->_setAclRole(); // 设置角色
		$this->_setAclRes();  // 设置资源
		$this->_setAclPriv(); // 设置权限

		/* preDispatch */
	}

    /**
     * 检查并最终设置当前访问者角色
     * 
     * @param string $sessRole
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
		$this->_aclCompany->addRole(new Zend_Acl_Role('guest'))
		                  ->addRole(new Zend_Acl_Role('member', 'guest')); // 继承于guest
	}

    /**
     * 设置Acl访问资源(ctrl)
     * 
     * @return void
     */
	private function _setAclRes()
	{
		$this->_aclCompany->add(new Zend_Acl_Resource('detail'))
		                  ->add(new Zend_Acl_Resource('error'))
		                  ->add(new Zend_Acl_Resource('external'))
		                  ->add(new Zend_Acl_Resource('message'))
		                  ->add(new Zend_Acl_Resource('index'))
		                  ->add(new Zend_Acl_Resource('manage'))
		                  ->add(new Zend_Acl_Resource('my'));
	}

    /**
     * 设置Acl访问权限
     * 
     * @return void
     */
	private function _setAclPriv()
	{
		$this->_aclCompany->deny('guest')
		                  ->allow('guest', array('detail', 'external', 'index'))
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
		if (!$this->_aclCompany->isAllowed($this->_roleVisit, $request->controller, $request->action))
		{
			// 权限校验未通过则强制修改ctrl和act
			$request->setControllerName('Error');
			$request->setActionName('login'); // 要求访问者先(重)登录
			// 获取本次未能打开的ctrl和act告之登录口以求登录后转回
			// do it
		}
		else
		{
		    // 子系统登录兼初始
		    if (REQUEST_TIME >= $this->_sessCompany->login['refresh'])
		    {
		    	if (0 < USER_UID && !$row = CorpLogic::init()->selectUidRow(USER_UID))
		    	{
		    		$row = array(
		    		    'uid' => USER_UID, 'realName' => $this->_sessCommon->login['realName'], 
		    		    'valid' => 0, 'auditing' => 0, 'untread' => 0
		    		);

		    		CorpLogic::init()->insert($row);
		    	}

		    	$row['refresh'] = (!$row ? 0 : 600) + REQUEST_TIME; // 下次刷新子系统session在600秒后
		    	$this->_sessCompany->login = $row;
		    }
		}
	}
}
