<?php

	class Lp_Valid
	{
		/**
		 * @var array 返回的错误提示
		 */
		private $message;
		/**
		 * @var string 选择的语言
		 */
		public $language;
		/**
		 * @var array 语言包
		 */
		private $packs = array(
			// 中文包
			'cn' => array(
				'valid_exact' => '%0%的长度必须为%1%',
				'valid_required' => '%0%不能为空',
				'valid_numeric' => '%0%必须为数字',
				'valid_alpha' => '%0%必须为字母',
				'valid_alnum' => '%0%必须由字母或数字组合而成',
				'valid_aldash' => '%0%必须由字母,数字,下划线组合而成',
				'valid_email' => '%0%必须为有效的电子邮箱地址',
				'valid_url' => '%0%必须为有效的URL地址',
				'valid_str_between' => '%0%字符长度范围在%1%到%2%之间',
				'valid_str_exact' => '%0%字符长度必须为%1%',
				'valid_num_between' => '%0%的数值应该在%1%到%2%之间',
				'valid_num_equal' => '%0%的值应该为%1%',
                'valid_matches' => '%0%的值必须与%1%相同'
			),
			// 英文包
			'en' => array()
		);
		
		/**
		 * @param string $language 实例化时选择使用的语言包
		 */
		function __construct($language = 'cn')
		{
			$this->language = $language;
		}
		
		public function of($value, $name, $alias, $validator)
		{
			if(get_magic_quotes_gpc())
			{
				$value = stripslashes($value);
			}
			
			// 要用到的校验器(函数)收集
			$validatorArr = explode('|', $validator);
			foreach ($validatorArr as $fun)
			{
				// eg. fun[arg,arg]|fun[arg,arg]
				if(preg_match("/(.*)\[(.*)\]/", $fun, $match))
				{
					$fun = $match[1];
					$arg = explode(",", $match[2]);
				}
				
				if(function_exists($fun))
				{
					$value = $fun($value);
				}
				elseif (method_exists($this, $fun))
				{
					$this->$fun($value, $name, $alias, $arg);
				}
				else 
				{
					throw new Exception($fun.' is not an in effect function!');
				}
			}
			
			return $value;
		}
		
		/**
		 * @param string $key 关键字
		 */
		private function trans($key)
		{
			$language = $this->language;
			
			if(FALSE == array_key_exists($language, $this->packs))
			{
				throw new Exception($language.' language pack is not exist!');
			}
			else // 进行关键字->目标语言转换
			{
				return $this->packs[$language][$key];
			}
		}
		
		/**
		 * 获取单条错误提示
		 * @param string $name input 的 name值
		 * @param string $decoration 修饰内容
		 */
		public function getMessage($name, $decoration = '*')
		{
			$message = $this->message;
			
			if(NULL == $message) return false;
			
			if(array_key_exists($name, $message))
			{
				return $decoration.$message[$name];
			}
		}
		
		/**
		 * 获取错误信息提示(集合式显示)
		 * @param string $decoration 错误信息头部修饰
		 * @param string $separator 错误与错误间的间隔
		 */
		public function getMessages($decoration = '*', $separator = '<br />')
		{
			$message = $this->message;
			
			if(NULL == $message) return false;
			
			if(is_array($message) && count($message) > 0)
			{
				foreach ($message as $k => $v)
				{
					$message[$k] = $decoration.$message[$k];
				}
				return implode($separator, $message);
			}
		}
		
		/**
		 * 增加错误提示信息
		 * @param string $name input的name值
		 * @param string $key 关键字
		 * @param array $params 替换参数
		 */
		public function addMessage($name, $key = null, $alias = array())
	    {
	    	$str = $this->trans($key);
	    	
	    	for ($i = 0; $i < count($alias); $i++)
	    	{
	    		$str = preg_replace("/%$i%/", $alias[$i], $str);
	    	}
	    	$this->message[$name] = $str;
	    }
        
        function strlen_utf8($str)
        {
          $i = 0;
          $count = 0;
          $len = strlen($str);
          while ($i < $len)
          {
            $chr = ord($str[$i]);
            $count++;
            $i++;
            if ($i >= $len)
              break;
            if ($chr & 0x80)
            {
              $chr <<= 1;
              while ($chr & 0x80)
              {
                $i++;
                $chr <<= 1;
              }
            }
          }
          return $count;
        }
	    
	    /**
	     * -----------------------------------------------------------------
	     */
	    
        # 必填
	    private function required($value, $name, $alias)
	    {
	    	if(empty($value))
	    	$this->addMessage($name, 'valid_required', array($alias));
	    }
		
        # 必须为数字
        private function numeric($value, $name, $alias)
        {
            if(!is_numeric($value))
            $this->addMessage($name, 'valid_numeric', array($alias));
        }
        
        # 字母
        private function alpha($value, $name, $alias)
        {
            if (!ctype_alpha($value))
            $this->addMessage($name, 'valid_alpha', array($alias));
        }
        
        # 字母+数字
        private function alnum($value, $name, $alias)
        {
            if(!ctype_alnum($value))
            $this->addMessage($name, 'valid_alnum', array($alias));
        }
        
        # 长度范围
		private function str_between($value, $name, $alias, $arg)
		{
			list($min, $max) = $arg;
			if($this->strlen_utf8($value) > $max || $this->strlen_utf8($value) < $min)
			$this->addMessage($name, 'valid_str_between', array($alias, $min, $max));
		}
        
        # 数字范围
        private function num_between($value, $name, $alias, $arg)
        {
            list($min, $max) = $arg;
            if($value > $max || $value < $min)
            $this->addMessage($name, 'valid_num_between', array($alias, $min, $max));
        }
        
        # 固定长度
        private function str_exact($value, $name, $alias, $arg)
        {
            if($this->strlen_utf8($value) != $arg[0])
            $this->addMessage($name, 'valid_str_exact', array($alias, $arg[0]));
        }
        
        # 相同
        private function matches($value, $name, $alias, $arg)
        {
            $input = $arg[0];
            $value2 = $arg[1];
            if($value != $value2)
            $this->addMessage($name, 'valid_matches', array($alias, $input));
        }
        
        # 字母+下划线+数字
        private function aldash($value, $name, $alias)
        {
            if(!preg_match("/^([-a-z0-9_-])+$/i", $value))
            $this->addMessage($name, 'valid_aldash', array($alias));
        }
        
        # 数值相等
        private function num_equal($value, $name, $alias, $arg)
        {
            $result = $arg[0];
            if($value != $result)
            $this->addMessage($name, 'valid_num_equal', array($alias, $result));
        }
	    
        # 有效邮箱
	    private function valid_email($value, $name, $alias)
	    {
	    	if (!ereg("^([a-zA-Z0-9_])+@([a-zA-Z0-9_])+((.)([a-zA-Z0-9_]))+", $value))
	    	$this->addMessage($name, 'valid_email', array($alias));
	    }
        
        # 有效url地址
        private function valid_url($value, $name, $alias)
        {
            if(!preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/",$value))
            $this->addMessage($name, 'valid_url', array($alias));
        }
	    
	}

?>