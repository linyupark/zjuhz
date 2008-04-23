<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ReplyFilter.php
 */


/**
 * 你问我答
 * 控制器附属层:参数过滤操作
 * 纯安全处理(验证过滤) 返回安全字符(串)
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class ReplyFilter extends HelpInterlayer
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
     * 回答问题数据过滤
     * 
     * @param array $args
     * @return string to ajax or false or array
     */
	public function insert($args)
	{
		// 载入其关ZEND扩展 - ZF1.5版本需此
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array(
		        'StringTrim'),  
    	);

    	//*设置验证规则
		$validators = array(
		    'uid' => array(array('Int'), 'presence' => 'required'),
			'qid' => array(array('Int'), 'presence' => 'required'), 
			'offer' => array(array('Int'), 'presence' => 'required'), 
          	'content' => array(
       	        array('Utf8Length', '2', '3000'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniHelp->hint->reply->formatError,
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniHelp->hint->reply->formatError)),			
            'anonym' => array('presence' => 'required'),
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			//print_r($input->getMessages());exit;
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
			    'uid' => $input->getUnescaped('uid'), 'qid' => $input->getUnescaped('qid'), 
			    'content' => $input->getUnescaped('content'), 'anonym' => $input->getUnescaped('anonym'), 
			    'offer' => $input->getUnescaped('offer'), 
			);
		}

		return false;
	}
}
