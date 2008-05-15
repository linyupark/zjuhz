<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ErrorController.php
 */


/**
 * 校友互助-错误处理
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

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:

            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

            default:

                // Log the exception:
                $exception = $errors->exception;
                $log = new Zend_Log(new Zend_Log_Writer_Stream('HelpAppException.log'));
                $log->debug($exception->getMessage()."\n".$exception->getTraceAsString()."\n");
        }

        $this->_redirect('/');
    }

    /**
     * 模块登录页
     * 
     * @return void
     */
    public function loginAction()
    {
    	$this->view->headTitle(Zend_Registry::get('iniHelp')->head->titleLogin);
    	$this->view->uname = $_COOKIE['zjuhz_member']['alive']; // 记住账号
    }
}
