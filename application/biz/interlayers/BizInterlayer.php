<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:BizInterlayer.php
 */


/**
 * 校友企业
 * 位于控制层和模型层间的夹层,是控制器访问模型层唯一入口.
 * index.php <-> controllers {<-> interlayers(Filters|Logics|...)} <-> models * 
 */
abstract class BizInterlayer
{
	/**
     * 企业模块配置
     *
     * @var object
     */
	protected $_iniCompany = null;

	/**
     * BaseModel
     * 
     * @var object
     */
	protected $_BaseModel = null;

	/**
     * CompanyBizModel
     * 
     * @var object
     */
	protected $_CompanyBizModel = null;

	/**
     * CompanyContactModel
     * 
     * @var object
     */
	protected $_CompanyContactModel = null;

	/**
     * CompanyLinkModel
     * 
     * @var object
     */
	protected $_CompanyLinkModel = null;

	/**
     * CompanyMsgModel
     * 
     * @var object
     */	
	protected $_CompanyMsgModel = null;

	/**
     * CorpCompanyModel
     * 
     * @var object
     */
	protected $_CorpCompanyModel = null;

	/**
     * CorpIndustryModel
     * 
     * @var object
     */
	protected $_CorpIndustryModel = null;

	/**
     * CorpModel
     * 
     * @var object
     */
	protected $_CorpModel = null;

    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
    	$this->_iniCompany = Zend_Registry::get('iniCompany');
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
     * for Logic
     * 
     * @return void
     */
    protected function _initLogic()
    {
    	$this->_setDao();
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
     * 数据库初始化
     * 
     * @return void
     */
    private function _setDao()
    {
    	if (!Zend_Registry::isRegistered('dao'))
    	{
    		$iniDb   = new Zend_Config_Ini('../../common/Ini/Db.ini');
    		$adapter = $iniDb->default->adapter;
    		$params  = $iniDb->default->params->toArray();
    		$params['dbname'] = 'zjuhz_corp';
 
			$dao = Zend_Db::factory($adapter, $params);

			$dao->query('set names utf8');
			Zend_Db_Table::setDefaultAdapter($dao);
			Zend_Registry::set('dao', $dao);
    	}
    }
}
