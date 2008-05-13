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
     * 写入/更新名片记录/资料
     * 
     * @param array $args
     * @return integer
     */
	public function insertOrUpdate($args)
    {
    	return ($this->selectCidCount($args['cid']) ? 
    	    $this->update($args, $args['cid']) : $this->insert($args)
    	);
    }

    /**
     * 查找cid存在数量
     * 
     * @param string $cid
     * @return integer
     */
	public function selectCidCount($cid)
    {
    	return $this->_AddressCardModel->selectFieldCount('cid', $cid);
    }

    /**
     * 查询cid对应的名片详情
     * 
     * @param string $cid
     * @param integer $uid
     * @return array
     */
	public function selectCidRow($cid, $uid)
    {
		return $this->_AddressCardModel->selectCidRow($cid, $uid);
    }

    /**
     * 查询gid对应的名片组
     * 
     * @param string $gid
     * @param integer $uid
     * @return array
     */
	public function selectGidAll($gid, $uid)
    {
		return $this->_AddressCardModel->selectGidAll($gid, $uid);
    }

    /**
     * 查询gid对应的记录数
     * 
     * @param string $gid
     * @param integer $uid
     * @return array
     */
	public function selectGidCount($gid, $uid)
    {
		return $this->_AddressCardModel->selectGidCount($gid, $uid);
    }

    /**
     * 名片列表自定义查找/统计
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
     * 插入名片记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
    	return $this->_AddressCardModel->insert($args);
    }

    /**
     * 更新名片资料
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
