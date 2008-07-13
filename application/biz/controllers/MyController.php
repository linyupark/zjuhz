<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MyController.php
 */


/**
 * 校友企业-我的创业
 */
class MyController extends Zend_Controller_Action
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

		$this->view->sessCommon  = $this->_sessCommon;
		$this->view->sessCompany = $this->_sessCompany;
	}

	/**
     * 临时功能-查看审核
     * 
     * @return void
     */
	public function auditAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if (3 == USER_UID || 4 == USER_UID || 5 == USER_UID || 6 == USER_UID)
		{
			print_r(CorpCompanyLogic::init()->selectAuditingAll());
			exit;
		}
	}

	/**
     * 临时功能-审核通过
     * 
     * @return void
     */
	public function doauditAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if (3 == USER_UID || 4 == USER_UID || 5 == USER_UID || 6 == USER_UID)
		{
			$cid = CommonFilter::cid(($this->getRequest()->getParam('cid')));
            if (10 == strlen($cid))
            {
            	$logic = CorpCompanyLogic::init();
                if ($row = $logic->selectCidRow($cid))
                {
                	if ($logic->updateStatusValid($cid)) // 更改企业状态为已审
            	    {
            	    	// 更改会员拥有企业总数
            		    CorpLogic::init()->update(array('valid' => new Zend_Db_Expr('valid + 1'), 
            		        'auditing' => new Zend_Db_Expr('auditing - 1'), 'uid' => $row['uid'])
            		    );

            		    // 更改行业所有企业总数
            		    CorpIndustryLogic::init()->insertOrUpdate(array(
            		        'count' => new Zend_Db_Expr('count + 1'), 'iid' => $row['industry'])
            		    );

            		    // 更改创业基础数据资料
            		    BaseLogic::init()->update(array('companies' => new Zend_Db_Expr('companies + 1')));

            		    // 刷新基础数据资料缓存
            		    CacheLogic::init()->baseRefresh();

            		    echo 'message';
            	    }
                }
            }
		}
	}

	/**
     * 我的创业首页
     * 
     * @return void
     */
	public function indexAction()
	{
		$this->_forward('company');
	}

	/**
     * 我的企业
     * 
     * @return void
     */
	public function companyAction()
	{
		$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'join': // 申请加入
			{
    			break;
			}
			case 'auditing': // 审核中
			{
				$companies = CorpCompanyLogic::init()->selectUidAuditingAll(USER_UID);
				$this->_sessCompany->login['auditing'] = count($companies);
				$this->view->companies = $companies;

    			break;
			}
			case 'untread': // 审核未通过
			{
				$this->_sessCompany->login['untread'] = 0;
				$this->view->companies = array();

    			break;
			}
			default: // 已发布上网
			{
				$type = 'valid';

				$companies = CorpCompanyLogic::init()->selectUidValidAll(USER_UID);
				$this->_sessCompany->login['valid'] = count($companies);
				$this->view->companies = $companies;
			}
		}

		$this->view->headTitle($this->_iniCompany->head->titleMyCompany);
		$this->view->headScript()->appendFile('/static/scripts/biz/my/company.js');		
		$this->view->action = 'company';
		$this->view->type   = $type;
		$this->_helper->layout->setLayout('my');
		$this->getResponse()->insert('nav', $this->view->render('my-nav.phtml'));
		$this->render("company-{$type}");
	}

	/**
     * 我的企业-申请加入
     * 
     * @return void
     */
	public function dojoinAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['cid'] = Commons::getRandomStr(USER_UID, 10);
			$postArgs['uid'] = USER_UID;

			if ($joinArgs = MyFilter::init()->join($postArgs))
			{
				if (CorpCompanyLogic::init()->insert($joinArgs))
				{
					$this->_sessCompany->message = $this->_iniCompany->hint->joinSubmit;

					echo 'message'; // 请求ajax弹出提示
				}
			}
		}
	}

	/**
     * 我的留言
     * 
     * @return void
     */
	public function messageAction()
	{
		$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'notreply': // 未回复
			{
    			break;
			}
			case 'hasreply': // 已回复
			{
    			break;
			}
			default: // 全部
			{
				$type = 'all';
			}
		}

        // 显示留言簿内容列表
        $paging  = new Paging(array('totalRs' => $this->_sessCompany->login['msgs'], 'perPage' => 10));
        $message = CompanyMsgLogic::init()->selectUidAll(USER_UID, $paging->limit());
        $this->view->message = $message;
        $this->view->paging  = $paging->show();

		$this->view->headTitle($this->_iniCompany->head->titleMyCompany);
		$this->view->headLink()->appendStylesheet('/static/styles/paging.css', 'screen');
		$this->view->action = 'message';
		$this->view->type   = $type;
		$this->_helper->layout->setLayout('my');
		$this->getResponse()->insert('nav', $this->view->render('my-nav.phtml'));
		$this->render("message-{$type}");
	}
}
