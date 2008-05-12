<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:SearchFilter.php
 */


/**
 * 你问我答
 * 控制器附属层:参数过滤操作
 * 纯安全处理(验证过滤) 返回安全字符(串)
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class SearchFilter extends HelpInterlayer
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
     * 搜索关键字词过滤
     * 
     * @param string $arg
     * @return string
     */
	public function keywords($arg)
	{
		$filterChain = new Zend_Filter();
		$filterChain->addFilter(new Zend_Filter_StringTrim())
		            ->addFilter(new Zend_Filter_StripTags())
                    ->addFilter(new Zend_Filter_StringToLower());

        // Filter the $arg
        return $filterChain->filter($arg);
	}
}
