<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:SortController.php
 */


/**
 * 校友互助-问题分类
 */
class SortController extends Zend_Controller_Action
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
     * 互助模块配置
     * 
     * @var object
     */
	private $_iniHelp = null;

	/**
     * sess内的会员编号
     * 
     * @var integer
     */
	private $_sessUid = 0;

	/**
     * sort分类编号
     * 
     * @var integer
     */
	private $_sortId = 0;

	/**
     * 初始化
     * 
     * @return void
     */
	public function init()
	{
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessHelp   = Zend_Registry::get('sessHelp');
		$this->_iniHelp    = Zend_Registry::get('iniHelp');

		$this->_sessUid = $this->_sessCommon->login['uid'];
		$this->_sortId  = (int)$this->getRequest()->getParam('sid');

		$this->view->sessCommon = $this->_sessCommon;
		$this->view->sessHelp   = $this->_sessHelp;
	}

	/**
     * 分类首页
     * 
     * @return void
     */
	public function indexAction()
	{
	}

	/**
     * 分类输出-JSON方式
     * 
     * @return void
     */
	public function jsonAction()
	{
		echo ($this->getRequest()->isXmlHttpRequest() ? Zend_Json::encode(
		    AskSortLogic::init()->selectParentPairs($this->_sortId)) : '');
	}

	/**
     * 分类查看-问题列表
     * 
     * @return void
     */
	public function browseAction()
	{
		$sortAll    = AskSortLogic::init()->selectAll(); // 获取全部分类
		$sortDetail = HelpClass::getSortDetail($sortAll, $this->_sortId); // 获取分类详情

		$type = $this->getRequest()->getParam('type');
	    switch ($type)
	    {
		    case 'latest': // 最新问题
		    {
		    	$method = 'selectLatestAll';
		    	$total  = (int)($sortDetail['question'] - $sortDetail['solved'] - 
		    	    $sortDetail['closed'] - $sortDetail['overtime']);

    		    break;
		    }
		    case 'offer': // 高分悬赏
		    {
		    	$method = 'selectOfferAll';
		    	$total  = (int)($sortDetail['question'] - $sortDetail['solved'] - 
		    	    $sortDetail['closed'] - $sortDetail['overtime']);

   			    break;
		    }
		    case 'forget': // 被遗忘的
		    {
		    	$method = 'selectForgetAll';
		    	$total  = (int)($sortDetail['question'] - $sortDetail['solved'] - 
		    	    $sortDetail['closed'] - $sortDetail['overtime']);

   			    break;
		    }
		    case 'solved': // 已经解决
		    {
		    	$method = 'selectSolvedAll';
		    	$total  = (int)($sortDetail['solved']);

   			    break;
		    }
		    default: // 全部问题
		    {
   			    $type = 'all';

   			    $method = 'selectQuestionAll';
   			    $total  = (int)($sortDetail['question'] - $sortDetail['closed'] - 
   			        $sortDetail['overtime']);
		    }
		}

		$paging	  = new Paging(array('totalRs' => $total));
        $question = (0 < $total ? AskSortLogic::init()->$method($this->_sortId, $paging->limit()) : '');

        $this->view->headTitle($sortDetail['name']);
        $this->view->headScript()->appendFile('/static/scripts/help/sort/sort.js');
        $this->view->sortMap    = HelpClass::getSortMap($sortAll, $this->_sortId); // 分类地图
        $this->view->sortPath   = HelpClass::getSortPath($sortAll, $this->_sortId); // 分类路径
	    $this->view->sortDetail = $sortDetail; // 分类详情
	    $this->view->type       = $type; // 当前子块
        $this->view->total      = $total; // 总记录数
        $this->view->paging     = $paging->show(); // 显示分页
        $this->view->question   = $question; // 问题列表
	}
}
