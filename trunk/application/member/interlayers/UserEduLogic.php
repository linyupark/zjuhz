<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:UserEduLogic.php
 */


/**
 * 校友中心-tbl_user_edu
 * 控制器附属层:模型层操作入口
 */
class UserEduLogic extends MemberInterlayer
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

    	$this->_load('UserEduModel');
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
     * 插入/更新教育的记录/资料
     * 
     * @param array $args
     * @return integer
     */
	public function insertOrUpdate($args)
    {
    	return ($this->selectEidCount($args['eid'], $args['uid']) ? 
    	    $this->update($args) : $this->insert($args));
    }

    /**
     * 查找eid存在数量
     * 
     * @param string $eid
     * @param integer $uid
     * @return integer
     */
	public function selectEidCount($eid, $uid)
    {
    	return $this->_UserEduModel->selectEidCount($eid, $uid);
    }

    /**
     * 查找uid对应的教育经历
     * 
     * @param integer $uid
     * @return array
     */
	public function selectUidAll($uid)
    {
    	return $this->_UserEduModel->selectUidAll($uid);
    }

    /**
     * 查询eid对应的教育详情
     * 
     * @param string $eid
     * @param integer $uid
     * @return array
     */
	public function selectEidRow($eid, $uid)
    {
		return $this->_UserEduModel->selectEidRow($eid, $uid);
    }

    /**
     * 插入教育记录
     * 
     * @param array $args
     * @return integer
     */
	public function insert($args)
    {
    	return $this->_UserEduModel->insert($args);
    }

    /**
     * 更新教育资料
     * 
     * @param array $args
     * @return integer
     */
	public function update($args)
    {
		return $this->_UserEduModel->update($args);
    }

    /**
     * 删除教育记录
     * 
     * @param array $args
     * @return integer
     */
	public function delete($args)
    {
		return $this->_UserEduModel->delete($args);
    }
}
