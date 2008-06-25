<?php

/**
 * @category   zjuhz.com
 * @package    company
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
     * 初始化
     * 
     * @return void
     */
	public function init()
	{
		$this->_sessCommon  = Zend_Registry::get('sessCommon');
		$this->_sessCompany = Zend_Registry::get('sessCompany');

		// 获取企业编号
		$this->_dataCid = CommonFilter::cid(($this->getRequest()->getParam('cid')));
		// 载入企业资料
		$this->_dataCompany = (!$this->_dataCid ? '' : 
		    CacheLogic::init()->companyLoad($this->_dataCid));
        // 判断数据可用
		($this->_dataCid === $this->_dataCompany['cid'] ? '' : 
		    $this->_redirect('/company/', array('exit')));

		$this->view->sessCommon  = $this->_sessCommon;
		$this->view->sessCompany = $this->_sessCompany;
		$this->view->dataCompany = $this->_dataCompany;
		$this->view->dataCid     = $this->_dataCid;
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

		$this->view->headTitle($this->_dataCompany['name']);
		$this->_helper->layout->setLayout('company');
	}
}
