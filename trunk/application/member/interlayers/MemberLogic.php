<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MemberLogic.php
 */


/**
 * 会员中心
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class MemberLogic extends MemberInterlayer
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
     * 更新表数据
     * $set = array('status' => xxx, 'editNick' => xxx, 'initAsk' => xxx);
     * 
     * @param array $input
     * @param string $uid
     * @return integer
     */
	public function extUpdate($input, $uid)
	{
		$this->_loadMdl('Ext');

		return $this->_mdlExt->update($input, $uid);
	}
}
