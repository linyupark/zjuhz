<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:UserWorkLogic.php
 */


/**
 * 校友中心-tbl_user_work
 * 控制器附属层:模型层操作入口
 */
class UserWorkLogic extends MemberInterlayer
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

    	$this->_load('UserWorkModel');
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
     * 插入/更新工作的记录/资料
     * 
     * @param array $args
     * @return integer
     */
	public function insertOrUpdate($args)
    {
    	return ($this->selectWidCount($args['wid'], $args['uid']) ? 
    	    $this->update($args) : $this->insert($args));
    }

    /**
     * 查找wid存在数量
     * 
     * @param string $wid
     * @param integer $uid
     * @return integer
     */
	public function selectWidCount($wid, $uid)
    {
    	return $this->_UserWorkModel->selectWidCount($wid, $uid);
    }

    /**
     * 查找uid对应的工作经验
     * 
     * @param integer $uid
     * @return array
     */
	public function selectUidAll($uid)
    {
    	return $this->_UserWorkModel->selectUidAll($uid);
    }

    /**
     * 查询wid对应的工作详情
     * 
     * @param string $wid
     * @param integer $uid
     * @return array
     */
	public function selectWidRow($wid, $uid)
    {
		return $this->_UserWorkModel->selectWidRow($wid, $uid);
    }

    /**
     * 插入工作记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
    	return $this->_UserWorkModel->insert($args);
    }

    /**
     * 更新工作资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
    {
		return $this->_UserWorkModel->update($args);
    }

    /**
     * 删除工作记录
     * 
     * @param array $args
     * @return integer
     */
	public function delete($args)
    {
		return $this->_UserWorkModel->delete($args);
    }
}
