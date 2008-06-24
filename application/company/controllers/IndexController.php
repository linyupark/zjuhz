<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
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
		//$this->view->headScript()->appendFile('/static/scripts/company/index/index.js');

		$this->view->rand     = CorpCompanyLogic::init()->selectRandList(20);
		$this->view->join     = CorpCompanyLogic::init()->selectJoinAll(10);
		$this->view->industry = CorpIndustryLogic::init()->selectPairs();
	}
}
