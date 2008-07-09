<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MyController.php
 */


/**
 * 校友中心-我的账号
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
     * 公用模块配置
     * 
     * @var object
     */
	private $_iniCommon = null;

	/**
     * 项目模块配置
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
		$this->_iniCommon  = Zend_Registry::get('iniCommon');
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
		$this->view->headLink()->appendStylesheet('/static/styles/calendar-blue.css', 'screen');
        $this->view->headLink()->appendStylesheet('/static/styles/swfupload.css','screen');

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'contact': // 联系信息
			{
    			break;
			}
			case 'work':    // 工作经验
			{
				$wid    = $this->getRequest()->getParam('wid');
				$detail = (10 == strlen($wid) ? UserWorkLogic::init()->selectWidRow($wid, $this->_sessUid) : '');

                $this->view->wid    = (10 == strlen($detail['wid']) ? $detail['wid'] : 
                    Commons::getRandomStr($this->_sessUid, 10));
                $this->view->detail = $detail;
                $this->view->work   = UserWorkLogic::init()->selectUidAll($this->_sessUid);

    			break;
			}
			case 'edu':    // 教育经历
			{
				$eid    = $this->getRequest()->getParam('eid');
				$detail = (10 == strlen($eid) ? UserEduLogic::init()->selectEidRow($eid, $this->_sessUid) : '');

                $this->view->eid    = (10 == strlen($detail['eid']) ? $detail['eid'] : 
                    Commons::getRandomStr($this->_sessUid, 10));
                $this->view->detail = $detail;
                $this->view->edu    = UserEduLogic::init()->selectUidAll($this->_sessUid);

    			break;
			}
			default:        // 基本信息
			{
    			$type = 'basic';
			}
		}

		$this->view->ctrl = 'user';
		$this->view->type = $type;
	}

	/**
     * 我的头像
     * 
     * @return void
     */
	public function faceAction()
	{
		$this->view->headTitle($this->_iniMember->head->titleMyFace);
		$this->view->headScript()->appendFile('/static/scripts/member/my/face.js');

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			default:        // 上传头像
			{
    			$type = 'upload';
			}
		}

		$this->view->ctrl = 'face';
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
				$this->view->gid   = Commons::getRandomStr($this->_sessUid, 5);
				$this->view->group = AddressGroupLogic::init()->selectUidAll($this->_sessUid);

    			break;
			}
			case 'docard': // 名片操作
			{
				$cid    = $this->getRequest()->getParam('cid');
				$detail = (10 == strlen($cid) ? AddressCardLogic::init()->selectCidRow($cid, $this->_sessUid) : '');

                $this->view->cid    = (10 == strlen($detail['cid']) ? $detail['cid'] : 
                    Commons::getRandomStr($this->_sessUid, 10));
                $this->view->detail = $detail;

    			break;
			}
			case 'doinvite': // 邀请操作
			{
				$cid    = $this->getRequest()->getParam('cid');
				$detail = (10 == strlen($cid) ? AddressCardLogic::init()->selectCidRow($cid, $this->_sessUid) : 
                    $this->_redirect('/member/my/address/type/card/', array('exit')));

				$this->view->detail = $detail;

    			break;
			}
			default:       // 名片管理
			{
    			$type = 'card';

				$getArgs = $this->getRequest()->getParams();
				$getArgs['uid'] = $this->_sessUid;

				$findArgs = MyFilter::init()->find($getArgs);
                $logic    = AddressCardLogic::init();
                $total    = $logic->selectFind('count', $findArgs, '');
				$paging   = new Paging(array('totalRs' => $total));

			    $this->view->find   = $this->_iniMember->find->addressCard->toArray();
			    $this->view->status = $this->_iniMember->status->invite->toArray();
			    $this->view->total  = $total;
			    $this->view->gid    = $findArgs['gid'];
			    $this->view->group  = AddressGroupLogic::init()->selectUidPairs($this->_sessUid);
			    $this->view->field  = $findArgs['field'];
                $this->view->wd     = $findArgs['wd'];
			    $this->view->paging = $paging->show();
                $this->view->card   = $logic->selectFind('result', $findArgs, $paging->limit());
			}
		}

		$this->view->ctrl  = 'address';
		$this->view->type  = $type;		
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
			default: // 修改密码
			{
    			$type = 'passwd';

    			$this->view->headLink()->appendStylesheet('/static/styles/passwdcheck.css', 'screen');
			}
		}

		$this->view->ctrl = 'account';
		$this->view->type = $type;
	}

	/**
     * 我的资料-基础信息
     * 
     * @return void
     */
	public function dobasicAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($basicArgs = MyFilter::init()->basic($postArgs))
			{
				if (UserLogic::init()->update($basicArgs, $this->_sessUid))
				{
					Commons::modiSess('common', 'login', $basicArgs); // 同步Session
		            CacheLogic::init()->cardSave($this->_sessCommon->login); // 同步名片

					$this->_sessMember->message = $this->_iniMember->hint->insertSuccess;

					echo 'message'; // 请求ajax给出提示
				}
			}
		}
	}

	/**
     * 我的资料-更改头像
     * 
     * @return void
     */
	public function dofaceAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		Upload::init(array('max_size' => 35, 'cust_name' => 'original', 
		    'allow_type' => 'jpg|jpeg', 'overwrite' => true, 'upload_path' => USER_ROOT));

		if(Upload::handle('fileData')) // 上传成功
		{
			// 以最小边切成正方形
			$image = new ImageHandle(USER_ROOT.'original.jpg');
			$image->square(USER_ROOT.'square');
			// 再以正方形强制切割
			$image->init(USER_ROOT.'square.jpg');
			$image->abs_resize(30, 30, USER_ROOT.'small'); // 小图
			$image->init(USER_ROOT.'square.jpg');
			$image->abs_resize(54, 54, USER_ROOT.'medium'); // 中图
			$image->init(USER_ROOT.'square.jpg');
			$image->abs_resize(200, 200, USER_ROOT.'large'); // 大图

			echo 'message';

		} else {

            //print_r(Upload::getTip());exit;
			echo 'failure'; 

		}
	}

	/**
     * 我的资料-联络方式
     * 
     * @return void
     */
	public function docontactAction()
	{
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
     * 我的资料-工作经验
     * 
     * @return void
     */
	public function doworkAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid']      = $this->_sessUid;
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($workArgs = MyFilter::init()->work($postArgs))
			{
				if (UserWorkLogic::init()->insertOrUpdate($workArgs))
				{
					$this->_sessMember->message = $this->_iniMember->hint->insertSuccess;

					echo 'message';
				}
			}
		}
	}

	/**
     * 我的资料-删除工作
     * 
     * @return void
     */
	public function doworkdelAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid'] = $this->_sessUid;

			if ($workdelArgs = MyFilter::init()->workdel($postArgs))
			{
				echo (UserWorkLogic::init()->delete($workdelArgs) ? 'hide' : '');
			}
		}
	}

	/**
     * 我的资料-教育经历
     * 
     * @return void
     */
	public function doeduAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid']      = $this->_sessUid;
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($eduArgs = MyFilter::init()->edu($postArgs))
			{
				if (UserEduLogic::init()->insertOrUpdate($eduArgs))
				{
					$this->_sessMember->message = $this->_iniMember->hint->insertSuccess;

					echo 'message';
				}
			}
		}
	}

	/**
     * 我的资料-删除教育
     * 
     * @return void
     */
	public function doedudelAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid'] = $this->_sessUid;

			if ($edudelArgs = MyFilter::init()->edudel($postArgs))
			{
				echo (UserEduLogic::init()->delete($edudelArgs) ? 'hide' : '');
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
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid']      = $this->_sessUid;
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($cardArgs = MyFilter::init()->card($postArgs))
			{
				$this->_sessMember->message = (AddressCardLogic::init()->insertOrUpdate($cardArgs) ? 
				    $this->_iniMember->hint->insertSuccess : $this->_iniMember->hint->insertFailure);

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
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid']      = $this->_sessUid;
			$postArgs['lastModi'] = $_SERVER['REQUEST_TIME'];

			if ($groupArgs = MyFilter::init()->group($postArgs))
			{
				$this->_sessMember->message = ((AddressGroupLogic::init()->insertOrUpdate($groupArgs)) ? 
				    $this->_iniMember->hint->insertSuccess : $this->_iniMember->hint->insertFailure);

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
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid'] = $this->_sessUid;

			if ($groupdelArgs = MyFilter::init()->groupdel($postArgs))
			{
				// 仅当组内未存有名片才能删除
				if (!AddressCardLogic::init()->selectGidCount($groupdelArgs['gid'], $groupdelArgs['uid']))
			    {
			    	echo (AddressGroupLogic::init()->delete($groupdelArgs) ? 'hide' : '');
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
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid']   = $this->_sessUid;
			$postArgs['scode'] = $this->_sessCommon->verify;

			if ($passwdArgs = MyFilter::init()->passwd($postArgs))
			{
				if ($passwdArgs['oldpassword'] != $passwdArgs['password'])
				{
					// 设定默认错误信息 可免else语句
					$this->_sessMember->message = $this->_iniMember->hint->updatePswdFailure;

					if ($passwdArgs['oldpassword'] === $this->_sessCommon->login['password'])
				    {
				       	if (UserLogic::init()->updatePassword($passwdArgs))
				   	    {
				   	    	Commons::modiSess('common', 'login', $passwdArgs); // 同步Session

				   		    $this->_sessMember->message = $this->_iniMember->hint->updatePswdSuccess;
				   	    }
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

	/**
     * 通讯录-email邀请好友
     * 
     * @return void
     */
	public function doemailAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$cid = $postArgs['cid'];

			$this->_sessMember->message = $this->_iniMember->hint->inviteFailure;

			if (!$this->_sessMember->invite[$cid] && ($inviteArgs = MyFilter::init()->invite($postArgs)))
			{
				$AddressCardLogic   = AddressCardLogic::init();
			    if ($AddressCardRow = $AddressCardLogic->selectCidRow($cid, $this->_sessUid))
			    {
			    	$InviteLogLogic = InviteLogLogic::init();
				    if (!$InviteLogLogic->selectCidRow($cid))
			        {
			        	$insertArgs = array('cid' => $cid, 'logTime' => $_SERVER['REQUEST_TIME']);
			            $updateArgs = array('status' => 0);
			    	    if (!$InviteLogLogic->insert($insertArgs) ? false : $AddressCardLogic->update($updateArgs, $cid))
			    	    {
			    	    	$config = $this->_iniCommon->mail->gmail->toArray(); // 载入ini的配置
		                    $mail   = new Zend_Mail('utf-8');

                            $mail->setFrom($config['username'], $this->_sessCommon->login['realName']) // 配置中的发信方
                                 ->addTo($AddressCardRow['eMail'], $AddressCardRow['cname']) // 数据表中的收信方
                                 ->setSubject($this->_iniCommon->mail->subject->invite) // 配置中的邮件主题
                                 ->setBodyText($inviteArgs['bodyText']) // 表单中的邮件正文
                                 ->send(new Zend_Mail_Transport_Smtp($config['name'], $config));

		    	    	    $this->_sessMember->message = $this->_iniMember->hint->inviteSuccess;
		    	        }
                    }

			        $this->_sessMember->invite[$cid] = true; // 标记cid已邀请
			    }
			}
		}
	}	
}
