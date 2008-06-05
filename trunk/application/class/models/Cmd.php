<?php

	class Cmd
	{
		
		static function cacheClassInit($uid)
		{
			$cacheDir = Commons::getUserCache($uid);
			if(FALSE == file_exists($cacheDir))
			{
				@mkdir($cacheDir, 0777);
			}
			
			$frontendOptions = array(
			   'lifetime' => null,
			   'automatic_serialization' => true
			);
			
			$backendOptions = array(
			    'cache_dir' => $cacheDir
			);
			return Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
		}
		
		/**
		 * 建立某成员的班级信息缓存
		 *
		 * @param int $uid 成员唯一id
		 */
		static function cacheClass($uid)
		{
			$cache = self::cacheClassInit($uid);
			$db = Zend_Registry::get('dbClass');
			$rowset = $db->fetchPair('SELECT `class_id`,`class_name` FROM `vi_class_member` 
									WHERE `class_member_id` = ?', $uid);
			
			$cache->save($rowset, 'classCache');
		}
		
		# 是否为班级成员
		static function isMember($class_id)
		{
			return Zend_Registry::get('sessClass')->data[$class_id] == null ? false : true;
		}
		
		# 是否为班级管理员
		static function isManager($class_id)
		{
			if(Zend_Registry::get('sessClass')->data[$class_id]['class_charge'] != Zend_Registry::get('sessCommon')->login['uid'] && Zend_Registry::get('sessClass')->data[$class_id]['class_member_charge'] != 1 ) 
			return false;
			else return true;
		}
		
		# 是否为建立者
		static function isCreater($class_id)
		{
			return Zend_Registry::get('sessClass')->data[$class_id]['class_charge'] == Zend_Registry::get('sessCommon')->login['uid'] ? true : false;
		}
	}