<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:SortController.php
 */


/**
 * 你问我答-问题分类
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
     * 问答模块配置
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
     * 问题首页
     * 
     * @return void
     */
	public function indexAction()
	{
	}

	/**
     * 问题分类-JSON
     * 
     * @return void
     */
	public function jsonAction()
	{		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		echo ($this->getRequest()->isXmlHttpRequest() ? 
		    Zend_Json::encode(AskSortLogic::init()->selectParentList($this->_sortId)) : '');
	}

	/**
     * 问题分类-查看列表
     * 
     * @return void
     */
	public function browseAction()
	{
		$sortAll    = AskSortLogic::init()->selectList();
		$sortDetail = HelpClass::getSortDetail($sortAll, $this->_sortId);
		if ($this->_sortId == $sortDetail['sid'])
		{
			$type = $this->getRequest()->getParam('type');
		    switch ($type)
		    {
			    case 'latest': // 最新问题
			    {
			    	$total  = $sortDetail['question'] - $sortDetail['solved'] - $sortDetail['closed'] - $sortDetail['overtime'];
			    	$paging	= new Paging(array('total' => $total, 'perpage' => 25));
			    	$list   = AskSortLogic::init()->selectSortLatest($this->_sortId, $paging->limit());

    			    break;
			    }
			    case 'offer': // 高分悬赏
			    {
			    	$total  = $sortDetail['question'] - $sortDetail['solved'] - $sortDetail['closed'] - $sortDetail['overtime'];
			        $paging	= new Paging(array('total' => $total, 'perpage' => 25));
			        $list   = AskSortLogic::init()->selectSortOffer($this->_sortId, $paging->limit());

    			    break;
			    }
			    case 'forget': // 被遗忘的
			    {
			    	$total  = $sortDetail['question'] - $sortDetail['solved'] - $sortDetail['closed'] - $sortDetail['overtime'];
			        $paging	= new Paging(array('total' => $total, 'perpage' => 25));
			        $list   = AskSortLogic::init()->selectSortForget($this->_sortId, $paging->limit());

    			    break;
			    }
			    case 'solved': // 已经解决
			    {
			    	$total  = $sortDetail['solved'];
			        $paging	= new Paging(array('total' => $total, 'perpage' => 25));
			        $list   = AskSortLogic::init()->selectSortSolved($this->_sortId, $paging->limit());

    			    break;
			    }
			    default: // 全部问题
			    {
    			    $type   = 'all';

			    	$total  = $sortDetail['question'] - $sortDetail['closed'] - $sortDetail['overtime'];
			    	$paging	= new Paging(array('total' => $total, 'perpage' => 25));
			    	$list   = AskSortLogic::init()->selectSortAll($this->_sortId, $paging->limit());
			    }
			}

		    $this->view->sort   = $sortDetail;
		    $this->view->type   = $type;
	        $this->view->total  = $total;
	        $this->view->paging = $paging->show();
	        $this->view->list   = $list;
	        $this->view->map    = HelpClass::getSortMap($sortAll, $this->_sortId);
	        $this->view->path   = HelpClass::getSortPath($sortAll, $this->_sortId);
	        $this->view->headTitle($sortDetail['name']);
	        $this->view->headScript()->appendFile('/static/scripts/help/sort/sort.js');	
		}
		else
		{
			$this->_forward('index', 'Index');
		}
	}
}
