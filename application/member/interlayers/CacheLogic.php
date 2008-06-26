<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CacheLogic.php
 */


/**
 * 校友中心-缓存
 * 控制器附属层:模型层操作入口
 */
class CacheLogic extends MemberInterlayer
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
    private $_hoppers = array(
        'card' => array('uid', 'sex', 'lastLogin', 'year', 'college')
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
     * 初始名片缓存
     * 
     * @return void
     */
	public function cardInit()
    {
    	if (!isset($this->_cache))
    	{
    		// 固定变更项写入
    	    // ...
    	    // 参数变更初始化
    	    $this->_setFrontendOptions();
    	    $this->_setBackendOptions();

    	    $this->_cache = Zend_Cache::factory('Core', 'File', 
    	        $this->_frontendOptions, $this->_backendOptions
	        );
    	}
    }

    /**
     * 保存名片缓存
     * 
     * @param array $data
     * @return boolean True if no problem
     */
	public function cardSave($data)
    {
    	$this->cardInit();

    	return $this->_cache->save($this->_setHoppers($data, 'card'), 'card');
    }

    /**
     * 载入名片缓存
     * 
     * @return array|false cached datas
     */
	public function cardLoad()
    {
    	$this->cardInit();

    	return $this->_cache->load('card');
    }


    /**
     * 初始班级缓存
     * 
     * @return void
     */
	public function classInit()
    {
    	if (!isset($this->_cache))
    	{
    		// 固定变更项写入
    	    // ...
    	    // 参数变更初始化
    	    $this->_setFrontendOptions();
    	    $this->_setBackendOptions();

    	    $this->_cache = Zend_Cache::factory('Core', 'File', 
    	        $this->_frontendOptions, $this->_backendOptions
	        );
    	}
    }

    /**
     * 载入班级缓存
     * 
     * @return array|false cached datas
     */
	public function classLoad()
    {
    	$this->classInit();

    	return $this->_cache->load('classCache');
    }


    /**
     * 初始在线缓存
     * 
     * @return void
     */
	public function onlineInit()
    {
    	if (!isset($this->_cache))
    	{
    		// 固定变更项写入
    	    self::$_options['cache_dir'] = DOCUMENT_CACHE;
    	    // 参数变更初始化
    	    $this->_setFrontendOptions();
    	    $this->_setBackendOptions();

    	    $this->_cache = Zend_Cache::factory('Core', 'File', 
    	        $this->_frontendOptions, $this->_backendOptions
	        );
    	}
    }

    /**
     * 保存在线缓存
     * 
     * @param array $data
     * @param string $name
     * @return boolean True if no problem
     */
	public function onlineSave($data, $name='')
    {
    	$this->onlineInit();

    	return $this->_cache->save($data, "online{$name}");
    }

    /**
     * 载入在线缓存
     * 注意：每次访问或离去(指php自动删除session)都会自动刷新,但若
     *      需要再加速以提高在线情况精确度,可修改php.ini中相关配置
     * 
     * @param string $name
     * @return array|false cached datas
     */
	public function onlineLoad($name='')
    {
    	$this->onlineInit();

    	 // 获取缓存中的在线数
    	$cache = (int)$this->_cache->load('onlinenum');
    	//实际sess文件存有数 此值必须与下方统一否则缓存无法生效
    	$facts = count(scandir(session_save_path())) - 2;

    	// 若上述两值不相等则刷新缓存,反之则返回对应缓存
    	return ($cache == $facts ? $this->_cache->load("online{$name}") : 
    	    $this->onlineRefresh($name)
    	);
    }

    /**
     * 刷新在线缓存
     * 
     * @param string $name
     * @return array
     */
	public function onlineRefresh($name)
    {
    	$this->onlineInit();

    	$path  = session_save_path(); // 读取php.ini中的sess保存路径
    	$files = scandir($path); // 获取sess保存路径所有sess文件
    	foreach ($files as $value)
    	{
    		$file = "{$path}/{$value}";
    		if (is_file($file) && 1000 <= filesize($file)) // 去除filesize会包括游客
    		{
    			// 将每一个sess读取并转换为数组格式
    		    $sess   = MemberClass::DecodeSession(file_get_contents($file));
    		    // 若登录sess数组结构有变化则需改变
    		    $data[] = array('uid' => $sess['common']['login']['uid'], 
    		        'realName' => $sess['common']['login']['realName']
    		    );
    		}
    	}

    	// 统计在线人数(含游客即未登录)
    	// 此值放值位置决定统计精确度
        $num = count($files) - 2; // 减去"."和".."

    	$this->onlineSave($data); // 保存在线会员详情
    	$this->onlineSave($num, 'num'); // 保存总的在线人数

    	return ('num' == $name ? $num : $data);
    }
}
