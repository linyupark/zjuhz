<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CacheLogic.php
 */


/**
 * 校友企业-缓存
 * 控制器附属层:模型层操作入口
 */
class CacheLogic extends BizInterlayer
{
	/**
     * Zend_Cache
     * 
     * @var object
     */
	private $_cache = null;

	/**
     * diy(default) options
     * 
     * @var array
     */
    private static $_options = array(
        /* backend options */
        'cache_dir' => USER_CACHE, // from bootstarp
        /* frontend options */
        'lifeTime' => null, 
        'automatic_serialization' => true
    );

	/**
     * hopper it(即从传入数组中只保留以下键值)
     * 
     * @var array
     */
    private $_hoppers = array();

	/**
     * frontendOptions
     * 
     * @var array
     */
    private $_frontendOptions = array();

	/**
     * backendOptions
     * 
     * @var array
     */
    private $_backendOptions = array();

	/**
     * methods
     * 
     * @var array
     */
    private $_methods = array();

    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
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
     * 类实例
     * 
     * @return object
     */
	public static function init()
    {
    	return parent::_getInstance(__CLASS__);
    }

    /**
     * 自定义参数设置
     * 
     * @return void
     */
	public static function setOptions($key, $value)
    {
    	if (array_key_exists($key, self::$_options))
    	{
    		self::$_options[$key] = $value;
    	}
    }

    /**
     * 前端参数设置
     * 
     * @return void
     */
	private function _setFrontendOptions()
    {
    	$this->_frontendOptions = array(
    	    'lifeTime' => self::$_options['lifeTime'], 
    	    'automatic_serialization' => self::$_options['automatic_serialization']
    	);
    }

    /**
     * 后端参数设置
     * 
     * @return void
     */
	private function _setBackendOptions()
    {
    	$this->_backendOptions = array(
    	    'cache_dir' => self::$_options['cache_dir']
    	);
    }

    /**
     * 数组漏斗设置
     * 
     * @param array $data
     * @param string $name
     * @return void
     */
	private function _setHoppers($data, $name)
    {
    	return array_intersect_key($data, array_flip($this->_hoppers[$name]));
    }

    /**
     * 初始基础数据缓存
     * 
     * @return void
     */
	public function baseInit()
    {
    	if (!isset($this->_cache))
    	{
    		// 固定变更项写入
    	    self::$_options['cache_dir'] = DOCUMENT_CACHE;
    	    self::$_options['lifeTime']  = 43200;
    	    // 参数变更初始化
    	    $this->_setFrontendOptions();
    	    $this->_setBackendOptions();

    	    $this->_cache = Zend_Cache::factory('Core', 'File', 
    	        $this->_frontendOptions, $this->_backendOptions
	        );
    	}
    }

    /**
     * 保存基础数据缓存
     * 
     * @param array $data
     * @return boolean True if no problem
     */
	public function baseSave($data)
    {
    	$this->baseInit();

    	return $this->_cache->save($data, 'base');
    }

    /**
     * 载入基础数据缓存
     * 
     * @return array|false cached datas
     */
	public function baseLoad()
    {
    	$this->baseInit();

    	if(!$data = $this->_cache->load('base'))
    	{
    		$data = BaseLogic::init()->selectRow();

    	    $this->baseSave($data);
    	}

    	return $data;
    }

    /**
     * 刷新基础数据缓存
     * 
     * @return void
     */
	public function baseRefresh()
    {
    	$this->baseInit();

    	$this->baseSave(BaseLogic::init()->selectRow());
    }


    /**
     * 初始企业缓存
     * 
     * @param string $cid
     * @return void
     */
	public function companyInit($cid)
    {
    	if (!isset($this->_cache) && 10 == strlen($cid))
    	{
    		// 固定变更项写入
    	    self::$_options['cache_dir'] = Commons::getCompanyCache($cid);
			self::$_options['lifeTime']  = 604800;
    	    // 参数变更初始化
    	    $this->_setFrontendOptions();
    	    $this->_setBackendOptions();

    	    $this->_cache = Zend_Cache::factory('Core', 'File', 
    	        $this->_frontendOptions, $this->_backendOptions
	        );
    	}
    }

    /**
     * 保存企业缓存
     * 
     * @param array $data
     * @param string $cid
     * @return boolean True if no problem
     */
	public function companySave($data, $cid)
    {
    	$this->companyInit($cid);

    	return $this->_cache->save($data, "company{$cid}");
    }

    /**
     * 载入企业缓存
     * 
     * @param string $cid
     * @return array|false cached datas
     */
	public function companyLoad($cid)
    {
    	$this->companyInit($cid);

    	if(!$data = $this->_cache->load("company{$cid}"))
    	{
    		$data = CorpCompanyLogic::init()->selectCidRow($cid);

    	    $this->companySave($data, $cid);
    	}

    	return $data;
    }
}
