<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:LogoutController.php
 */


/**
 * 校友中心-退出登录
 */
class LogoutController extends Zend_Controller_Action
{
    /**
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
    }

    /**
     * 执行退出
     * 
     * @return void
     */
	public function indexAction()
    {
    	Zend_Session::destroy(true);

    	$this->_redirect('../', array('exit'));
    }
}
