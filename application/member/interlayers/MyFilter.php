<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MyFilter.php
 */


/**
 * 会员中心
 * 控制器附属层:参数过滤操作
 * 纯安全处理(验证过滤) 返回安全字符(串)
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class MyFilter extends MemberInterlayer
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
     * 基本信息
     * 
     * @param array $args
     * @return string to ajax or false or array
     */
	public function basic($args)
	{
		// 载入相关ZEND扩展 - ZF1.5版本需此
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array('*' => array('StringTrim', 'StripTags'));

    	// 设置验证规则
		$validators = array(
		    'everName' => array('allowEmpty' => true), 
			'birthday' => array(
			    array('Date'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Date::NOT_YYYY_MM_DD => $this->_iniMember->hint->dateTimeError)), 
            'hometown_a' => array(
                array('Utf8Length', '2', '16'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->pcasError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->pcasError)), 
            'location_a' => array(
                array('Utf8Length', '2', '16'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->pcasError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->pcasError)),  
            'location_p' => array('presence' => 'required'),
            'location_c' => array('presence' => 'required'),
            'hometown_p' => array('presence' => 'required'),
            'hometown_c' => array('presence' => 'required'),
            'lastModi' => array('presence' => 'required')
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
		    	'everName' => $input->getUnescaped('everName'), 'birthday' => $input->getUnescaped('birthday'), 
		    	'hometown_p' => $input->getUnescaped('hometown_p'), 'hometown_c' => $input->getUnescaped('hometown_c'), 
		    	'hometown_a' => $input->getUnescaped('hometown_a'), 'location_p' => $input->getUnescaped('location_p'), 
		    	'location_c' => $input->getUnescaped('location_c'), 'location_a' => $input->getUnescaped('location_a'), 
		    	'lastModi' =>  $input->getUnescaped('lastModi')
			);
		}

		return false;
	}

	/**
     * 联络方式
     * 
     * @param array $args
     * @return string to ajax or false or array
     */
	public function contact($args)
	{
		// 载入相关ZEND扩展 - ZF1.5版本需此
		// Zend_Validate_IsEmail
		Zend_Loader::loadFile('IsEmail.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array(
		        'StringTrim', 'StringToLower'),
            'mobile' => 'Digits',		        
	    	'qq' => 'Digits',
	    	'postcode' => 'Digits'
    	);

    	// 设置验证规则
		$validators = array(
            'mobile' => array(
			    array('StringLength', '11', '11'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->mobileError, 
                    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->mobileError)), 
            'eMail' => array(
			    array('IsEmail'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_IsEmail::NOT_EMAIL => $this->_iniMember->hint->emailInvalid)), 
            'qq' => array(
			    array('StringLength', '5', '15'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->qqError, 
                    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->qqError)), 
            'msn' => array(
			    array('IsEmail'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_IsEmail::NOT_EMAIL => $this->_iniMember->hint->msnInvalid)), 
            'address' => array('allowEmpty' => true), 
			'postcode' => array(
			    array('StringLength', '6', '6'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->postcodeError, 
                    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->postcodeError)), 
            'other' => array('allowEmpty' => true), 
            'lastModi' => array('presence' => 'required')
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
		    	'mobile' => $input->getUnescaped('mobile'), 'eMail' => $input->getUnescaped('eMail'), 
		    	'qq' => $input->getUnescaped('qq'), 'msn' => $input->getUnescaped('msn'), 
		    	'address' => $input->getUnescaped('address'), 'postcode' => $input->getUnescaped('postcode'),	
		    	'other' => $input->getUnescaped('other'), 'lastModi' => $input->getUnescaped('lastModi')		    	
			);
		}

		return false;
	}

	/**
     * 通讯录-名片
     * 
     * @param array $args
     * @return string to ajax or false or array
     */
	public function card($args)
	{
		// 载入相关ZEND扩展 - ZF1.5版本需此
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');
		// Zend_Validate_IsEmail
		Zend_Loader::loadFile('IsEmail.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array(
		        'StringTrim', 'StringToLower'),
            'mobile' => 'Digits',		        
	    	'qq' => 'Digits',
	    	'postcode' => 'Digits'
    	);

    	// 设置验证规则
		$validators = array(
		    'cid' => array(array('StringLength', '10', '10'), 'presence' => 'required'),
		    'uid' => array(array('Int'), 'presence' => 'required'),
		    'cname' => array(
		        array('Utf8Length', '2', '6'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->nameError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->nameError)), 
			'gid' => array(
			    array('StringLength', '5', '5'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->gidNotSelect, 
                    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->gidNotSelect)), 
            'mobile' => array(
			    array('StringLength', '11', '11'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->mobileError, 
                    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->mobileError)), 
            'eMail' =>array(
			    array('IsEmail'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_IsEmail::NOT_EMAIL => $this->_iniMember->hint->emailInvalid)), 
            'qq' => array(
			    array('StringLength', '5', '15'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->qqError, 
                    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->qqError)), 
            'msn' => array(
			    array('IsEmail'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_IsEmail::NOT_EMAIL => $this->_iniMember->hint->msnInvalid)), 
            'address' => array('allowEmpty' => true), 
			'postcode' => array(
			    array('StringLength', '6', '6'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->postcodeError, 
                    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->postcodeError)), 
            'memo' => array(
		        array('Utf8Length', '0', '200'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->memoInvalid, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->memoInvalid)),
            'lastModi' => array('presence' => 'required')
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
		    	'cid' => $input->getUnescaped('cid'), 'uid' => $input->getUnescaped('uid'), 
		    	'cname' => $input->getUnescaped('cname'), 'gid' => $input->getUnescaped('gid'), 
		    	'mobile' => $input->getUnescaped('mobile'),	'eMail' => $input->getUnescaped('eMail'), 
		    	'qq' => $input->getUnescaped('qq'),	'msn' => $input->getUnescaped('msn'), 
		    	'address' => $input->getUnescaped('address'), 'postcode' => $input->getUnescaped('postcode'), 
		    	'memo' => $input->getUnescaped('memo'),	'lastModi' => $input->getUnescaped('lastModi')
			);
		}

		return false;
	}

	/**
     * 通讯录-组
     * 
     * @param array $args
     * @return string to ajax or false or array
     */
	public function group($args)
	{
		// 载入相关ZEND扩展 - ZF1.5版本需此
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array('*' => array('StringTrim', 'StripTags'));

    	// 设置验证规则
		$validators = array(
		    'gid' => array(array('StringLength', '5', '5'), 'presence' => 'required'),
			'uid' => array(array('Int'), 'presence' => 'required'),
            'gname' => array(array('Utf8Length', '1', '10'), 'breakChainOnFailure' => true, 'presence' => 'required'),
            'lastModi' => array('allowEmpty' => true)
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
		    	'gid' => $input->getUnescaped('gid'), 'uid' => $input->getUnescaped('uid'), 
		    	'gname' => $input->getUnescaped('gname'), 'lastModi' => $input->getUnescaped('lastModi')
			);
		}

		return false;
	}

	/**
     * 通讯录-邀请
     * 
     * @param array $args
     * @return string to ajax or false or array
     */
	public function invite($args)
	{
		// 设置过滤规则
		$filters = array('*' => array('StringTrim', 'StripTags'));

    	// 设置验证规则
		$validators = array(
		    'cid' => array(array('StringLength', '10', '10'), 'presence' => 'required'),
			'status' => array(array('Int'), 'presence' => 'required'),
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
		    	'cid' => $input->getUnescaped('cid'), 'status' => $input->getUnescaped('status')
			);
		}

		return false;
	}

	/**
     * 通讯录-删除组
     * 
     * @param array $args
     * @return string to ajax or false or array
     */
	public function groupdel($args)
	{
		return array(
		    'gid' => $args['gid'],
		    'uid' => $args['uid']
		);
	}
}
