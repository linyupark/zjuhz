<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ManageController.php
 */


/**
 * 校友企业-企业管理
 */
class ManageController extends Zend_Controller_Action
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
		$this->_iniCompany  = Zend_Registry::get('iniCompany');

        // 获取动作名称
        $actionName = $this->getRequest()->getActionName();
		// 判断管理权限
		(!$this->_sessCompany->manageCid && 'login' != $actionName ? 
		    $this->_redirect('/company/my/company/type/valid/', array('exit')) : '');
		// 载入企业资料
		$this->_dataCompany = (!$this->_sessCompany->manageCid || 'login' == $actionName ? '' : 
		    CacheLogic::init()->companyLoad($this->_sessCompany->manageCid));

		$this->view->sessCommon  = $this->_sessCommon;
		$this->view->sessCompany = $this->_sessCompany;
		$this->view->dataCompany = $this->_dataCompany;
	}

	/**
     * 企业管理-登录系统
     * 
     * @return void
     */
	public function loginAction()
	{
		// 初始销毁
		$this->_sessCompany->manageCid = '';
		// 企业管理session初始化
		$cid      = CommonFilter::cid(($this->getRequest()->getParam('cid')));
		$redirect = $this->getRequest()->getParam('redirect', '/manage/company/');
		if ($row = CorpCompanyLogic::init()->selectCidUidRow($cid, USER_UID))
		{
			CacheLogic::init()->companySave($row, $cid);

			$this->_sessCompany->manageCid = $cid;
			$this->_redirect($redirect, array('exit'));
		}
		else
		{
			$this->_sessCompany->manageCid = '';
			$this->_redirect('/company/my/company/type/valid/', array('exit'));
		}
	}

	/**
     * 企业管理-企业资料
     * 
     * @return void
     */
	public function companyAction()
	{
		$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'contact': // 联系方式
			{
    			break;
			}
			case 'biz': // 商务供求
			{
    			break;
			}
			case 'logo': // 企业标志
			{
    			break;
			}
			default: // 基础信息
			{
				$type = 'basic';
			}
		}

		$this->view->headTitle($this->_iniCompany->head->titleManageCompany);
		$this->view->headScript()->appendFile('/static/scripts/company/manage/company.js');
		$this->view->action = 'company';
		$this->view->type   = $type;
		$this->_helper->layout->setLayout('manage');
		$this->getResponse()->insert('nav', $this->view->render('manage-nav.phtml'));
		$this->render("company-{$type}");
	}

	/**
     * 企业管理-基础信息
     * 
     * @return void
     */
	public function dobasicAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['cid'] = $this->_sessCompany->manageCid;
			$postArgs['uid'] = USER_UID;

			if ($basicArgs = ManageFilter::init()->basic($postArgs))
			{
				(!CorpCompanyLogic::init()->updateBasic($basicArgs) ? '' : 
				    CacheLogic::init()->companySave(array_merge($this->_dataCompany, $basicArgs), $basicArgs['cid']));

				$this->_sessCompany->message = $this->_iniCompany->hint->updateSuccess;

				echo 'message'; // 请求ajax弹出提示
			}
		}
	}

	/**
     * 企业管理-联系方式
     * 
     * @return void
     */
	public function docontactAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['cid'] = $this->_sessCompany->manageCid;
			$postArgs['uid'] = USER_UID;

			if ($contactArgs = ManageFilter::init()->contact($postArgs))
			{
				(!CompanyContactLogic::init()->updateContact($contactArgs) ? '' : 
				    CacheLogic::init()->companySave(array_merge($this->_dataCompany, $contactArgs), $contactArgs['cid']));

				$this->_sessCompany->message = $this->_iniCompany->hint->updateSuccess;

				echo 'message'; // 请求ajax弹出提示
			}
		}
	}

	/**
     * 企业管理-商务供求
     * 
     * @return void
     */
	public function dobizAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['cid'] = $this->_sessCompany->manageCid;
			$postArgs['uid'] = USER_UID;

			if ($bizArgs = ManageFilter::init()->biz($postArgs))
			{
				(!CompanyBizLogic::init()->updateBiz($bizArgs) ? '' : 
				    CacheLogic::init()->companySave(array_merge($this->_dataCompany, $bizArgs), $bizArgs['cid']));

				$this->_sessCompany->message = $this->_iniCompany->hint->updateSuccess;

				echo 'message'; // 请求ajax弹出提示
			}
		}
	}

	/**
     * 企业管理-企业标志
     * 
     * @return void
     */
	public function dologoAction()
	{
	    $this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		$companyRoot = DOCUMENT_ROOT.Commons::getCompanyFolder($this->_sessCompany->manageCid);
		Upload::init(array('max_size' => 55, 'cust_name' => 'original.jpg', 
		    'allow_type' => 'jpg|jpeg', 'overwrite' => true, 'upload_path' => $companyRoot));

		if(Upload::handle('fileData')) // 上传成功
		{
			// 以最小边切成正方形
			$image = new ImageHandle($companyRoot.'original.jpg');
			$image->square($companyRoot.'square');
			// 再以正方形强制切割
			$image->init($companyRoot.'square.jpg');
			$image->abs_resize(30, 30, $companyRoot.'small'); // 小图
			$image->init($companyRoot.'square.jpg');
			$image->abs_resize(54, 54, $companyRoot.'medium'); // 中图
			$image->init($companyRoot.'square.jpg');
			$image->abs_resize(100, 100, $companyRoot.'large'); // 大图

			// 标记企业标志已上传,可读取标志图片
			(!CorpCompanyLogic::init()->updateFace($this->_sessCompany->manageCid) ? '' : 
			    CacheLogic::init()->companySave(array_merge($this->_dataCompany, array('face' => 'Y')), $this->_sessCompany->manageCid));

			echo 'message';

		} else {

            //file_put_contents('CompanyUploadException.log', Upload::getTip());exit;
			echo 'failure'; 

		}
	}
}
