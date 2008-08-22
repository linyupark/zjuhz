<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:DetailController.php
 */


/**
 * 校友企业-企业展示
 */
class DetailController extends Zend_Controller_Action
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
	private $_sessCompany = null;

	/**
     * 当前企业编号
     * 
     * @var string
     */
	private $_dataCid = null;

	/**
     * 当前企业资料
     * 
     * @var array
     */
	private $_dataCompany = array();

	/**
     * 当前企业个人资料
     * 
     * @var array
     */
	private $_dataCorp = array();

	/**
     * 初始化
     * 
     * @return void
     */
	public function init()
	{
		$this->_sessCommon  = Zend_Registry::get('sessCommon');
		$this->_sessCompany = Zend_Registry::get('sessCompany');

		$this->view->sessCommon  = $this->_sessCommon;
		$this->view->sessCompany = $this->_sessCompany;
        
        // 获取企业编号
		$this->_dataCid = CommonFilter::cid(($this->getRequest()->getParam('cid')));
		// 载入企业资料
		$this->_dataCompany = (!$this->_dataCid ? '' : 
		    CacheLogic::init()->companyLoad($this->_dataCid));
        // 判断数据可用
		($this->_dataCid === $this->_dataCompany['cid'] ? '' : 
		    $this->_redirect('/biz/', array('exit')));
		// 仅登录后可见
		$this->_dataCorp = ('member' !== $this->_sessCommon->role ? '' : 
			CorpLogic::init()->selectUidRow($this->_dataCompany['uid']));
	}

    public function showAction()
    {
        /* 分类数据 */
        $db = CompanyShowModel::_dao();
        
        $sort = urldecode($this->_getParam('sort'));
        $cid = $this->_dataCid;
        $page = $this->_getParam('p', 1);
        
        $row = $db->fetchRow('SELECT COUNT(`pid`) AS `numrows` FROM `tbl_corp_company_show` 
                             WHERE `cid` = "'.$cid.'" AND `sort` = "'.$sort.'"');
        
        //Page::$pagesize = 1;
        Page::create(array(
                    'href_open' => '<a href="/biz/detail/show/cid/'.$cid.'/sort/'.$sort.'?p=%d">',
                    'href_close' => '</a>',
                    'num_rows' => $row['numrows'],
                    'cur_page' => $page
        ));

        $prods = $db->fetchAll('SELECT * FROM `tbl_corp_company_show`
                               WHERE `cid` = "'.$cid.'" AND `sort` = "'.$sort.'"
                               ORDER BY `pid` DESC LIMIT '.Page::$offset.','.Page::$pagesize);
        $this->view->prods = $prods;
        $this->view->pagination = Page::$page_str;
        
        $sorts = $db->fetchAll('SELECT DISTINCT `sort` FROM `tbl_corp_company_show` WHERE `cid` = ?', $this->_dataCid);
        $this->view->sorts = $sorts;
        
        $this->view->headTitle($this->_dataCompany['name']);
        $this->view->dataCompany = $this->_dataCompany;
    }

	/**
     * 企业展示首页
     * 
     * @return void
     */
	public function indexAction()
	{
		if (!$this->_sessCompany->pageview[$this->_dataCid])
		{
			CorpCompanyLogic::init()->updatePageview($this->_dataCid); // 更新页面浏览量
			$this->_sessCompany->pageview[$this->_dataCid] = REQUEST_TIME; // 浏览时间
		}

		$this->_helper->layout->setLayout('company');
        
        /* 分类数据 */
        $db = CompanyShowModel::_dao();
        $sorts = $db->fetchAll('SELECT DISTINCT `sort` FROM `tbl_corp_company_show` WHERE `cid` = ?', $this->_dataCid);
        $this->view->sorts = $sorts;
        
		$this->view->headTitle($this->_dataCompany['name']);
		$this->view->message     = CompanyMsgLogic::init()->selectCidNotDeletedAll($this->_dataCid, '5');
		$this->view->dataCid     = $this->_dataCid;
		$this->view->dataCompany = $this->_dataCompany;
		$this->view->dataCorp    = $this->_dataCorp;
	}
}
