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
     * 公用Session
     * 
     * @var object
     */
	private $_sessCommon = null;

	/**
     * 项目Session
     * 
     * @var object
     */
	private $_sessHelp = null;

	/**
     * 关键字词 keywords
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
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessHelp   = Zend_Registry::get('sessHelp');

		$this->view->sessCommon = $this->_sessCommon;
		$this->view->sessHelp   = $this->_sessHelp;

    	$this->_wd = SearchFilter::init()->keywords($this->getRequest()->getParam('wd'));
    }

    /**
     * 搜索
     * 
     * @return void
     */
    public function indexAction()
    {
    	$logic = AskQuestionLogic::init();

    	$total  = $logic->selectSearch('count', $this->_wd, '');
    	$paging = new Paging(array('total' => $total, 'perpage' => 10));

		$this->view->wd         = $this->_wd;
        $this->view->total      = $total;
    	$this->view->paging     = $paging->show();
		$this->view->searchList = $logic->selectSearch('result', $this->_wd, $paging->limit());
		$this->view->headTitle($this->_wd);
    }
}
