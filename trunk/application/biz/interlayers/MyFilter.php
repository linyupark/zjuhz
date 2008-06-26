<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MyFilter.php
 */


/**
 * 校友企业
 * 控制器附属层:参数过滤操作
 * 纯安全处理(验证过滤) 返回安全字符(串)
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class MyFilter extends BizInterlayer
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
     * 我的企业-申请加入
     * 
     * @param array $args
     * @return boolean or array
     */
	public function join($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');
		// Zend_Validate_IsPhone
		Zend_Loader::loadFile('IsPhone.php');

		// 设置过滤规则
		$filters = array('*' => array('StripTags'));

    	// 设置验证规则
		$validators = array(
            'cid' => array(array('StringLength', '10', '10'), 'presence' => 'required'), 
		    'uid' => array(array('Int'), 'presence' => 'required'), 
            'name' => array(
                array('Utf8Length', '6', '30'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->companyError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->companyError)), 
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
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->introError)), 
            'phone' => array(
			    array('IsPhone'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_IsPhone::NOT_PHONE => $this->_iniCompany->hint->phoneError))
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
		  		'name' => $input->getUnescaped('name'), 'industry' => $input->getUnescaped('industry'), 
		  		'property' => $input->getUnescaped('property'), 'province' => $input->getUnescaped('province'), 
		  		'city' => $input->getUnescaped('city'), 'intro' => $input->getUnescaped('content'), 
		  		'phone' => $input->getUnescaped('phone')
		  		
			);
		}

		return false;
	}
}
