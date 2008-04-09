<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ErrorController.php
 */


/**
 * 错误处理
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
		// 禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();
    }

    /**
     * This action handles  
     *    - Application errors
     *    - Errors in the controller chain arising from missing 
     *      controller classes and/or action methods
     */
    public function errorAction()
    {
    	//$this->_forward('index', 'Index');
        print_r($this->getRequest()->getParams());
    }
}
