<?php
	
	/**
	 * 资讯全文索引查询
	 *
	 */
	class SearchController extends Zend_Controller_Action 
	{
		function init()
		{
			
		}
		
		# 查找
		function lookAction()
		{
			$this->_helper->layout->disableLayout();
			$keywords = $this->_getParam('for');
			if(!empty($keywords))
			{
				$query = urldecode($keywords);
				Zend_Search_Lucene_Analysis_Analyzer::setDefault(new CNLuceneAnalyzer());
				$index = Zend_Search_Lucene::open('../../public/static/indexes/info-index');
				$hits = $index->find($query);
				foreach ($hits as $hit)
				{
					echo $hit->score.' --- ';
					echo $hit->title.'<br />';
				}
			}
		}
		
		# 优化
		function optimizeAction()
		{
			$index = Zend_Search_Lucene::open('../../public/static/indexes/info-index');
			$index->optimize();
		}
		
		# 建立
		function createAction()
		{
			$this->_helper->layout->disableLayout();
			Zend_Search_Lucene_Analysis_Analyzer::setDefault(new CNLuceneAnalyzer());
			$index = Zend_Search_Lucene::create('../../public/static/indexes/info-index');
			$Db = Zend_Registry::get('dbInfo');
			$rows = $Db->fetchAll('SELECT * FROM `vi_entity`');
			foreach ($rows as $k => $v)
			{
				$doc = new Zend_Search_Lucene_Document();
				$doc->addField(Zend_Search_Lucene_Field::UnIndexed('id',$v['entity_id'],'utf8'));
				$tags = explode(',', $v['entity_tag']);
				foreach ($tags as $key => $val)
				{
					$doc->addField(Zend_Search_Lucene_Field::Keyword('No:'.$key, $val,'utf8'));
				}
				$doc->addField(Zend_Search_Lucene_Field::Text('title', $v['entity_title'],'utf8'));
				$doc->addField(Zend_Search_Lucene_Field::UnStored('content', $v['entity_content'],'utf8'));
				$index->addDocument($doc);
			}
		}
	}