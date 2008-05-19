<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:SearchController.php
 */


/**
 * 校友互助-综合搜索
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
    	(empty($this->_wd) ? $this->_redirect($_SERVER['HTTP_REFERER']) : ''); // 若未输关键字词返回上页
    }

    /**
     * 搜索显示
     * 
     * @return void
     */
    public function indexAction()
    {
    	$logic  = AskQuestionLogic::init();

    	$total  = $logic->selectSearch('count', $this->_wd, '');
    	$paging = new Paging(array('totalRs' => $total, 'perPage' => 10));

    	$this->view->headTitle($this->_wd);
		$this->view->wd     = $this->_wd;
        $this->view->total  = $total;
    	$this->view->paging = $paging->show();
		$this->view->search = $logic->selectSearch('result', $this->_wd, $paging->limit());
    }
}
