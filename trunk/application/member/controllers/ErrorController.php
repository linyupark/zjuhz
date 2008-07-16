<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ErrorController.php
 */


/**
 * 校友中心-错误处理
 */
class ErrorController extends Zend_Controller_Action
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
     * 错误输出
     * 
     * @return void
     */
    public function errorAction()
    {
        $errors = $this->getRequest()->getParam('error_handler');

        switch ($errors->type)
        {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:

            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

            default:

                // Log the exception:
                $exception = $errors->exception;
                $log = new Zend_Log(new Zend_Log_Writer_Stream('MemberAppException.log'));
                $log->debug($exception->getMessage()."\n".$exception->getTraceAsString()."\n");
        }

        $this->_redirect('/', array('exit'));
    }

    /**
     * 模块登录页
     * 
     * @return void
     */
    public function loginAction()
    {
    	$this->view->headTitle(Zend_Registry::get('iniMember')->head->titleLogin);
    	$this->view->uname = $_COOKIE['zjuhz_member']['uname']; // 记住账号
        $this->view->pswd = $_COOKIE['zjuhz_member']['pswd']; // 记住账号
    }
}
