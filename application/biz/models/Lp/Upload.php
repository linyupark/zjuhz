<?php

	/**
	 * 文件上传专用类(支持多文件上传)
	 *
	 */
	class Lp_Upload
	{
		public static $upConfig = array(
			"max_size" => 100,  //* 允许文件大小,kb
  			"file_type" => "", 
  			"file_temp" => "", 
  			"file_name" => "",  //上传后的文件名
  			"cust_name" => "",  //自定义名称
  			"file_size" => "", 
  			"file_ext" => "", 
  			"orig_name" => "", 
  			"allow_type" => "",  //* "jpg|...|..."
  			"random" => false,  //是否为随机
  			"overwrite" => false,  //是否覆盖
  			"upload_path" => "",  //* 上传路径
  			"no_spaces" => true
		);
		
		private static $messages = ''; // 收集信息
		
		/**
		 * 加入提示信息
		 *
		 * @param string $message
		 */
		private static function tip($message)
		{
			self::$messages = $message;
		} 
		
		/**
		 * 返回提示信息
		 *
		 * @return array
		 */
		static function getTip()
		{
			return self::$messages;
		}
		
		/**
		 * 重置提示信息
		 *
		 */
		static function clear()
		{
			self::$messages = '';
		}
		
		/**
		 * 上传设置初始化
		 *
		 */
		static function init($config)
		{
			if (!is_array($config))
		    {
		      throw new Exception("Upload config args must be an array");
		    }
		    $upConfig = self::$upConfig;
		    foreach($config as $key => $val)
		    {
		      $upConfig[$key] = $val;
		    }
		    self::$upConfig = $upConfig;
		}
		
		/**
		 * 获取上传参数值
		 *
		 * @param string $key
		 * @return string
		 */
		static function fetchParam($key)
		{
			return self::$upConfig[$key];
		}
		
        
        static function multi($field)
        {
            if (!isset($_FILES[$field]))
            {
              //self::tip('upload_userfile_not_set');
              self::tip('无法找到变量为'.$field.'的 POST.');
              return false;
            }
            if (!self::validateUploadPath())
            {
              return false;
            }
            
            foreach($_FILES[$field]['name'] as $k => $v)
            {
                if($_FILES[$field]['name'][$k] == '' && $k != 0) continue;
                
                $index = ($k+1);
                
                if (!is_uploaded_file($_FILES[$field]['tmp_name'][$k]))
                {
                  $error = (!isset($_FILES[$field]['error'][$k])) ? 4 : $_FILES[$field]['error'][$k];
            
                  switch ($error)
                  { 
                    case 1:
                      //self::tip('upload_file_exceeds_limit');
                      self::tip('第'.$index.'个上传的文件大小超过了指定的最大限制('.self::fetchParam('max_size').'kb)');
                      break;
                    case 3:
                      //self::tip('upload_file_partial');
                      self::tip('第'.$index.'个只上传了部分文件');
                      break;
                    case 4:
                      //self::tip('upload_no_file_selected');
                      self::tip('第'.$index.'个请选择要上传的文件');
                      break;
                    default:
                      //self::tip('upload_no_file_selected');
                      self::tip('第'.$index.'个请选择要上传的文件');
                      break;
                  }
                  break;
                }
            
                //将值传入设置变量
                self::$upConfig['file_temp'][$index] = $_FILES[$field]['tmp_name'][$k];
                self::$upConfig['file_name'][$index] = $_FILES[$field]['name'][$k];
                self::$upConfig['file_size'][$index] = $_FILES[$field]['size'][$k];
                self::$upConfig['file_type'][$index] = strtolower(preg_replace("/^(.+?);.*$/", "\\1", $_FILES[$field]['type'][$k]));
                self::$upConfig['file_ext'][$index] = self::getExt($_FILES[$field]['name'][$k]);
                
                // Convert the file size to kilobytes
                if (self::$upConfig['file_size'][$index] > 0)
                {
                  self::$upConfig['file_size'][$index] = round(self::$upConfig['file_size'][$index] / 1024, 2);
                }
                // Is the file type allowed to be uploaded?
                if (self::isAllowedType($index) === false)
                {
                  //self::tip('upload_invalid_filetype');
                  self::tip('第'.$index.'个你所尝试上传的文件类型无效');
                  break;
                }
                if (!self::isAllowedFileSize($index))
                {
                  //self::tip('upload_invalid_filesize');
                  self::tip('第'.$index.'上传的文件大小超过了指定的最大限制('.self::fetchParam('max_size').'kb)');
                  break;
                }
                //	Clean the file name for security
                self::$upConfig['file_name'][$index] = self::cleanFileName(self::$upConfig['file_name'][$index]);
            
                // Remove white spaces in the name
                if (self::$upConfig['no_spaces'] == true)
                {
                  self::$upConfig['file_name'][$index] = preg_replace("/\s+/", "_", self::$upConfig['file_name'][$index]);
                }
            
                //
                // Validate the file name
                // This function appends an number onto the end of
                // the file if one with the same name already exists.
                // If it returns false there was a problem.
                //
                self::$upConfig['orig_name'] = self::$upConfig['file_name'][$index];
            
                if (self::$upConfig['overwrite'] == false)
                {
                  self::$upConfig['file_name'][$index] = self::setFileName(self::$upConfig['upload_path'], self::$upConfig['file_name'][$index], $index);
            
                  if (self::$upConfig['file_name'][$index] === false)
                  {
                    return false;
                  }
                }
            
                //custom file name *设置的时候要注意*
                if (self::$upConfig['cust_name'] != "")
                {
                  self::$upConfig['file_name'][$index] = self::$upConfig['cust_name'][$index];
                }
            }
            if(self::getTip())
            {
                return false;
            }
            else //所有文件符合要求，开始传输
            {
                foreach($_FILES[$field]['name'] as $k => $v)
                {
                    if( $_FILES[$field]['name'][$k] == '' ) continue;
                    
                    $index = ($k+1);
                    if (!@copy(self::$upConfig['file_temp'][$index], self::$upConfig['upload_path'].self::$upConfig['file_name'][$index]))
                    {
                      if (!@move_uploaded_file(self::$upConfig['file_temp'][$index], self::$upConfig['upload_path'].self::$upConfig['file_name'][$index]))
                      {
                        //self::tip('upload_destination_error');
                        self::tip('第'.$index.'个在文件上传并转移的时候发出现了问题');
                        break;
                      }
                    }
                }
                if(self::getTip())
                {
                    return false;
                } else return true;
            }
        }
        
	  /**	-------------------------------------------------------------------------------------
	   *	Handle upload file 单文件处理
	   */
	  static function handle($field = 'userfile')
	  {
	    if (!isset($_FILES[$field]))
	    {
	      //self::tip('upload_userfile_not_set');
	      self::tip('无法找到变量为'.$field.'的 POST.');
	      return false;
	    }
	    if (!self::validateUploadPath())
	    {
	      return false;
	    }
	    if (!is_uploaded_file($_FILES[$field]['tmp_name']))
	    {
	      $error = (!isset($_FILES[$field]['error'])) ? 4 : $_FILES[$field]['error'];
	
	      switch ($error)
	      {
	        case 1:
	          //self::tip('upload_file_exceeds_limit');
	          self::tip('上传的文件大小超过了指定的最大限制('.self::fetchParam('max_size').'kb)');
	          break;
	        case 3:
	          //self::tip('upload_file_partial');
	          self::tip('只上传了部分文件');
	          break;
	        case 4:
	          //self::tip('upload_no_file_selected');
	          self::tip('请选择要上传的文件');
	          break;
	        default:
	          //self::tip('upload_no_file_selected');
	          self::tip('请选择要上传的文件');
	          break;
	      }
	      return false;
	    }
	
	    //upload config value set
	    self::$upConfig['file_temp'] = $_FILES[$field]['tmp_name'];
	    self::$upConfig['file_name'] = $_FILES[$field]['name'];
	    self::$upConfig['file_size'] = $_FILES[$field]['size'];
	    self::$upConfig['file_type'] = strtolower(preg_replace("/^(.+?);.*$/", "\\1", $_FILES[$field]['type']));
	    self::$upConfig['file_ext'] = self::getExt($_FILES[$field]['name']);
	
	    // Convert the file size to kilobytes
	    if (self::$upConfig['file_size'] > 0)
	    {
	      self::$upConfig['file_size'] = round(self::$upConfig['file_size'] / 1024, 2);
	    }
	    // Is the file type allowed to be uploaded?
	    if (!self::isAllowedType())
	    {
	      //self::tip('upload_invalid_filetype');
	      self::tip('你所尝试上传的文件类型无效');
	      return false;
	    }
	    // Is the file size within the allowed maximum?
	    if (!self::isAllowedFileSize())
	    {
	      //self::tip('upload_invalid_filesize');
	      self::tip('上传的文件大小超过了指定的最大限制('.self::fetchParam('max_size').'kb)');
	      return false;
	    }
	    //	Clean the file name for security
	    self::$upConfig['file_name'] = self::cleanFileName(self::$upConfig['file_name']);
	
	    // Remove white spaces in the name
	    if (self::$upConfig['no_spaces'] == true)
	    {
	      self::$upConfig['file_name'] = preg_replace("/\s+/", "_", self::$upConfig['file_name']);
	    }
	
	    //
	    // Validate the file name
	    // This function appends an number onto the end of
	    // the file if one with the same name already exists.
	    // If it returns false there was a problem.
	    //
	    self::$upConfig['orig_name'] = self::$upConfig['file_name'];
	
	    if (self::$upConfig['overwrite'] == false)
	    {
	      self::$upConfig['file_name'] = self::setFileName(self::$upConfig['upload_path'], self::$upConfig['file_name']);
	
	      if (self::$upConfig['file_name'] === false)
	      {
	        return false;
	      }
	    }
	
	    //custom file name
	    if (self::$upConfig['cust_name'] != "")
	    {
	      self::$upConfig['file_name'] = self::$upConfig['cust_name'];
	    }
	
	    //
	    // Move the file to the final destination
	    // To deal with different server configurations
	    // we'll attempt to use copy() first.  If that fails
	    // we'll use move_uploaded_file().  One of the two should
	    // reliably work in most environments
	    //
	    if (!@copy(self::$upConfig['file_temp'], self::$upConfig['upload_path'].self::$upConfig['file_name']))
	    {
	      if (!@move_uploaded_file(self::$upConfig['file_temp'], self::$upConfig['upload_path'].self::$upConfig['file_name']))
	      {
	        //self::tip('upload_destination_error');
	        self::tip('在文件上传并转移的时候发出现了问题');
	        return false;
	      }
	    }
	    return true;
	  }
	  
      
	  /**	---------------------------------------------------------------------------------
	   * Set the file name
	   *
	   * This function takes a filename/path as input and looks for the
	   * existence of a file with the same name. If found, it will append a
	   * number to the end of the filename to avoid overwriting a pre-existing file.
	   *
	   * @access	public
	   * @param	string
	   * @param	string
	   * @return	string
	   */
	  static function setFileName($path, $filename, $index = 'one')
	  {
	    if (self::$upConfig['random'] == true)
	    {
	      mt_srand();
          if($index != 'one')
          $filename = md5(uniqid(mt_rand())).self::$upConfig['file_ext'][$index];
	      else $filename = md5(uniqid(mt_rand())).self::$upConfig['file_ext'];
	    }
	
	    if (!file_exists($path.$filename))
	    {
	      return $filename;
	    }
        
        if($index != 'one')
	    $filename = str_replace(self::$upConfig['file_ext'][$index], '', $filename);
        else $filename = str_replace(self::$upConfig['file_ext'], '', $filename);
	
	    $new_filename = '';
	    for ($i = 1; $i < 100; $i++)
	    {
            if($index != 'one')
            {
                if (!file_exists($path.$filename.$i.self::$upConfig['file_ext'][$index]))
                {
                  $new_filename = $filename.$i.self::$upConfig['file_ext'][$index];
                  break;
                }
            }
            else
            {
                if (!file_exists($path.$filename.$i.self::$upConfig['file_ext']))
                {
                  $new_filename = $filename.$i.self::$upConfig['file_ext'];
                  break;
                }
            }
	    }
	
	    if ($new_filename == '')
	    {
	      //self::tip('upload_bad_filename');
	      self::tip('上传的文件名已经存在');
	      return false;
	    }
	    else
	    {
	      return $new_filename;
	    }
	  }
	
	  /**	--------------------------------------------------------------------------------------
	   * Clean the file name for security
	   *
	   * @access	public
	   * @param	string
	   * @return	string
	   */
	  static function cleanFileName($filename)
	  {
	    $bad = array("<!--", "-->", "'", "<", ">", '"', '&', '$', '=', ';', '?', '/', "%20", "%22", "%3c",  // <
	    "%253c",  // <
	    "%3e",  // >
	    "%0e",  // >
	    "%28",  // (
	    "%29",  // )
	    "%2528",  // (
	    "%26",  // &
	    "%24",  // $
	    "%3f",  // ?
	    "%3b",  // ;
	    "%3d" // =
	    );
	
	    foreach($bad as $val)
	    {
	      $filename = str_replace($val, '', $filename);
	    }
	
	    return $filename;
	  }
	
	  /**	---------------------------------------------------------------------------------------
	   * Verify that the file is within the allowed size
	   *
	   * @access	public
	   * @return	bool
	   */
	  static function isAllowedFileSize($index = 'one')
	  {
        if (self::$upConfig['max_size'] <= 0) return false;
        else
        {
            if ($index != 'one')
            {
                return (self::$upConfig['file_size'][$index] > self::$upConfig['max_size']) ? false : true;
            }
            else
            {
                return (self::$upConfig['file_size'] > self::$upConfig['max_size']) ? false : true;
            }
        }
	  }
	
	  /** ------------------------------------------------------------------------------
	   * Verify that the filetype is allowed
	   *
	   * @access	public
	   * @return	bool
	   */
	  static function isAllowedType($index = 'one')
	  {
	    if (self::$upConfig['allow_type'] == "")
	    {
	      //self::tip('upload_no_file_types');
	      self::tip('你尚未指定有效的文件类型');
	      return false;
	    }
	    $typeArr = explode("|", self::$upConfig['allow_type']);
        if($index != 'one')
        return in_array(strtolower(substr(self::$upConfig['file_ext'][$index], 1)), $typeArr);
        else return in_array(strtolower(substr(self::$upConfig['file_ext'], 1)), $typeArr);
	  }
	
	  /**	-------------------------------------------------------------------------------
	   * Extract the file extension
	   *
	   * @access	public
	   * @param	string
	   * @return	string
	   */
	  static function getExt($filename)
	  {
	    $x = explode('.', $filename);
	    return '.'.end($x);
	  }
	
	  static function validateUploadPath()
	  {
	    if (self::$upConfig['upload_path'] == "")
	    {
	      //self::tip('upload_no_filepath');
	      self::tip('上传路径无效');
	      return false;
	    }
	
	    if (function_exists('realpath') && @self::$upConfig['upload_path'] !== false)
	    {
	      self::$upConfig['upload_path'] = str_replace("\\", "/", realpath(self::$upConfig['upload_path']));
	    }
	
	    if (!@is_dir(self::$upConfig['upload_path']))
	    {
	      //self::tip('upload_no_filepath');
	      self::tip('上传路径无效');
	      return false;
	    }
	
	    if (!is_writable(self::$upConfig['upload_path']))
	    {
	      //self::tip('upload_not_writable');
	      self::tip('上传目录当前权限无法写入文件');
	      return false;
	    }
	
	    self::$upConfig['upload_path'] = preg_replace("/(.+?)\/*$/", "\\1/", self::$upConfig['upload_path']);
	    return true;
	  }  
	  
	}

?>