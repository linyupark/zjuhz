<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ManageFilter.php
 */


/**
 * 校友企业
 * 控制器附属层:参数过滤操作
 * 纯安全处理(验证过滤) 返回安全字符(串)
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class ManageFilter extends BizInterlayer
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
     * 企业管理-基础信息
     * 
     * @param array $args
     * @return boolean or array
     */
	public function basic($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array('*' => array('StripTags'));

    	// 设置验证规则
		$validators = array(
            'cid' => array(array('StringLength', '10', '10'), 'presence' => 'required'), 
		    'uid' => array(array('Int'), 'presence' => 'required'), 
            'industry' => array(
			    array('Between', '1', '99'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniCompany->hint->industryError)), 
            'property' => array(
			    array('Between', '1', '99'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniCompany->hint->propertyError)), 
		    'province' => array(
                array('Utf8Length', '2', '8'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->provinceError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->provinceError)), 
			'city' => array(
                array('Utf8Length', '2', '11'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->cityError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->cityError)), 
            'content' => array(
       	        array('Utf8Length', '50', '2000'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->introError,
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->introError))
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
		  		'industry' => $input->getUnescaped('industry'), 'property' => $input->getUnescaped('property'), 
		  		'province' => $input->getUnescaped('province'), 'city' => $input->getUnescaped('city'), 
		  		'intro' => $input->getUnescaped('content')
			);
		}

		return false;
	}

	/**
     * 企业管理-联系方式
     * 
     * @param array $args
     * @return boolean or array
     */
	public function contact($args)
	{
		// Zend_Validate_IsEmail
		Zend_Loader::loadFile('IsEmail.php');
		// Zend_Validate_IsUrl
		Zend_Loader::loadFile('IsUrl.php');
		// Zend_Validate_IsPhone
		Zend_Loader::loadFile('IsPhone.php');
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array('StringTrim', 'StripTags'),
	    	'postcode' => 'Digits'
    	);

    	// 设置验证规则
		$validators = array(
            'cid' => array(array('StringLength', '10', '10'), 'presence' => 'required'), 
		    'uid' => array(array('Int'), 'presence' => 'required'), 
            'phone' => array(
			    array('IsPhone'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_IsPhone::NOT_PHONE => $this->_iniCompany->hint->phoneError)),
            'fax' => array(
			    array('IsPhone'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_IsPhone::NOT_PHONE => $this->_iniCompany->hint->faxError)),
            'eMail' => array(
			    array('IsEmail'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_IsEmail::NOT_EMAIL => $this->_iniCompany->hint->emailInvalid)), 
            'url' => array(
			    array('IsUrl'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_IsUrl::NOT_URL => $this->_iniCompany->hint->urlError)), 
            'address' => array(
		        array('Utf8Length', '6', '80'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->addressError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->addressError)),
			'postcode' => array(
			    array('StringLength', '6', '6'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
               	    Zend_Validate_StringLength::TOO_SHORT => $this->_iniCompany->hint->postcodeError, 
                    Zend_Validate_StringLength::TOO_LONG => $this->_iniCompany->hint->postcodeError)), 
            'other' => array(
		        array('Utf8Length', '0', '50'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->otherInvalid, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->otherInvalid))
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
		    	'phone' => $input->getUnescaped('phone'), 'fax' => $input->getUnescaped('fax'), 
		    	'eMail' => $input->getUnescaped('eMail'), 'url' => $input->getUnescaped('url'), 
		    	'address' => $input->getUnescaped('address'), 'postcode' => $input->getUnescaped('postcode'), 
		    	'other' => $input->getUnescaped('other')
			);
		}

		return false;
	}

	/**
     * 企业管理-商务供求
     * 
     * @param array $args
     * @return boolean or array
     */
	public function biz($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array('StringTrim', 'StripTags'),
    	);

    	// 设置验证规则
		$validators = array(
            'cid' => array(array('StringLength', '10', '10'), 'presence' => 'required'), 
		    'uid' => array(array('Int'), 'presence' => 'required'), 
            'product' => array(
		        array('Utf8Length', '0', '1000'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->productError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->productError)),
            'job' => array(
		        array('Utf8Length', '0', '1000'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->jobError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->jobError)),
            'cooperate' => array(
		        array('Utf8Length', '0', '1000'), 'breakChainOnFailure' => true, 'allowEmpty' => true, 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->cooperateError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->cooperateError))
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
		    	'product' => $input->getUnescaped('product'), 'job' => $input->getUnescaped('job'), 
		    	'cooperate' => $input->getUnescaped('cooperate')
			);
		}

		return false;
	}
}
