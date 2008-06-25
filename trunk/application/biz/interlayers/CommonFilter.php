<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CommonFilter.php
 */


/**
 * 校友企业
 * 控制器附属层:参数过滤操作
 * 纯安全处理(验证过滤) 返回安全字符(串)
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class CommonFilter extends CompanyInterlayer
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
     * 企业编号过滤
     * 
     * @param string $cid
     * @return string
     */
	public static function cid($cid)
	{
		$filterChain = new Zend_Filter();
		$filterChain->addFilter(new Zend_Filter_StripTags());

        // Filter the $arg
        return $filterChain->filter($cid);
	}

	/**
     * 分类编号过滤
     * 
     * @param integer $iid
     * @return string
     */
	public static function iid($iid)
	{
		$filterChain = new Zend_Filter();
		$filterChain->addFilter(new Zend_Filter_StripTags())
		            ->addFilter(new Zend_Filter_Int());

        // Filter the $arg
        return $filterChain->filter($iid);
	}
}
