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
     * 
     * @return null
     */
    public function init()
    {
    	//载入对应MODEL
    	Zend_Loader::loadClass('User');

    	//禁用自动渲染视图
    	$this->_helper->viewRenderer->setNoRender();

    	return null;
    }

    /**
     * 注册提交
     * 
     * 点击提交按钮后数据验证及写库操作
     * 
     * @return string to ajax
     */
	public function registerAction()
    {
    	if ($this->_request->isXmlHttpRequest())
    	{    		
    		//print_r($this->_getAllParams());
    		//echo $this->_getParam('username');
    		$username = $this->_getParam('username');
    		
    		if (!Valid::isAlNumUline($username) || !Valid::lenBetwAlNum($username,3,6))
    		{
    			echo '用户名格式不正确。';
    		}
    	}
    }
}
