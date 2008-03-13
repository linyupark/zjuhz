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
			//接收
			$posts = $this->_request->getPost();
			//过滤
			$filters = array('*' => array('StringTrim','StringToLower'), 'rname' => 'StripTags');
			//验证 注：有时间改为验证扩展类
			$validators = array('uname' => new Zend_Validate_Regex('/^([a-z0-9_]){3,16}+$/i'), 
                                'pswd' => new Zend_Validate_StringLength(6,16), 
                                'repswd' => array('StringEquals','fields' => array('pswd', 'repswd')), 
                                'rname' => new Zend_Validate_StringLength(2,16),);

			$input = new Zend_Filter_Input($filters,$validators,$posts);
			if ($input->isValid('pswd'))
			{
  				echo "Field 'pswd' is OK\n";
			}
			else
			{
				echo 'pswd error';
			}

			exit;
			$post = $this->_request->getPost();

        	$user = array('userName' => $post['uname'],'passWord' => $post['pswd'],
        	              'realName' => $post['rname'],'nickName' => $post['rname'],
        	              'sex' => $post['sex'],'regIp' => Commons::getIp(),
        	              'ikey' => $post['ikey']);

			$userModel = new UserModel();
			$userModel->addUser($user);
		}
	}
}
