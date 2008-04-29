<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ErrorController.php
 */


/**
 * 会员中心-错误处理
 */
class ErrorController extends Zend_Controller_Action
{
	/**
     * 项目模块配置对象
     *
     * @var object
     */
	private $_iniMember = null;

    /**
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
    	$this->_iniMember  = Zend_Registry::get('iniMember'); // 载入项目配置
    }

    /**
     * 错误信息输出
     * 
     * @return void
     */
    public function errorAction()
    {
    	//$this->_forward('index', 'Index');
        print_r($this->getRequest()->getParams());exit;
    }

    /**
     * 模块登录页
     * 
     * @return void
     */
    public function loginAction()
    {
    	$this->view->headTitle($this->_iniMember->head->title->login); // 载入标题
    	$this->view->uname = $_COOKIE['zjuhz_member']['alive']; // 记住账号
    }
}
