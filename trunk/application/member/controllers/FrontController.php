<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : FrontController.php $
 * $Author : wangyumin $
 */


/**
 * 会员中心-所有前端
 * 除前端首页、注册、登录
 */
class FrontController extends Zend_Controller_Action
{
    /**
     * 初始化
     * 
     * @return null
     */
    public function init()
    {
    	//return null;
    }

    /**
     * member验证码
     * 
     * @return null
     */
    public function verifyAction()
    {
		//禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();

		//将验证码写入公共SESSION
		ImageHandle::verify('common');
    }
}
