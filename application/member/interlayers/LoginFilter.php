<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:LoginFilter.php
 */


/**
 * 会员中心
 * 控制器附属层:参数过滤操作
 * 纯安全处理(验证过滤) 返回安全字符(串)
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class LoginFilter extends MemberInterlayer
{
    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	parent::__construct();
    	parent::_initFilter();
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	parent::__destruct();
    }

    /**
     * 类实例
     * 
     * @return object
     */
	public static function init()
    {
    	return parent::_getInstance(__CLASS__);
    }

	/**
     * 会员登录数据过滤
     * 
     * @param array $input
     * @return string to ajax or false or array
     */
	public function login($input)
	{
		// 设置过滤规则
		$filters = array(
		    '*' => array(
		        'StringTrim', 'StringToLower'), 
    	);

    	// 设置验证规则
		$validators = array(
		    'uname' => array(
		   	    array('Regex', '/^([a-z0-9_]){3,16}+$/i'), 'breakChainOnFailure' => true, 'messages' => array(
		   	        Zend_Validate_Regex::NOT_MATCH => $this->_iniMember->hint->userName->formatError, )),  
          	'pswd' => array(
       	        array('StringLength', '6', '16'), 'breakChainOnFailure' => true, 'messages' => array(
              	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->passWord->formatError, 
              	    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->passWord->formatError, )), 
        );

        $options = array(
            'notEmptyMessage' => $this->_iniMember->hint->notEmptyMessage, 
			'missingMessage' => $this->_iniMember->hint->missingMessage, 
        );

		$input = new Zend_Filter_Input($filters, $validators, $input, $options);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			//print_r($input->getMessages());exit;
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
		    	'userName' => $input->getUnescaped('uname'), 'passWord' => $input->getUnescaped('pswd'), 
		  		'lastIp' => Commons::getIp(), 
			);
		}

		return false;
	}
}
