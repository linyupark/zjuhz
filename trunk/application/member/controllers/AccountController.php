<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : AccountController.php $
 * $Author : wangyumin $
 */


/**
 * 会员中心-帐号处理
 * 面向前后端:帐号创建 资料修改 修改修改等
 * 纯数据处理，不做页面层显示
 */
class AccountController extends Zend_Controller_Action
{
    /**
     * 初始化
     */
    public function init()
    {
    	Zend_Loader::loadClass('UserModel', '../../application/member/models/');
    }

    /**
     * 注册提交
     */
	public function registerAction()
    {
    	//禁用自动渲染视图
    	$this->_helper->viewRenderer->setNoRender();


    	$user = new UserModel();
    	$user->register($this->_getAllParams());
    	
    }
}
