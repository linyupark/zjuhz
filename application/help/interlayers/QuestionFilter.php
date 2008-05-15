<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:QuestionFilter.php
 */


/**
 * 校友互助
 * 控制器附属层:参数过滤操作
 * 纯安全处理(验证过滤) 返回安全字符(串)
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class QuestionFilter extends HelpInterlayer
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
     * 发布问题数据过滤
     * 
     * @param array $args
     * @return boolean or array
     */
	public function insert($args)
	{
		// Zend_Validate_Utf8Length
		Zend_Loader::loadFile('Utf8Length.php');
		// Zend_Validate_NotEquals
		Zend_Loader::loadFile('NotEquals.php');

		// 设置过滤规则
		$filters = array(
		    '*' => array('StringTrim'), 
	    	'title' => 'StripTags',
	    	'tags' => 'StripTags'
    	);

    	// 设置验证规则
		$validators = array(
		    'uid' => array(array('Int'), 'presence' => 'required'),
		    'title' => array(
		   	    array('Utf8Length', '4', '25'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
		   	        Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniHelp->hint->titleError,
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniHelp->hint->titleError)), 
          	'content' => array(
       	        array('Utf8Length', '2', '3000'), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_Utf8Length::TOO_SHORT => $this->_iniHelp->hint->contentError,
                    Zend_Validate_Utf8Length::TOO_LONG => $this->_iniHelp->hint->contentError)),
            'tags' => array('allowEmpty' => true),
			'sortId' => array(
			    array('Between', '2', '999'),'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniHelp->hint->sortError)),
			'offer' => array(
			    array('Between', '0', $args['point']), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
               	    Zend_Validate_Between::NOT_BETWEEN => $this->_iniHelp->hint->offerNotBtw)),
            'anonym' => array('presence' => 'required'), 
            'scode' => array(
       	        array('NotEquals', md5($args['vcode'])), 'breakChainOnFailure' => true, 'presence' => 'required', 'messages' => array(
              	    Zend_Validate_NotEquals::NOT_EQUALS => $this->_iniHelp->hint->verifyError)) 
        );

		$input = new Zend_Filter_Input($filters, $validators, $args);

		if ($input->hasInvalid() || $input->hasMissing())
		{
			foreach ($input->getMessages() as $message) { foreach ($message as $msg) { echo $msg; } exit; }
		}
		else
		{
			return array(
			    'uid' => $input->getUnescaped('uid'), 'title' => $input->getUnescaped('title'), 
			    'content' => $input->getUnescaped('content'), 'tags' => $input->getUnescaped('tags'), 
			    'sid' => $input->getUnescaped('sortId'), 'offer' => $input->getUnescaped('offer'), 
			    'anonym' => $input->getUnescaped('anonym')
			);
		}

		return false;
	}

	/**
     * 采纳答案数据过滤
     * 
     * @param array $args
     * @return array
     */
	public function accept($args)
	{
		return array(
		    'qid' => (int)$args['qid'], 
		    'rid' => (int)$args['rid'], 
		    'quid' => (int)$args['quid'], 
		    'ruid' => (int)$args['ruid']
		);
	}
}
