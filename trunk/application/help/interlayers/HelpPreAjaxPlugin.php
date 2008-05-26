<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:HelpPreAjaxPlugin.php
 */


/**
 * 校友互助
 * help子系统-布局和页面加载类
 */
class HelpPreAjaxPlugin extends Zend_Controller_Plugin_Abstract
{
    /**
     * 分派动作前根据Ajax响应决定是否加载布局和页面
     * 
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
	public function preDispatch($request)
    {
    	if($request->isXmlHttpRequest())
        {
        	// 禁止嵌入布局

        	// 禁止渲染页面
        	Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        }
        else
        {
        	// 允许嵌入布局
        	Zend_Layout::startMvc(array(
        	  'layoutPath' => '../../application/layouts/', 
        	  'layout' => 'main')
        	);

        	// 允许渲染页面
        }
    }
}
