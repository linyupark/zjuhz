<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:RegisterFilter.php
 */


/**
 * 校友中心
 * 控制器附属层:参数过滤操作
 * 纯安全处理(验证过滤) 返回安全字符(串)
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class RegisterFilter extends MemberInterlayer
{
    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	parent::__construct();
    	//parent::_initFilter();
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
     * 账号注册数据过滤
     * 
     * @param array $args
     * @return boolean or array
     */
	public function register($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');
		// Zend_Validate_NotEquals
		Zend_Loader::loadFile('NotEquals.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array('StringTrim', 'StripTags', 'StringToLower'), 
	    	'ikey' => 'Alnum'
    	);

    	// 设置验证规则
		$validators = array(
          	'ikey' => array(
                array('StringLength', '10', '10'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->ikeyError, 
                    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->ikeyError)), 
		    'uname' => array(
		   	    array('Regex', '/^[a-z][a-z0-9_]{0,14}[a-z0-9]$/i'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
		   	        Zend_Validate_Regex::NOT_MATCH => $this->_iniMember->hint->usernameError)), 
          	'pswd' => array(
       	        array('StringLength', '6', '16'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->passwordError, 
              	    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->passwordError)), 
			'repswd' => array(
			    array('InArray', array($args['pswd']), true), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_InArray::NOT_IN_ARRAY => $this->_iniMember->hint->passwordNotEqual)), 
            'rname' => array(
                array('Utf8Length', '2', '6'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->realNameError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->realNameError)), 
            'sex' => array('presence' => 'required'), 
            'year' => array(
			    array('Between', '1900', '2050'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniMember->hint->yearNotBetween)), 
            'college' => array(
			    array('Between', '1', '50'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniMember->hint->collegeNotBetween)), 
            'major' => array(
                array('Utf8Length', '2', '30'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->majorError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->majorError)), 
            'ip' => array('presence' => 'required'), 
            'scode' => array(
       	        array('NotEquals', md5($args['vcode'])), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_NotEquals::NOT_EQUALS => $this->_iniMember->hint->verifyError))
        );

        /*$options = array(
            'notEmptyMessage' => $this->_iniMember->hint->notEmptyMessage, 
            'missingMessage' => $this->_iniMember->hint->missingMessage, 
        );*/

		$input = new Zend_Filter_Input($filters, $validators, $args); //, $options

		if ($input->hasInvalid() || $input->hasMissing())
		{
			//print_r($input->getMessages());exit;
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
		    	'username' => $input->getUnescaped('uname'), 'password' => $input->getUnescaped('pswd'), 
		  		'realName' => $input->getUnescaped('rname'), 'sex' => $input->getUnescaped('sex'), 
		  		'year' => $input->getUnescaped('year'), 'college' => $input->getUnescaped('college'), 
		  		'major' => $input->getUnescaped('major'), 'regIp' => $input->getUnescaped('ip'), 
		  		'ikey' => $input->getUnescaped('ikey')
			);
		}

		return false;
	}

	/**
     * 账号检测数据过滤
     * 
     * @param array $args
     * @return string or boolean
     */
	public function check($args)
	{
		// 设置过滤规则
		$filters = array('uname' => array('StringTrim', 'StringToLower'));

    	// 设置验证规则
		$validators = array(
		    'uname' => array(
		   	    array('Regex', '/^[a-z][a-z0-9_]{0,14}[a-z0-9]$/i'), 'breakChainOnFailure' => true, 
		   	    'presence' => 'required', 'messages' => array(
		   	        Zend_Validate_Regex::NOT_MATCH => $this->_iniMember->hint->usernameError))
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return $input->getUnescaped('uname');
		}

		return false;
	}

	/**
     * 显示班级过滤
     * 
     * @param string $cid
     * @return boolean
     */
	public function classes($cid)
	{
		$chain = new Zend_Validate();
        $chain->addValidator(new Zend_Validate_StringLength(10, 10))
              ->addValidator(new Zend_Validate_Alnum());

        return ($chain->isValid($cid) ? true : false);
	}
}
