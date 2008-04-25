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
     * 会员注册数据过滤
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
		$filters = array(
		    '*' => array(
		        'StringTrim', 'StripTags'), 
    	);

    	// 设置验证规则
		$validators = array(
		    'everName' => array('allowEmpty' => true), 
			'birthday' => array(
			    array('Date'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Date::NOT_YYYY_MM_DD => $this->_iniMember->hint->datetime->formatError)), 
            'hometown_p' => array(
                array('Utf8Length', '2','15'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->pcas->formatError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->pcas->formatError)), 
            'location_p' => array(
                array('Utf8Length', '2','15'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
                    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniMember->hint->pcas->formatError, 
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniMember->hint->pcas->formatError)),  
            'hometown_c' => array('presence' => 'required'),
            'hometown_a' => array('presence' => 'required'),
            'location_c' => array('presence' => 'required'),
            'location_a' => array('presence' => 'required'),   
            'lastModi' => array('presence' => 'required'),
        );

		$input = new Zend_Filter_Input($filters, $validators, $args); //, $options

		if ($input->hasInvalid() || $input->hasMissing())
		{
			//print_r($input->getMessages());exit;
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
		    	'everName' => $input->getUnescaped('everName'), 'birthday' => $input->getUnescaped('birthday'), 
		    	'hometown_p' => $input->getUnescaped('hometown_p'), 'hometown_c' => $input->getUnescaped('hometown_c'), 
		    	'hometown_a' => $input->getUnescaped('hometown_a'), 'location_p' => $input->getUnescaped('location_p'), 
		    	'location_c' => $input->getUnescaped('location_c'), 'location_a' => $input->getUnescaped('location_a'), 
		    	'lastModi' =>  $input->getUnescaped('lastModi'),
			);
		}

		return false;
	}
}
