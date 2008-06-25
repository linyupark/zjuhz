<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CorpIndustryLogic.php
 */


/**
 * 校友企业-tbl_corp_industry
 * 控制器附属层:模型层操作入口
 */
class CorpIndustryLogic extends BizInterlayer
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

    	$this->_load('CorpIndustryModel');
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
     * 插入/更新行业的企业数记录/资料
     * 
     * @param array $args
     * @return integer
     */
	public function insertOrUpdate($args)
    {
    	return ($this->selectIidCount($args['iid']) ? 
    	    $this->update($args) : $this->insert($args));
    }

    /**
     * 查找iid存在数量
     * 
     * @param integer $iid
     * @return integer
     */
	public function selectIidCount($iid)
    {
    	return $this->_CorpIndustryModel->selectFieldCount('iid', $iid);
    }

    /**
     * 查找所有行业分类的数量
     * 
     * @return array
     */
	public function selectPairs()
	{
		return $this->_CorpIndustryModel->selectPairs();
	}

    /**
     * 插入行业的企业数记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
    	return $this->_CorpIndustryModel->insert($args);
    }

    /**
     * 更新iid的企业数资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
    {
		return $this->_CorpIndustryModel->update($args);
    }
}
