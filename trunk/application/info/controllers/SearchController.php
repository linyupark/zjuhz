<?php
	
	/**
	 * 资讯全文索引查询
	 *
	 */
	class SearchController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen')
								   ->appendStylesheet('/static/styles/info_front.css','screen');

			// 获取全局SESSION 
			$this->_sessCommon = Zend_Registry::get('sessCommon');
								   
			// 当前所属模块分配
			$this->view->request = $this->getRequest();
			
			// 分配当前角色信息
			$this->view->role = $this->_sessCommon->role;
			
			// 分配角色详细信息
			$this->view->login = $this->_sessCommon->login;
		}
		
		/* --------- Lucene 方法 (未完善放弃使用) -------------------------------------------- */
		# 查找
		function lookAction()
		{
			if($keywords = $this->getRequest()->getParam('for'))
			{
				Zend_Search_Lucene_Analysis_Analyzer::setDefault(new CNLuceneAnalyzer());
				$index = Zend_Search_Lucene::open('../../public/static/indexes/info-index');
				$query = Zend_Search_Lucene_Search_QueryParser::parse($keywords);
				//$query->highlightMatches();
				$hits = $index->find($query);
				echo count($hits);
			}
		}
		
		# 优化
		function optimizeAction()
		{
			//$index = Zend_Search_Lucene::open('../../public/static/indexes/info-index');
			//$index->optimize();
			//echo Commons::decrypt("47Wp766x");
		}
		
		# 建立
		function createAction()
		{
			$SW = new SplitWord();
			$string = iconv('gb2312','utf-8',$SW->SplitRMM(iconv('utf-8','gb2312','应浙江大学加州国际纳米技术研究院陈帆青教授邀请')));
			$SW->Clear();
			$this->_helper->layout->disableLayout();
			$analyzer = new CNLuceneAnalyzer();
        $analyzer->setInput($analyzer->cleanup($string), 'utf-8');
        
    $position     = 0;
        $tokenCounter = 0;
        while (($token = $analyzer->nextToken()) !== null) {
            $tokenCounter++;
            $tokens[] = $token;
        }
        print_r($tokens); 
			//$index = Zend_Search_Lucene::create('../../public/static/indexes/info-index');
			/*
			$Db = Zend_Registry::get('dbInfo');
			$rows = $Db->fetchAll('SELECT * FROM `vi_entity`');
			foreach ($rows as $k => $v)
			{
				$doc = new Zend_Search_Lucene_Document();
				$doc->addField(Zend_Search_Lucene_Field::UnIndexed('id',$v['entity_id'],'utf8'));
				$tags = explode(',', $v['entity_tag']);
				foreach ($tags as $key => $val)
				{
					$doc->addField(Zend_Search_Lucene_Field::Keyword($val, $val,'utf8'));
				}
				$doc->addField(Zend_Search_Lucene_Field::Text('title', $v['entity_title'],'utf8'));
				$doc->addField(Zend_Search_Lucene_Field::UnStored('content', $v['entity_content'],'utf8'));
				$index->addDocument($doc);
			}*/
			//$doc = new Zend_Search_Lucene_Document();
			//$doc->addField(Zend_Search_Lucene_Field::Text('content', $string,'utf8'));
			//$index->addDocument($doc);
		}
		
	}