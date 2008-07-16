<?php

class ErrorController extends Zend_Controller_Action
{
	
	function init()
	{

	}
	
    /**
     * This action handles  
     *    - Application errors
     *    - Errors in the controller chain arising from missing 
     *      controller classes and/or action methods
     */
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found                
                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
                $this->view->title = 'HTTP/1.1 404 Not Found';
                $this->view->message = '请求网页不存在';
                break;
            default:
                // application error; display error page, but don't change                
                // status code
                $this->view->title = 'Application Error';
                 $this->view->message = '程序出现错误~！';
                break;
        }
    }
    
    function reloginAction()
    {
    	$this->view->uname = $_COOKIE['zjuhz_member']['uname']; // 记住账号
        $this->view->pswd = $_COOKIE['zjuhz_member']['pswd']; // 记住账号
    }
}
