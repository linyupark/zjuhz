<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : AccountController.php $
 * $Author : wangyumin $
 */


/**
 * 会员中心-帐号处理
 * 面向前后端:帐号创建 资料修改 修改修改等
 * 纯数据处理，不做页面层显示
 */
class AccountController extends Zend_Controller_Action
{
	/**
     * 配置文档对象
     *
     * @var object
     */
	private $_ini = null;

	/**
     * 公共SESSION对象
     *
     * @var array
     */
	private $_sessCommon = null;

	/**
     * 初始化
     * 
     * @return null
     */
	public function init()
	{
		//载入配置文档
		$this->_ini        = Zend_Registry::get('ini');

		//载入公共SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');

		//载入对应MODEL类
		Zend_Loader::loadClass('UserModel');

		//禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();

		return null;
	}

	/**
     * 注册提交
     * 
     * 点击提交按钮后数据验证及写库操作
     * 
     * @return string to ajax
     */
	public function registerAction()
	{
		if ($this->_request->isXmlHttpRequest())
		{
			//print_r($this->_getAllParams());
			$userName = $this->_request->getPost('uname');
			$passWord = $this->_request->getPost('pswd');
			$rePasswd = $this->_request->getPost('repswd');
			$realName = $this->_request->getPost('rname');
			$sex      = $this->_request->getPost('sex','S');
			$eMail    = $this->_request->getPost('email');
			$ikey     = $this->_request->getPost('ikey');
			$iuid     = $this->_request->getPost('iuid');			

			if (!self::chkVerifyCode($this->_sessCommon->verify,
			    $this->_request->getPost('vcode')))
			{
				echo $this->_ini->hint->verifyCode->checkError;
			}
			elseif (!self::chkUserName($userName))
			{
				echo $this->_ini->hint->userName->formatError;
			}
			elseif (!self::chkPasswd($passWord))
			{
				echo $this->_ini->hint->passWord->formatError;
			}
			elseif (!Valid::equal($passWord,$rePasswd))
			{
				echo $this->_ini->hint->rePasswd->notEqual;
			}
			elseif (!self::chkRealName($realName))
			{
				echo $this->_ini->hint->realName->formatError;
			}
			elseif (!self::chkEmail($eMail))
			{
				echo $this->_ini->hint->eMail->formatError;
			}
			else
			{
				$user = new UserModel();
				//$user->register(Filter::request($this->_getAllParams()));

				$invite = $user->getInviteDetail($ikey,$iuid);
				if (!$invite)
				{
					echo $this->_ini->hint->invite->ikeyNotExist;
				}
				elseif ($invite['status'] != 0)
				{
					echo $this->_ini->hint->invite->haveReg;
				}
				elseif ($user->isUserNameExist($userName))
				{
					echo $this->_ini->hint->userName->haveExist;
				}
				else
				{
					$regInfo = array('userName' => $userName, 'passWord' => $passWord, 'realName' => $realName, 
									 'nickName' => "zjuhz_$userName", 'sex' => $sex, 'regIp' => Commons::getIp(),
									 'iuid' => $iuid);
					$user->register($regInfo);
					
					//判断注册人填写的真实姓名与邀请人填写的是否相等
					//若不等状态为2(已注册待核实) 反之为可自由通行
					$regStatus = (($invite['realName'] != $realName) ? 2 : 1);
				}
				//Filter::request($this->_getAllParams())
			}
		}
	}

	/**
     * 检查登录帐号3-16位字母数字下划线
     * 
     * @param string $input
     * @return boolean
     */
	public static function chkUserName($input)
	{
		return ((!Valid::isAlphaNumUline($input) || !Valid::alphaNumLenRange($input,3,16)) ? false : true);
	}

	/**
     * 检查登录密码6-16位不含空格
     * 
     * @param string $input
     * @return boolean
     */
	public static function chkPasswd($input)
	{
		return ((!Valid::alphaNumLenRange($input,6,16) || !Valid::isFullIncluding($input,' ')) ? false : true);
	}

	/**
     * 检查真实姓名 2-16位，且不能含有数字和符号 中日英韩且不能混合
     * 
     * @param string $input
     * @return boolean
     */
	public static function chkRealName($input)
	{
		return ((!Valid::utf8NotMixed($input) || !Valid::utf8LenRange($input,2,16)) ? false : true);
	}

	/**
     * 检查EMAIL 6-50位
     * 
     * @param string $input
     * @return boolean
     */
	public static function chkEmail($input)
	{
		return ((!Valid::isEmail($input) || !Valid::alphaNumLenRange($input,6,50)) ? false : true);
	}

	/**
     * 检查附加码 4位
     * 
     * @param string $input1 SESSION内已加解的
     * @param string $input2 用户输入的原始数据
     * @return boolean
     */
	public static function chkVerifyCode($input1,$input2)
	{
		return ((!Valid::equal($input1,md5($input2)) || !Valid::alphaNumLenRange($input2,4,4)) ? false : true);
	}
}
