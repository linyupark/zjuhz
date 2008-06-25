<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CompanyLinkLogic.php
 */


/**
 * 校友企业-tbl_corp_company_link
 * 控制器附属层:模型层操作入口
 */
class CompanyLinkLogic extends BizInterlayer
{
    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	parent::__construct();
    	parent::_initLogic();

    	$this->_load('CompanyLinkModel');
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
}
