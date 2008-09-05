<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MyFilter.php
 */


/**
 * 校友中心
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
     * 个人资料-基础信息
     * 
     * @param array $args
     * @return boolean or array
     */
	public function basic($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array('*' => array('StringTrim', 'StripTags'));

    	// 设置验证规则
		$validators = array(
            'year' => array(
			    array('Between', '1900', '2050'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniMember->hint->yearNotBetween)), 
               	    /*
            'college' => array(
			    array('Between', '1', '50'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniMember->hint->collegeNotBetween)), 
					*/
            'college' => array(
			    array('Between', '1', '50'),'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniMember->hint->collegeNotBetween)), 
            'major' => array(
                array('Utf8Length', '2', '30'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->majorError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->majorError)), 
		    'everName' => array('allowEmpty' => true), 
                  	/*
			'birthday' => array(
			    array('Date'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Date::NOT_YYYY_MM_DD => $this->_iniMember->hint->dateTimeError,
               	    Zend_Validate_Date::INVALID => $this->_iniMember->hint->dateTimeError)), 
					*/
            'birthday' => array(
			    array('Date'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_Date::NOT_YYYY_MM_DD => $this->_iniMember->hint->dateTimeError,
               	    Zend_Validate_Date::INVALID => $this->_iniMember->hint->dateTimeError)), 
            'hometown_a' => array('allowEmpty' => true), 
            'location_a' => array('allowEmpty' => true),  
            'location_p' => array('allowEmpty' => true),
            'location_c' => array('allowEmpty' => true),
            'hometown_p' => array('allowEmpty' => true),
            'hometown_c' => array('allowEmpty' => true),
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
			    'year' => $input->getUnescaped('year'), 'college' => $input->getUnescaped('college'), 
		  		'major' => $input->getUnescaped('major'), 'everName' => $input->getUnescaped('everName'), 
		  		'birthday' => $input->getUnescaped('birthday'), 'hometown_p' => $input->getUnescaped('hometown_p'), 
		  		'hometown_c' => $input->getUnescaped('hometown_c'), 'hometown_a' => $input->getUnescaped('hometown_a'), 
		  		'location_p' => $input->getUnescaped('location_p'), 'location_c' => $input->getUnescaped('location_c'), 
		  		'location_a' => $input->getUnescaped('location_a'), 'lastModi' =>  $input->getUnescaped('lastModi')
			);
		}

		return false;
	}

	/**
     * 个人资料-联络方式
     * 
     * @param array $args
     * @return boolean or array
     */
	public function contact($args)
	{
		// Zend_Validate_IsEmail
		Zend_Loader::loadFile('IsEmail.php');
		// Zend_Validate_IsMobile
		Zend_Loader::loadFile('IsMobile.php');
		// Zend_Validate_IsPhone
		Zend_Loader::loadFile('IsPhone.php');
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array('StringTrim', 'StripTags'),
            'mobile' => 'Digits',
	    	'qq' => 'Digits',
	    	'postcode' => 'Digits'
    	);

    	// 设置验证规则
		$validators = array(
            'mobile' => array(
			    array('IsMobile'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_IsMobile::NOT_MOBILE => $this->_iniMember->hint->mobileError)), 
            'phone' => array(
			    array('IsPhone'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_IsPhone::NOT_PHONE => $this->_iniMember->hint->phoneError)), 
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
            'other' => array(
		        array('Utf8Length', '0', '50'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->otherInvalid, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->otherInvalid)), 
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
			    'mobile' => $input->getUnescaped('mobile'), 'phone' => $input->getUnescaped('phone'), 
		    	'eMail' => $input->getUnescaped('eMail'), 'qq' => $input->getUnescaped('qq'), 
		    	'msn' => $input->getUnescaped('msn'), 'address' => $input->getUnescaped('address'), 
		    	'postcode' => $input->getUnescaped('postcode'),	'other' => $input->getUnescaped('other'), 
		    	'lastModi' => $input->getUnescaped('lastModi')
			);
		}

		return false;
	}

	/**
     * 个人资料-工作经验
     * 
     * @param array $args
     * @return boolean or array
     */
	public function work($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array('StringTrim', 'StripTags'),
		    'wid' => 'Alnum',
            'uid' => 'Digits',
	    	'industry' => 'Digits',
	    	'property' => 'Digits',
	    	'functional' => 'Digits'
    	);

    	// 设置验证规则
		$validators = array(
            'wid' => array(array('StringLength', '10', '10'), 'presence' => 'required'),
            'uid' => array(array('Int'), 'presence' => 'required'),
            'company' => array(
		        array('Utf8Length', '6', '50'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->companyError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->companyError)), 
            'startDate' => array(
			    array('Date'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Date::NOT_YYYY_MM_DD => $this->_iniMember->hint->dateTimeError,
               	    Zend_Validate_Date::INVALID => $this->_iniMember->hint->dateTimeError)), 
            'endDate' => array(
			    array('Date'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_Date::NOT_YYYY_MM_DD => $this->_iniMember->hint->dateTimeError,
               	    Zend_Validate_Date::INVALID => $this->_iniMember->hint->dateTimeError)), 
            'industry' => array(
			    array('Between', '1', '99'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniMember->hint->industryError)), 
            'property' => array(
			    array('Between', '1', '99'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniMember->hint->propertyError)), 
			'division' => array(
		        array('Utf8Length', '0', '30'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->divisionError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->divisionError)), 
            'functional' => array(
			    array('Between', '1', '99'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniMember->hint->functionalError)), 
            'job' => array(
		        array('Utf8Length', '0', '20'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->jobError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->jobError)), 
            'description' => array(
		        array('Utf8Length', '0', '2000'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->descriptionError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->descriptionError)), 
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
			    'wid' => $input->getUnescaped('wid'), 'uid' => $input->getUnescaped('uid'), 
		    	'company' => $input->getUnescaped('company'), 'startDate' => $input->getUnescaped('startDate'), 
		    	'endDate' => $input->getUnescaped('endDate'), 'industry' => $input->getUnescaped('industry'), 
		    	'property' => $input->getUnescaped('property'),	'division' => $input->getUnescaped('division'), 
		    	'functional' => $input->getUnescaped('functional'),	'job' => $input->getUnescaped('job'), 
		    	'description' => $input->getUnescaped('description'), 'lastModi' => $input->getUnescaped('lastModi')
			);
		}

		return false;
	}

	/**
     * 个人资料-删除工作
     * 
     * @param array $args
     * @return boolean or array
     */
	public function workdel($args)
	{
		// 设置过滤规则
		$filters = array(
		    'wid' => 'Alnum',
            'uid' => 'Digits'
    	);

    	// 设置验证规则
		$validators = array(
		    'wid' => array(array('StringLength', '10', '10'), 'presence' => 'required'),
			'uid' => array(array('Int'), 'presence' => 'required')
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		return (!$input->hasInvalid() && !$input->hasMissing() ? 
		    array('wid' => $input->getUnescaped('wid'), 'uid' => $input->getUnescaped('uid')) : false 
		);
	}

	/**
     * 个人资料-教育经历
     * 
     * @param array $args
     * @return boolean or array
     */
	public function edu($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array('StringTrim', 'StripTags'),
		    'eid' => 'Alnum',
            'uid' => 'Digits',
	    	'edulevel' => 'Digits'
    	);

    	// 设置验证规则
		$validators = array(
            'eid' => array(array('StringLength', '10', '10'), 'presence' => 'required'),
            'uid' => array(array('Int'), 'presence' => 'required'),
            'school' => array(
		        array('Utf8Length', '2', '50'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->schoolError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->schoolError)), 
            'startDate' => array(
			    array('Date'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Date::NOT_YYYY_MM_DD => $this->_iniMember->hint->dateTimeError,
               	    Zend_Validate_Date::INVALID => $this->_iniMember->hint->dateTimeError)), 
            'endDate' => array(
			    array('Date'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_Date::NOT_YYYY_MM_DD => $this->_iniMember->hint->dateTimeError,
               	    Zend_Validate_Date::INVALID => $this->_iniMember->hint->dateTimeError)), 
			'major' => array(
		        array('Utf8Length', '0', '30'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->majorError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->majorError)), 
            'edulevel' => array(
			    array('Between', '1', '99'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniMember->hint->edulevelError)), 
            'description' => array(
		        array('Utf8Length', '0', '2000'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->descriptionError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->descriptionError)), 
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
			    'eid' => $input->getUnescaped('eid'), 'uid' => $input->getUnescaped('uid'), 
		    	'school' => $input->getUnescaped('school'), 'startDate' => $input->getUnescaped('startDate'), 
		    	'endDate' => $input->getUnescaped('endDate'), 'major' => $input->getUnescaped('major'), 
		    	'edulevel' => $input->getUnescaped('edulevel'),	'description' => $input->getUnescaped('description'), 
		    	'lastModi' => $input->getUnescaped('lastModi')
			);
		}

		return false;
	}

	/**
     * 个人资料-删除教育
     * 
     * @param array $args
     * @return boolean or array
     */
	public function edudel($args)
	{
		// 设置过滤规则
		$filters = array(
		    'eid' => 'Alnum',
            'uid' => 'Digits'
    	);

    	// 设置验证规则
		$validators = array(
		    'eid' => array(array('StringLength', '10', '10'), 'presence' => 'required'),
			'uid' => array(array('Int'), 'presence' => 'required')
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		return (!$input->hasInvalid() && !$input->hasMissing() ? 
		    array('eid' => $input->getUnescaped('eid'), 'uid' => $input->getUnescaped('uid')) : false 
		);
	}

	/**
     * 通讯录-名片查找
     * 
     * @param array $args
     * @return boolean or array
     */
	public function find($args)
	{
		// 设置过滤规则
		$filters = array(
		    '*' => array('StringTrim', 'StripTags'),
            'uid' => 'Digits'
    	);

    	// 设置验证规则
		$validators = array(
		    'gid' => array('allowEmpty' => true),
			'field' => array('allowEmpty' => true),
			'wd' => array('allowEmpty' => true),
			'uid' => array('presence' => 'required')			
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
		    	'gid' => $input->getUnescaped('gid'), 'field' => $input->getUnescaped('field'),
		    	'wd' => $input->getUnescaped('wd'), 'uid' => $input->getUnescaped('uid')
			);
		}

		return false;
	}

	/**
     * 通讯录-名片新增/修改
     * 
     * @param array $args
     * @return boolean or array
     */
	public function card($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');
		// Zend_Validate_IsEmail
		Zend_Loader::loadFile('IsEmail.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array('StringTrim', 'StringToLower'),
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
     * 通讯录-组新增/修改
     * 
     * @param array $args
     * @return boolean or array
     */
	public function group($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array('*' => array('StringTrim', 'StripTags'));

    	// 设置验证规则
		$validators = array(
		    'gid' => array(array('StringLength', '5', '5'), 'presence' => 'required'),
			'uid' => array(array('Int'), 'presence' => 'required'),
			'gname' => array(array('Utf8Length', '1', '10'), 'presence' => 'required'), 
			'lastModi' => array('allowEmpty' => true)
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		return (!$input->hasInvalid() && !$input->hasMissing() ? 
		    array('gid' => $input->getUnescaped('gid'), 'uid' => $input->getUnescaped('uid'), 
		        'gname' => $input->getUnescaped('gname'), 'lastModi' => $input->getUnescaped('lastModi')) : false
		);
	}

	/**
     * 通讯录-组删除
     * 
     * @param array $args
     * @return boolean or array
     */
	public function groupdel($args)
	{
		// 设置过滤规则
		$filters = array('*' => array('StripTags'));

    	// 设置验证规则
		$validators = array(
		    'gid' => array(array('StringLength', '5', '5'), 'presence' => 'required'),
			'uid' => array(array('Int'), 'presence' => 'required')
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		return (!$input->hasInvalid() && !$input->hasMissing() ? 
		    array('gid' => $input->getUnescaped('gid'), 'uid' => $input->getUnescaped('uid')) : false 
		);
	}

	/**
     * 账号安全-修改密码
     * 
     * @param array $args
     * @return boolean or array
     */
	public function passwd($args)
	{
		// Zend_Validate_NotEquals
		Zend_Loader::loadFile('NotEquals.php');

		// 设置过滤规则
		//$filters = array('*' => array('StringTrim', 'StringToLower'));
		$filters = array('*' => array('StringTrim'));

    	// 设置验证规则
		$validators = array(
		    'uid' => array(array('Int'), 'presence' => 'required'),
          	'oldpswd' => array(
       	        array('StringLength', '6', '16'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->passwordError, 
              	    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->passwordError)), 
			'newpswd' => array(
       	        array('StringLength', '6', '16'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniMember->hint->passwordError, 
              	    Zend_Validate_StringLength::TOO_LONG => $this->_iniMember->hint->passwordError)),               	    
            'pswd' => array(
			    array('InArray', array($args['newpswd']), true), 'breakChainOnFailure' => true, 'presence' => 'required', 
			        'messages' => array(Zend_Validate_InArray::NOT_IN_ARRAY => $this->_iniMember->hint->passwordNotEqual)), 
            'scode' => array(
       	        array('NotEquals', md5($args['vcode'])), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_NotEquals::NOT_EQUALS => $this->_iniMember->hint->verifyError))  			        

        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
			    'uid' => $input->getUnescaped('uid'), 'oldpassword' => md5($input->getUnescaped('oldpswd')), 
		    	'password' => md5($input->getUnescaped('pswd'))
			);
		}

		return false;
	}

	/**
     * 通讯录-邀请好友
     * 
     * @param array $args
     * @return boolean or array
     */
	public function invite($args)
	{
		// Zend_Validate_IsEmail
		Zend_Loader::loadFile('IsEmail.php');

		// 设置过滤规则
		$filters = array();

    	// 设置验证规则
		$validators = array(
		    'cid' => array(array('StringLength', '10', '10'), 'presence' => 'required'), 
		    'eMail' => array(array('IsEmail'), 'presence' => 'required'), 
		    'bodyText' => array('presence' => 'required')
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		return (!$input->hasInvalid() && !$input->hasMissing() ? array(
		    'cid' => $input->getUnescaped('cid'), 'eMail' => $input->getUnescaped('eMail'), 
		    'bodyText' => $input->getUnescaped('bodyText')) : false 
		);
	}
}
