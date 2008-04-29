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
     * 会员模块配置对象
     *
     * @var object
     */
	private $_iniMember = null;

	/**
     * 公用SESSION对象
     *
     * @var array
     */
	private $_sessCommon = null;

	/**
     * 项目SESSION对象
     *
     * @var array
     */
	private $_sessMember = null;

	/**
     * sess内的会员编号
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
		$this->_iniMember  = Zend_Registry::get('iniMember'); // 载入项目配置
		$this->_sessCommon = Zend_Registry::get('sessCommon'); // 载入公共SESSION
		$this->_sessMember = Zend_Registry::get('sessMember'); // 载入项目SESSION

		$this->_sessUid = $this->_sessCommon->login['uid']; // sessionUid

		$this->view->sessCommon = $this->_sessCommon; // Session资料注入
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
		$this->view->headTitle($this->_iniMember->head->title->my->user); // 载入标题
		$this->view->headScript()->appendFile('/static/scripts/member/my/user.js'); // 载入JS脚本

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'contact': // 联系方式
    			break;
			case 'edu':     // 教育背景
    			break;
			case 'work':    // 工作经历
    			break;
			case 'hobbies': // 兴趣爱好
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
		$this->view->headTitle($this->_iniMember->head->title->my->address); // 载入标题
		$this->view->headScript()->appendFile('/static/scripts/member/my/address.js'); // 载入JS脚本			

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'group': // 组管理
			{
				$this->view->gid = Commons::getRandomStr($this->_sessUid, 5);
    			break;
			}
			default:      // 名片管理
			{
				$gid   = $this->getRequest()->getParam('gid');
				$field = $this->getRequest()->getParam('field');
				$wd    = $this->getRequest()->getParam('wd');

			    $total    = AddressCardLogic::init()->selectFindList('count', $gid, $this->_sessUid, $field, $wd, '');
                $p        = new Paging(array('total' => $total, 'perpage' => 10));
				$cardList = AddressCardLogic::init()->selectFindList('result', $gid, $this->_sessUid, $field, $wd, $p->limit());

				$this->view->total    = $total;
				$this->view->cid      = Commons::getRandomStr($this->_sessUid, 10);
			    $this->view->cardList = $cardList;
			    $this->view->select   = $this->_iniMember->select;
			    $this->view->paging   = $p->show();
    			$type = 'card';
			}
		}

		$this->view->groupList = AddressGroupLogic::init()->selectList($this->_sessUid);
		$this->view->ctrl = 'address';
		$this->view->type = $type;
	}

	/**
     * 通讯录-名片新建/修改/查看/邀请/
     * 
     * @return void
     */
	public function popupAction()
	{
		$this->_helper->layout->disableLayout(); // 禁用layout

    	$type = $this->getRequest()->getParam('type');
    	$cid  = $this->getRequest()->getParam('cid');
		switch ($type)
		{
			case 'invite': // 邀请/
			{				
    			break;
			}
			default:       // 名片新建/修改查看/
			{
				$this->view->groupList = AddressGroupLogic::init()->selectList($this->_sessUid);				
				$type = 'card';
			}			
		}
		
		$this->view->detail = AddressCardLogic::init()->selectDetail($cid, $this->_sessUid);
		$this->view->cid  = $cid;			
		$this->view->ctrl = 'popup';
		$this->view->type = $type;			
	}

	/**
     * 我的资料
     * 
     * @return void
     */
	public function dobasicAction()
	{
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs             = $this->getRequest()->getPost();
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($basicArgs = MyFilter::init()->basic($postArgs))
			{
				if (UserLogic::init()->update($basicArgs, $this->_sessUid))
				{
					Commons::modiSess('common', 'login', $basicArgs);

					$this->_sessMember->message = $this->_iniMember->hint->insertSuccess;

					echo 'message';
				}
			}
		}
	}

	/**
     * 我的资料
     * 
     * @return void
     */
	public function docontactAction()
	{
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs             = $this->getRequest()->getPost();
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($contactArgs = MyFilter::init()->contact($postArgs))
			{
				if (UserContactLogic::init()->update($contactArgs, $this->_sessUid))
				{
					Commons::modiSess('common', 'login', $contactArgs);

					$this->_sessMember->message = $this->_iniMember->hint->insertSuccess;

					echo 'message';
				}
			}
		}
	}

	/**
     * 通讯录-卡片操作
     * 
     * @return void
     */
	public function docardAction()
	{
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs         = $this->getRequest()->getPost();
			$tmpgid           = $postArgs['tmpgid']; $postArgs['tmpgid'] = null;
			$postArgs['uid']  = $this->_sessUid;
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($cardArgs = MyFilter::init()->card($postArgs))
			{
				if (AddressCardLogic::init()->insertOrUpdate($cardArgs))
				{
					if ($tmpgid == '')
					{
						AddressGroupLogic::init()->update(array(
					    	'count' => new Zend_Db_Expr('count + 1')), $cardArgs['gid']
						);						
					}
					elseif ($tmpgid != $cardArgs['gid'])
					{
						AddressGroupLogic::init()->update(array(
					    	'count' => new Zend_Db_Expr('count - 1')), $tmpgid
						);

						AddressGroupLogic::init()->update(array(
					    	'count' => new Zend_Db_Expr('count + 1')), $cardArgs['gid']
						);
					}

					$this->_sessMember->message = $this->_iniMember->hint->insertSuccess;

					echo 'message';
				}
			}
		}
	}

	/**
     * 通讯录-组操作
     * 
     * @return void
     */
	public function dogroupAction()
	{
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs         = $this->getRequest()->getPost();
			$postArgs['uid']  = $this->_sessUid;
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($groupArgs = MyFilter::init()->group($postArgs))
			{
				$this->_sessMember->message = ((AddressGroupLogic::init()->insertOrUpdate($groupArgs)) ? 
				    $this->_iniMember->hint->insertSuccess :
				    $this->_iniMember->hint->insertFailure 
				);
				
				echo 'message';
			}
		}
	}

	/**
     * 通讯录-邀请操作
     * 
     * @return void
     */
	public function doinviteAction()
	{
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs           = $this->getRequest()->getPost();
			$postArgs['status'] = 0;

			if ($inviteArgs = MyFilter::init()->invite($postArgs))
			{
				if (AddressCardLogic::init()->update($inviteArgs, $postArgs['cid']))
				{
					echo 'reload';
				}
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
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs['gid'] = $this->getRequest()->getParam('gid');
			$postArgs['uid'] = $this->_sessUid;

			if ($groupdelArgs = MyFilter::init()->groupdel($postArgs))
			{
				if (AddressGroupLogic::init()->delete($groupdelArgs))
				{
					echo 'hide';
				}
			}
		}
	}
}
