<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     biz
 * @version    Id:IndexController.php
 */


/**
 * 校友企业-主控程序
 */
class IndexController extends Zend_Controller_Action
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
     * 项目模块配置
     * 
     * @var object
     */
	private $_iniCompany = null;

	/**
     * 初始化
     * 
     * @return void
     */
	public function init()
	{
		$this->_sessCommon  = Zend_Registry::get('sessCommon');
		$this->_sessCompany = Zend_Registry::get('sessCompany');
		$this->_iniCompany  = Zend_Registry::get('iniCompany');

		$this->view->sessCommon = $this->_sessCommon;
	}

	/**
     * company验证码
     * 
     * @return void
     */
	public function verifyAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		ImageHandle::verify('common');
	}

    /**
     * 弹窗信息提示
     * 
     * @return void
     */
    public function messageAction()
    {
		$this->_helper->layout->disableLayout();

		$this->view->message = $this->_sessCompany->message;
    }

	/**
     * 校友企业首页
     * 
     * @return void
     */
	public function indexAction()
	{
		$this->view->headTitle($this->_iniCompany->head->titleIndex);
		//$this->view->headScript()->appendFile('/static/scripts/biz/index/index.js');

		$this->view->recmd    = CorpCompanyLogic::init()->selectRandList(20); // 随机显示全部推荐企业
		$this->view->list     = CorpCompanyLogic::init()->selectJoinAll(10); // 按新加入显示全部企业
		$this->view->industry = CorpIndustryLogic::init()->selectPairs(); // 显示全部行业分类目录
	}

	/**
     * 校友企业分类
     * 
     * @return void
     */
	public function industryAction()
	{
		// 获取企业编号
		$iid   = CommonFilter::iid(($this->getRequest()->getParam('iid')));
        $iname = $this->view->industry($iid);

		$this->view->headTitle($iname);
		$this->view->iid      = $iid;
		$this->view->iname    = $iname;
		$this->view->recmd    = CorpCompanyLogic::init()->selectIndustryRandList($iid, 20); // 随机显示行业推荐企业
		$this->view->list     = CorpCompanyLogic::init()->selectIndustryJoinAll($iid, 10); // 按新加入显示行业企业
		$this->view->industry = CorpIndustryLogic::init()->selectPairs(); // 显示全部行业分类目录
	}
}
