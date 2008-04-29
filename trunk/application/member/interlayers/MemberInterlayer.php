<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MemberInterlayer.php
 */


/**
 * 位于控制层和模型层间的夹层,是控制器访问模型层唯一入口.
 * index.php <-> controllers {<-> interlayers(Filters|Logics|...)} <-> models * 
 */
abstract class MemberInterlayer
{
	/**
     * 会员模块配置对象
     *
     * @var object
     */
	protected $_iniMember = null;

	/**
     * AddressCardModel对象
     *
     * @var object
     */
	protected $_AddressCardModel = null;

	/**
     * AddressGroupModel对象
     *
     * @var object
     */
	protected $_AddressGroupModel = null;

	/**
     * UserContactModel对象
     *
     * @var object
     */
	protected $_UserContactModel = null;

	/**
     * UserExtModel对象
     *
     * @var object
     */
	protected $_UserExtModel = null;

	/**
     * UserModel对象
     *
     * @var object
     */
	protected $_UserModel = null;

    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	$this->_iniMember = Zend_Registry::get('iniMember');
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    }

    /**
     * 单件模式载入类
     * 
     * @param string $className
     * @return object or false
     */
	protected static function _getInstance($className)
    {
    	static $instances = array();

    	if (isset($instances[$className])) { return $instances[$className];	}

    	if (class_exists($className))
    	{
    		$instances[$className] = new $className();

    		return $instances[$className];
    	}

    	return false;
    }

    /**
     * for Logic
     * 
     * @return void
     */
    protected function _initLogic()
    {
    	self::_setDao();
    }

    /**
     * for Filter
     * 
     * @return void
     */
    protected function _initFilter()
    {
    }

    /**
     * 类实例
     * 
     * @param string $className
     * @return void
     */
	protected function _load($className)
    {
    	$thisName = "_{$className}";

    	if (!is_object($this->$thisName))
    	{
    		$this->$thisName = $this->_getInstance($className);
    	}
    }

    /**
     * 初始化数据库
     * 
     * @return void
     */
    private function _setDao()
    {
    	if (!Zend_Registry::isRegistered('dao'))
    	{
			$dao = Zend_Db::factory($this->_iniMember->db->default->adapter, 
			    $this->_iniMember->db->default->params->toArray());
			$dao->query('set names utf8');
			Zend_Db_Table::setDefaultAdapter($dao);
			Zend_Registry::set('dao', $dao);
    	}
    }
}
