<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : RegisterController.php $
 * $Author : wangyumin $
 */


/**
 * 会员中心-会员注册
 */
class RegisterController extends Zend_Controller_Action
{
    /**
     * 初始化
     * 
     * @return null
     */
    public function init()
    {
    	return null;
    }

    /**
     * 显示注册页面
     * 
     * @return null
     */
	public function indexAction()
    {
    	$iuid = $this->_getParam('iuid');
    	$ikey = $this->_getParam('ikey');
    	
    	$this->view->iuid = $iuid;
    	$this->view->ikey = $ikey;
    	
    	return null;
    }
}
