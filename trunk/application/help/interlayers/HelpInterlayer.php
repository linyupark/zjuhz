<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:HelpInterlayer.php
 */


/**
 * 位于控制层和模型层间的夹层,是控制器访问模型层唯一入口.
 * index.php <-> controllers {<-> interlayers(Filters|Logics|...)} <-> models * 
 */
abstract class HelpInterlayer
{
	/**
     * 问答模块配置对象
     *
     * @var object
     */
	protected $_iniHelp = null;

	/**
     * AskModel
     *
     * @var object
     */
	protected $_mdlAsk = null;

	/**
     * CollectionModel
     *
     * @var object
     */
	protected $_mdlCollection = null;

	/**
     * HelpModel
     *
     * @var object
     */
	protected $_mdlHelp = null;

	/**
     * LogModel
     *
     * @var object
     */
	protected $_mdlLog = null;

	/**
     * MyModel
     *
     * @var object
     */
	protected $_mdlMy = null;

	/**
     * QuestionModel
     *
     * @var object
     */
	protected $_mdlQuestion = null;

	/**
     * ReplyModel
     *
     * @var object
     */
	protected $_mdlReply = null;

	/**
     * SortModel
     *
     * @var object
     */
	protected $_mdlSort = null;

    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	$this->_iniHelp = Zend_Registry::get('iniHelp');
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
     * for Entry
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
    		$params['dbname'] = 'zjuhz_ask';
 
			$dao = Zend_Db::factory($adapter, $params);

			$dao->query('set names utf8');
			Zend_Db_Table::setDefaultAdapter($dao);
			Zend_Registry::set('dao', $dao);
    	}
    }
}
