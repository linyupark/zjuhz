<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CacheLogic.php
 */


/**
 * 校友互助-缓存
 * 控制器附属层:模型层操作入口
 */
class CacheLogic extends HelpInterlayer
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
        'cache_dir' => DOCUMENT_CACHE, // from bootstarp
        /* frontend options */
        'lifeTime' => null, 
        'automatic_serialization' => true
    );

	/**
     * hopper it(即从传入数组中只保留以下键值)
     * 
     * @var array
     */
    private $_hoppers = array(
        'json' => array('sid', 'name')
    );

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
    private $_methods = array(
        'rankAsk' => array(
            'expert' => 'selectRankExpert', 
            'active' => 'selectRankActive'
        )
    );

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
     * 初始json方式的分类缓存
     * 
     * @return void
     */
	public function jsonInit()
    {
    	if (!isset($this->_cache))
    	{
    		// 固定变更项写入
    	    self::$_options['cache_dir'] = DOCUMENT_CACHE.'sort/';
    	    // 参数变更初始化
    	    $this->_setFrontendOptions();
    	    $this->_setBackendOptions();

    	    $this->_cache = Zend_Cache::factory('Core', 'File', 
    	        $this->_frontendOptions, $this->_backendOptions
	        );
    	}
    }

    /**
     * 保存json方式的分类缓存
     * 
     * @param array $data
     * @param integer $sid
     * @return boolean True if no problem
     */
	public function jsonSave($data, $sid)
    {
    	$this->jsonInit();

    	return $this->_cache->save($this->_setHoppers($data, 'json'), "json{$sid}");
    }

    /**
     * 载入json方式的分类缓存
     * 
     * @param integer $sid
     * @return string|false cached datas
     */
	public function jsonLoad($sid)
    {
    	$this->jsonInit();

    	return $this->_cache->load("json{$sid}");
    }


    /**
     * 初始各类榜单缓存
     * 
     * @return void
     */
	public function rankInit()
    {
    	// 固定变更项写入
    	self::$_options['cache_dir'] = DOCUMENT_CACHE.'rank/';    	    
    	// 参数变更初始化
    	$this->_setFrontendOptions();
    	$this->_setBackendOptions();

    	$this->_cache = Zend_Cache::factory('Core', 'File', 
    	    $this->_frontendOptions, $this->_backendOptions
	    );
    }

    /**
     * 初始用于统计tbl_ask的榜单缓存
     * 
     * @return void
     */
	public function rankAskInit()
    {
    	if (!isset($this->_cache))
    	{
    		self::$_options['lifeTime'] = 14400; // 4 hours

    	    $this->rankInit();
    	}
    }

    /**
     * 保存用于统计tbl_ask的榜单缓存
     * 
     * @param array $data
     * @param string $type
     * @return boolean True if no problem
     */
	public function rankAskSave($data, $type)
    {
    	$this->rankAskInit();

    	return $this->_cache->save($data, "rankAsk{$type}");
    }

    /**
     * 载入用于统计tbl_ask的榜单缓存
     * 
     * @param string $type
     * @return string|false cached datas
     */
	public function rankAskLoad($type)
    {
    	$this->rankAskInit();

    	if(!$data = $this->_cache->load("rankAsk{$type}"))
    	{
            $method = $this->_methods['rankAsk'][$type]; // 获取方法
    		$data = AskLogic::init()->$method();

    	    $this->rankAskSave($data, $type);
    	}

    	return $data;
    }
}
