<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AddressCardLogic.php
 */


/**
 * 会员中心-通讯录-名片
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class AddressCardLogic extends MemberInterlayer
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

    	$this->_load('AddressCardModel');
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
     * 名片资料写入或更新
     * 
     * @param array $args
     * @return integer
     */
	public function insertOrUpdate($args)
    {    	
    	return ($this->selectPrimaryExist($args['cid']) ? 
    	    $this->update($args, $args['cid']) : $this->insert($args)
    	);
    }

    /**
     * 主键是否存在
     * 
     * @param string $cid
     * @return integer
     */
	public function selectPrimaryExist($cid)
    {
    	return $this->_AddressCardModel->selectPrimaryExist($cid);
    }

    /**
     * 查询名片列表
     * 
     * @param string $gid
     * @param integer $uid
     * @return array
     */
	public function selectList($gid, $uid)
    {
		return $this->_AddressCardModel->selectList($gid, $uid);
    }

    /**
     * 查询记录数
     * 
     * @param string $gid
     * @param integer $uid
     * @return array
     */
	public function selectCount($gid, $uid)
    {
		return $this->_AddressCardModel->selectCount($gid, $uid);
    }

    /**
     * 查询详细记录
     * 
     * @param string $cid
     * @param integer $uid
     * @return array
     */
	public function selectDetail($cid, $uid)
    {
		return $this->_AddressCardModel->selectDetail($cid, $uid);
    }

    /**
     * 列表自定义查找/统计
     * 
     * @param string $type
     * @param array $args
     * @param string $limit
     * @return integer or array
     */
	public function selectFind($type, $args, $limit)
    {
    	$where  = "WHERE c.uid = {$args['uid']} AND c.gid = g.gid"; // 基础
    	$where .= (5 == strlen($args['gid']) ? " AND c.gid = '{$args['gid']}'" : ''); // 组范围
        $where .= (array_key_exists($args['field'], $this->_iniMember->find->addressCard->toArray()) && isset($args['wd']) 
			? " AND c.{$args['field']} LIKE '%{$args['wd']}%'" : ''); // 字段范围及关键字词

        return ('count' == $type ? $this->_AddressCardModel->selectFindCount($where) : 
            $this->_AddressCardModel->selectFindList($where, $limit)
        );
    }

    /**
     * 常规写入数据
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
    	return $this->_AddressCardModel->insert($args);
    }

    /**
     * 常规更新数据
     * 
     * @param array $args
     * @param string $cid
     * @return integer
     */
	public function update($args, $cid)
    {
		return $this->_AddressCardModel->update($args, $cid);
    }
}
