<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : ErrorController.php $
 * $Author : wangyumin $
 */


/**
 * 会员中心-异常处理
 */
class ErrorController extends Zend_Controller_Action
{
    /**
     * 异常输出
     * 
     * @return null
     */	
    public function errorAction()
    {
    	print_r($this->_getAllParams());
    	
    	return null;
    }
}
