<?php

/**
 * @category   zjuhz.com
 * @package    company
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CorpIndustryModel.php
 */


/**
 * 校友企业-tbl_corp_industry
 * 表级操作类,含单表读/写/改等方法
 */
class CorpIndustryModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_corp_industry';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'iid';

    /**
     * 数据表访问
     * @var object
     */
    protected $_dao = null;

    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_dao = Zend_Registry::get('dao');
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	$this->_dao->closeConnection();
    }

    /**
     * 查找字段存在数量
     * 
     * @param string $field
     * @param mixed $value
     * @return integer
     */
	public function selectFieldCount($field, $value)
    {
    	return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) FROM {$this->_name} 
    	    WHERE {$field} = :field;", array('field' => $value)
    	);
    }

    /**
     * 查找所有行业分类的数量
     * 
     * @return array
     */
	public function selectPairs()
    {
    	return $this->_dao->fetchPairs("SELECT * FROM {$this->_name} ORDER BY count DESC;", array());
    }

    /**
     * 插入行业的企业数记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
		return $this->_dao->insert($this->_name, $args);
    }

    /**
     * 更新行业的企业数资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
    {
		return $this->_dao->update($this->_name, $args, 
		    $this->_dao->quoteInto("{$this->_primary} = ?", $args['iid'])
		);
    }
}
