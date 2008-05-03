<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AddressGroupLogic.php
 */


/**
 * 会员中心-通讯录-组
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
     * 组资料写入或更新
     * 
     * @param array $args
     * @return integer
     */
	public function insertOrUpdate($args)
    {    	
    	return ($this->selectPrimaryExist($args['gid']) ? 
    	    $this->update($args, $args['gid']) : $this->insert($args)
    	);
    }

    /**
     * 主键是否存在
     * 
     * @param string $gid
     * @return integer
     */
	public function selectPrimaryExist($gid)
    {
    	return $this->_AddressGroupModel->selectPrimaryExist($gid);
    }

    /**
     * 查询组列表
     * 
     * @param integer $uid
     * @return array
     */
	public function selectList($uid)
    {
    	return $this->_AddressGroupModel->selectList($uid);
    }

    /**
     * 写入组数据
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
    	return $this->_AddressGroupModel->insert($args);
    }

    /**
     * 更新组数据
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
     * 删除组记录
     * 
     * @param array $args
     * @return integer
     */
	public function delete($args)
    {
		return $this->_AddressGroupModel->delete($args);
    }
}
