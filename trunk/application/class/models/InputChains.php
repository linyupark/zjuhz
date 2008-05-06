<?php 

	/**
	 * 负责INPUT输入的过滤和校验
	 *
	 */
	class InputChains
	{
		private $_messages = array();
		private $_valid;
		
		function __construct()
		{
			$this->_valid = new Zend_Validate();
		}
		
		#qq
		function addressQQ($input, $name = 'qq')
		{
			$input = strip_tags(trim($input));
			if(false == empty($input))
			{
				if(false == Valid::alphaNumLenRange($input, 4, 15))
				{
					$this->_messages[$name] = 'QQ格式有错误';
				}
			}
			return $input;
		}
		
		# 邮箱
		function addressEmail($input, $name = 'eMail')
		{
			$input = trim($input);
			if(false == empty($input))
			{
				if(false == Valid::isEmail($input))
				{
					$this->_messages[$name] = '邮箱格式错误';
				}
			}
			return $input;
		}
		
		# 手机号码
		function addressMobile($input, $name = 'mobile')
		{
			$input = trim($input);
			if(false == empty($input))
			{
				if(false == is_numeric($input))
				{
					$this->_messages[$name] = '手机号码必须全为数字';
				}
				if(strlen($input) < 11)
				{
					$this->_messages[$name] = '手机号码长度不对';
				}
			}
			return $input;
		}
		
		# 班级话题标签
		function topicTag($input)
		{
			if(null != $input && is_array($input))
			{
				$input = implode(',',$input);
				$input = trim(strip_tags($input));
			}
			return $input;
		}
		
		# 班级话题标题
		function topicTitle($input, $name = 'topic_title')
		{
			$input = strip_tags(trim($input));
			$valid = $this->_valid;
			$valid->addValidator(new Zend_Validate_StringLength(4, 100));
			if(!$valid->isValid($input))
			{
				$this->_messages[$name] = '标题长度范围在4到100之间(中文2到50之间)';
			}
			return $input;
		}
		
		# 不能为空
		function noEmpty($input, $name)
		{
			$input = trim($input);
			if(empty($input))
			{
				$this->_messages[$name] = $name.'不能为空';
			}
			return $input;
		}
		
		# 班级名称 
		function className($input, $name = 'class_name')
		{
			$input = strip_tags(trim($input));
			$valid = $this->_valid;
			$valid->addValidator(new Zend_Validate_StringLength(4, 50));
			if(!$valid->isValid($input))
			{
				$this->_messages[$name] = '班级名称长度范围在4到50之间(中文2到25之间)';
			}
			elseif (false != DbModel::isClassExist($input))
			{
				$this->_messages[$name] = $input.' 该名称的班级已经存在';
			}
			return $input;
		}
		
		
		# 获取校验信息
		function getMessages()
		{
			return $this->_messages;
		}
	}