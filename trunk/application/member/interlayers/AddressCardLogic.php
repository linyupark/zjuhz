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
     * @return object
     */
	public function insertOrUpdate($args)
    {    	
    	return ($this->selectPrimaryExist($args['cid']) ? 
    	    $this->update($args, $args['cid']) : $this->insert($args)
    	);
    }

    /**
     * 主键存在与否
     * 
     * @param string $cid
     * @return integer
     */
	public function selectPrimaryExist($cid)
    {
    	return $this->_AddressCardModel->selectPrimaryExist($cid);
    }

    /**
     * 查询列表
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
     * 查询详细
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
     * 查询列表
     * 
     * @param string $type
     * @param char $gid
     * @param integer $uid
     * @param string $field
     * @param string $wd
     * @param string $limit
     * @return integer or array
     */
	public function selectFindList($type, $gid, $uid, $field, $wd, $limit)
    {
    	$where = "WHERE c.uid = {$uid} AND c.gid = g.gid"; // 基础
    	$where .= (strlen($gid) === 5 ? " AND c.gid = '{$gid}'" : ''); // 组

    	$fieldList = array('cname'=>'cname', 'mobile', 'eMail', 'qq', 'msn', 'address', 'memo');
    	if (array_search($field, $fieldList) && isset($wd))
    	{
    		$where .= " AND c.{$field} LIKE '%{$wd}%'";
    	}

    	if ($type == 'count')
    	{
    		return $this->_AddressCardModel->selectCountList($where);
    	}

    	return $this->_AddressCardModel->selectFindList($where, $limit);
    }

    /**
     * 写入名片数据
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
    	return $this->_AddressCardModel->insert($args);
    }

    /**
     * 更新名片数据
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
