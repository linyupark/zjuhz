<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AlumniController.php
 */


/**
 * 校友中心-校友主控
 */
class AlumniController extends Zend_Controller_Action
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
     * 校友中心-迷你名片
     * 
     * @return void
     */
	public function cardAction()
    {
    	$this->_helper->viewRenderer->setRender();

    	CacheLogic::setOptions('cache_dir', Commons::getUserCache(
    	    (int)$this->getRequest()->getParam('uid')));

    	$this->view->card = CacheLogic::init()->cardLoad();
    }
}
