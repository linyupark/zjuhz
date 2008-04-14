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
     * MemberModel对象
     *
     * @var object
     */
	protected $_mdlMember = null;

	/**
     * UserModel对象
     *
     * @var object
     */
	protected $_mdlUser = null;

	/**
     * ExtModel对象
     *
     * @var object
     */
	protected $_mdlExt = null;

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
     * @return false | object
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
     * for Service
     * 
     * @return void
     */
    protected function _initService()
    {
    }

    /**
     * Model类实例
     * 
     * @param string $name
     * @return void
     */
	protected function _loadMdl($name)
    {
    	$thisName = "_mdl{$name}";
    	$objName  = "{$name}Model";

    	if (!isset($this->$thisName))
    	{
    		$this->$thisName = $this->_getInstance($objName);
    	}
    }

    /**
     * 初始化数据库访问类并注册
     * 
     * @return void
     */
    private function _setDao()
    {
    	if (!Zend_Registry::isRegistered('dao'))
    	{
    		/** Registry 数据库 */
			$dao = Zend_Db::factory($this->_iniMember->db->default->adapter, 
			                        $this->_iniMember->db->default->params->toArray());
			$dao->query('set names utf8');
			Zend_Db_Table::setDefaultAdapter($dao);
			Zend_Registry::set('dao', $dao);
    	}
    }
}
