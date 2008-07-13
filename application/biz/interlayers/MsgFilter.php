<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MsgFilter.php
 */


/**
 * 校友企业
 * 控制器附属层:参数过滤操作
 * 纯安全处理(验证过滤) 返回安全字符(串)
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class MsgFilter extends BizInterlayer
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
     * 企业留言簿-发表留言
     * 
     * @param array $args
     * @return boolean or array
     */
	public function insert($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array(
		    'cid' => 'Alnum',
            'uid' => 'Digits',
		    'message' => array('StripTags')
		);

    	// 设置验证规则
		$validators = array(
            'cid' => array(array('StringLength', '10', '10'), 'presence' => 'required'), 
		    'uid' => array(array('Int'), 'presence' => 'required'), 
            'message' => array(
       	        array('Utf8Length', '2', '200'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->messageError,
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->messageError))
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
		  		'message' => $input->getUnescaped('message')
			);
		}

		return false;
	}

	/**
     * 企业留言簿-回复留言
     * 
     * @param array $args
     * @return boolean or array
     */
	public function reply($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array(		    
		    'mid' => 'Digits',
            'cid' => 'Alnum',
            'reply' => array('StripTags'),
		);

    	// 设置验证规则
		$validators = array(
		    'mid' => array(array('Int'), 'presence' => 'required'), 
		    'cid' => array(array('StringLength', '10', '10'), 'presence' => 'required'),
            'reply' => array(
       	        array('Utf8Length', '2', '200'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniCompany->hint->replyError,
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniCompany->hint->replyError))
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
			    'mid' => $input->getUnescaped('mid'), 'cid' => $input->getUnescaped('cid'), 
		  		'reply' => $input->getUnescaped('reply')
			);
		}

		return false;
	}

	/**
     * 企业留言簿-删除留言
     * 
     * @param array $args
     * @return boolean or array
     */
	public function delete($args)
	{
		// 设置过滤规则
		$filters = array(
		    'mid' => 'Digits',
            'cid' => 'Alnum'
    	);

    	// 设置验证规则
		$validators = array(
		    'mid' => array(array('Int'), 'presence' => 'required'), 
		    'cid' => array(array('StringLength', '10', '10'), 'presence' => 'required')
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		return ($input->hasInvalid() || $input->hasMissing() ? false : 
		    array('mid' => $input->getUnescaped('mid'), 'cid' => $input->getUnescaped('cid'))
		);
	}
}
