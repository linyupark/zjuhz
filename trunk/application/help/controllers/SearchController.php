<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:SearchController.php
 */


/**
 * 问题/答案/标签等搜索
 */
class SearchController extends Zend_Controller_Action
{
	/**
     * 用户输入的关键字词 keyword
     *
     * @var string
     */
	private $_wd = null;

    /**
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
    	$this->_wd = $this->getRequest()->getParam('wd');
    }

    /**
     * 搜索
     * 
     * @return void
     */
    public function indexAction()
    {
    }
}
