<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AddressGroupLogic.php
 */


/**
 * 校友中心-通讯录-组
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class AddressGroupLogic extends MemberInterlayer
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

    	$this->_load('AddressGroupModel');
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
     * 插入/更新组的记录/资料
     * 
     * @param array $args
     * @return integer
     */
	public function insertOrUpdate($args)
    {    	
    	return ($this->selectGidCount($args['gid']) ? 
    	    $this->update($args, $args['gid']) : $this->insert($args));
    }

    /**
     * 查找gid存在数量
     * 
     * @param string $gid
     * @return integer
     */
	public function selectGidCount($gid)
    {
    	return $this->_AddressGroupModel->selectFieldCount('gid', $gid);
    }

    /**
     * 查找uid对应的名片组
     * 
     * @param integer $uid
     * @return array
     */
	public function selectUidAll($uid)
    {
    	return $this->_AddressGroupModel->selectUidAll($uid);
    }

    /**
     * 插入名片组记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
    	return $this->_AddressGroupModel->insert($args);
    }

    /**
     * 更新名片组资料
     * 
     * @param array $args
     * @param string $gid
     * @return integer
     */
	public function update($args, $gid)
    {
		return $this->_AddressGroupModel->update($args, $gid);
    }

    /**
     * 删除名片组记录
     * 
     * @param array $args
     * @return integer
     */
	public function delete($args)
    {
		return $this->_AddressGroupModel->delete($args);
    }
}
