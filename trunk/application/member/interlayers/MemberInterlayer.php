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
     * 会员模块配置
     *
     * @var object
     */
	protected $_iniMember = null;

	/**
     * AddressCardModel
     *
     * @var object
     */
	protected $_AddressCardModel = null;

	/**
     * AddressGroupModel
     *
     * @var object
     */
	protected $_AddressGroupModel = null;

	/**
     * UserContactModel
     *
     * @var object
     */
	protected $_UserContactModel = null;

	/**
     * UserExtModel
     *
     * @var object
     */
	protected $_UserExtModel = null;

	/**
     * UserModel
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
     * 单件模式初始化类
     * 
     * @param string $className
     * @return object or boolean
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
     * 单件模式初始化属性
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
     * 数据库初始化
     * 
     * @return void
     */
    private function _setDao()
    {
    	if (!Zend_Registry::isRegistered('dao'))
    	{
    		$iniDb   = Zend_Registry::get('iniDb');
    		$adapter = $iniDb->default->adapter;
    		$params  = $iniDb->default->params->toArray();
    		$params['dbname'] = 'zjuhz_user';
 
			$dao = Zend_Db::factory($adapter, $params);

			$dao->query('set names utf8');
			Zend_Db_Table::setDefaultAdapter($dao);
			Zend_Registry::set('dao', $dao);
    	}
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
}
