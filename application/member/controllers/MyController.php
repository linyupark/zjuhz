<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MyController.php
 */


/**
 * 会员中心-我的账号
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
	private $_sessMember = null;

	/**
     * 会员模块配置
     *
     * @var object
     */
	private $_iniMember = null;

	/**
     * Session内的会员编号
     *
     * @var integer
     */
	private $_sessUid = 0;

	/**
     * 初始化
     *
     * @return void
     */
	public function init()
	{
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessMember = Zend_Registry::get('sessMember');
		$this->_iniMember  = Zend_Registry::get('iniMember');

		$this->_sessUid = $this->_sessCommon->login['uid'];

		$this->view->sessCommon = $this->_sessCommon;
	}

	/**
     * 我的首页
     * 
     * @return void
     */
	public function indexAction()
	{
		$this->_forward('user');
	}

	/**
     * 我的资料
     * 
     * @return void
     */
	public function userAction()
	{
		$this->view->headTitle($this->_iniMember->head->titleMyUser);
		$this->view->headScript()->appendFile('/static/scripts/member/my/user.js');

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'contact': // 联系信息
    			break;
			default:        // 基本信息
    			$type = 'basic';
		}

		$this->view->ctrl = 'user';
		$this->view->type = $type;
	}

	/**
     * 通讯录
     * 
     * @return void
     */
	public function addressAction()
	{
		$this->view->headTitle($this->_iniMember->head->titleMyAddress);
		$this->view->headScript()->appendFile('/static/scripts/member/my/address.js');

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'group':  // 组管理
			{
				$this->view->gid = Commons::getRandomStr($this->_sessUid, 5);

    			break;
			}
			case 'docard': // 名片操作
			{
				$cid    = $this->getRequest()->getParam('cid');
				$detail = (10 == strlen($cid) ? AddressCardLogic::init()->selectDetail($cid, $this->_sessUid) : '');

				$this->view->detail = $detail;
                $this->view->cid    = (10 == strlen($detail['cid']) ? $detail['cid'] : Commons::getRandomStr($this->_sessUid, 10));

    			break;
			}
			default:       // 名片管理
			{
				$getArgs = $this->getRequest()->getParams();
				$getArgs['uid'] = $this->_sessUid;

				$findArgs = MyFilter::init()->find($getArgs);
                $logic    = AddressCardLogic::init(); // 名片数据库操作类

				$p = new Paging(array('total' => $logic->selectFind('count', $findArgs, ''), 'perpage' => 20));
			    $this->view->cardList = $logic->selectFind('result', $findArgs, $p->limit());
			    $this->view->paging   = $p->show();

			    $this->view->gid    = $findArgs['gid'];
			    $this->view->field  = $findArgs['field'];
                $this->view->wd     = $findArgs['wd'];
			    $this->view->find   = $this->_iniMember->find->addressCard->toArray();
			    $this->view->status = $this->_iniMember->status->invite->toArray();

    			$type = 'card';
			}
		}

		$this->view->groupList = AddressGroupLogic::init()->selectList($this->_sessUid);
		$this->view->ctrl = 'address';
		$this->view->type = $type;
	}

	/**
     * 账号安全
     * 
     * @return void
     */
	public function accountAction()
	{
		$this->view->headTitle($this->_iniMember->head->titleMyAccount);
		$this->view->headScript()->appendFile('/static/scripts/member/my/account.js');

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			default:        // 修改密码
    			$type = 'passwd';
		}

		$this->view->ctrl = 'account';
		$this->view->type = $type;
	}

	/**
     * 个人资料-基础信息
     * 
     * @return void
     */
	public function dobasicAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($basicArgs = MyFilter::init()->basic($postArgs))
			{
				if (UserLogic::init()->update($basicArgs, $this->_sessUid))
				{
					Commons::modiSess('common', 'login', $basicArgs); // 同步Session

					$this->_sessMember->message = $this->_iniMember->hint->insertSuccess;

					echo 'message'; // 请求ajax给出提示
				}
			}
		}
	}

	/**
     * 个人资料-联络信息
     * 
     * @return void
     */
	public function docontactAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($contactArgs = MyFilter::init()->contact($postArgs))
			{
				if (UserContactLogic::init()->update($contactArgs, $this->_sessUid))
				{
					Commons::modiSess('common', 'login', $contactArgs); // 同步Session

					$this->_sessMember->message = $this->_iniMember->hint->insertSuccess;

					echo 'message';
				}
			}
		}
	}

	/**
     * 通讯录-名片新建/修改
     * 
     * @return void
     */
	public function docardAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid']      = $this->_sessUid;
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($cardArgs = MyFilter::init()->card($postArgs))
			{
				$this->_sessMember->message = (AddressCardLogic::init()->insertOrUpdate($cardArgs) ? 
				    $this->_iniMember->hint->insertSuccess : $this->_iniMember->hint->insertFailure 
				);

				echo 'message';
			}
		}
	}

	/**
     * 通讯录-组新增/修改
     * 
     * @return void
     */
	public function dogroupAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid']      = $this->_sessUid;
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($groupArgs = MyFilter::init()->group($postArgs))
			{
				$this->_sessMember->message = ((AddressGroupLogic::init()->insertOrUpdate($groupArgs)) ? 
				    $this->_iniMember->hint->insertSuccess : $this->_iniMember->hint->insertFailure 
				);

				echo 'message';
			}
		}
	}

	/**
     * 通讯录-组删除
     * 
     * @return void
     */
	public function dogroupdelAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid'] = $this->_sessUid;

			if ($groupdelArgs = MyFilter::init()->groupdel($postArgs))
			{
				// 只有当组内未存有名片时才能删除
				if (!AddressCardLogic::init()->selectCount($groupdelArgs['gid'], $groupdelArgs['uid']))
			    {
			    	if (AddressGroupLogic::init()->delete($groupdelArgs))
				    {
				    	echo 'hide';
				    }
			    }
			}
		}
	}

	/**
     * 账号安全-修改密码
     * 
     * @return void
     */
	public function dopasswdAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid'] = $this->_sessUid;

			if ($passwdArgs = MyFilter::init()->passwd($postArgs))
			{
				if (MemberClass::checkVerifyCode($postArgs['vcode'], $this->_sessCommon->verify))
				{
					if ($passwdArgs['oldpassword'] != $passwdArgs['password'])
					{
						if ($passwdArgs['oldpassword'] === $this->_sessCommon->login['password'])
				        {
				        	if (UserLogic::init()->updatePassword($passwdArgs))
				    	    {
				    	    	Commons::modiSess('common', 'login', $passwdArgs); // 同步Session
				    		    $this->_sessMember->message = $this->_iniMember->hint->updatePswdSuccess;
				    	    }
				    	    else
				    	    {
				    		    $this->_sessMember->message = $this->_iniMember->hint->updatePswdFailure;
				    	    }
				        }
				        else
				        {
					        $this->_sessMember->message = $this->_iniMember->hint->updatePswdFailure;
				        }
					}
					else
					{
						$this->_sessMember->message = $this->_iniMember->hint->oldPswdEqualNewPswd;
					}

    				echo 'message'; // 请求ajax给出提示
				}
			}
		}
	}
}
