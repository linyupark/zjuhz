<?php

/**
 * 专题控制器（静态页）
 * 
 * @author Linyu
 */


class SubjectController extends Zend_Controller_Action {
	
	function init()
	{
		$this->view->headScript()->appendFile('/static/scripts/thickbox-compressed.js')
								 ->appendFile('/static/scripts/info/action.js');

	}
	
	# 通用显示页
	function indexAction()
	{
		$name = $this->getRequest()->getParam('of');
		$this->render($name);
	}
	
	function description($name)
	{
		$data['qiushixungen-jiangxi'] = array(
				'校友总会秘书长张美凤女士主持启程仪式',
				'校党委副书记王玉芝女士为活动致辞',
				'探访团成员及学生代表仔细聆听校领导讲话',
				'探访团副团长、企业家经理人同学会会长、浙江野风进出口有限公司董事长俞飞跃发言',
				'探访团副团长、杭州浙大校友会(筹)负责人、恒生电子董事陈鸿校友发言',
				'俞飞跃、陈鸿校友接受校领导为探访团授旗',
				'探访团团长、朱军副校长正式宣布“求是寻根——浙大校友探访西迁路”活动正式启程',
				'出发前探访团成员合影',
				'凌晨3：40，探访团成员乘坐的K271次列车抵达吉安站',
				'凌晨5:00，探访团入住泰和县白凤宾馆',
				'探访团成员走在通往张侠魂女士墓地的路上，砂子是头天晚上大雨之后泰和县政府安排临时铺设的',
				'侠魂女士、竺衡，我们来探望你们了！',
				'泰和县委常委、宣传部长邓复玲女士和博物馆副馆长向探访团成员详细介绍找寻墓地之艰辛',
				'探访团成员仔细听取邓部长和副馆长的介绍，被泰和县领导保护浙大遗迹的拳拳关爱而感动',
				'朱军副校长和邓复玲部长为侠魂女士及竺衡墓献上花圈',
				'侠魂女士和竺衡之墓的简单介绍',
				'从墓地远眺泰和县城',
				'探访团成员在原浙大农学院(现为泰和县黄冈小学)合影',
				'探访团成员在泰和六中合影，背后是浙大师生西迁时用过的大礼堂，当地政府至今一直努力保持原貌',
				'探访团成员游览毛泽东旧居',
				'俯视井冈山水口彩虹瀑布',
				'乘坐快艇遨游于井冈湖上',
				'仰望井冈山水口彩虹瀑布',
				'庄严肃穆的井冈山革命博物馆',
				'5月11日晚入住井冈山上的井秀山庄',
				'晚饭后大家歌声庆祝相识相知',
				'黄洋界景点立着的竖碑，刻着主席名言“星星之火可以燎原”',
				'黄洋界横碑，一侧为毛泽东题词',
				'黄洋界横碑，另一侧为朱德题词',
				'游览井冈山百竹园',
				'探访团乘缆车观望五龙潭',
				'五龙潭之仙女潭',
				'五龙潭之碧玉潭',
				'游览井冈山革命烈士陵园',
				'游览井冈山雕塑园',
				'五名代表登顶瞻仰井冈山革命烈士纪念碑',
			);
		return $data[$name];
	}
	
	# AJAX图片展示
	function albumAction()
	{
		
		$this->_helper->layout->disableLayout();
		
		$request = $this->_request;
		if($request->isXmlHttpRequest())
		{
			$pagesize = 12; // 分页大小
			$name = $request->getParam('of',FALSE);
			$page = $request->getParam('p',1);
			
			// 获取对应照片缩略图
			$d = dir('../../public/static/subjects/'.$name.'/pics/sample');
			$samples = array();
			while (FALSE !== ($entry = $d->read()))
			{
				if(strstr($entry,'.jpg'))
				$samples[] = $entry; //对相片记录进行存储
			}
			// 分页处理
			Page::$pagesize = $pagesize;
			$page_str = Page::create(array(
				'href_open' => '<a href="javascript:album(\''.$name.'\',%d)">',
				'href_close' => '</a>',
				'num_rows' => count($samples),
				'cur_page' => $page
			));
			$offset = ($page-1)*$pagesize;
			$samples = array_slice($samples, $offset, $pagesize);
			
			$descriptions = $this->description($name);
			
			$this->view->pages = Page::$num_pages;
			$this->view->name = $name;
			$this->view->descriptions = array_slice($descriptions, $offset, $pagesize);
			$this->view->pagesize = $pagesize;
			$this->view->page = $page;
			$this->view->pagination = $page_str;
			$this->view->samples = $samples;
													
		}
	}
}
