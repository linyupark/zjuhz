<?php

	class Cmd
	{
		/**
		 * 建立某成员的班级信息缓存
		 *
		 * @param int $uid 成员唯一id
		 */
		static function cacheClass($uid)
		{
			$cacheDir = DOCROOT.Commons::getUserFolder($uid).'cache/';
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
			$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
			
			$db = Zend_Registry::get('dbClass');
			$rowset = $db->fetchAll('SELECT `class_name`,`class_college`,`class_year`,`class_id` FROM `vi_class_member` 
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