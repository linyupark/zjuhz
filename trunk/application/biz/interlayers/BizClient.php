<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:BizClient.php
 */


/**
 * 校友企业_XML-RPC客户端
 * 控制器附属层:数据库操作入口
 * 介于控制器和模型之间,是控制器访问模型的唯一入口
 */
class BizClient extends BizInterlayer
{
	/**
     * 校友中心RPC调用接口
     *
     * @var object
     */
	private $_rpcMember = null;

    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	parent::__construct();

		$this->_rpcMember = new Zend_XmlRpc_Client('http://xmlrpc/MemberServer.php');
		//print_r($this->_rpcMember->call('system.listMethods',array()));exit;
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
}
