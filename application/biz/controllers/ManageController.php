<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ManageController.php
 */


/**
 * 校友企业-企业管理
 */
class ManageController extends Zend_Controller_Action
{
	/**
     * 公用Session
     * 
     * @var object
     */
	private $_sessCommon = null;

	/**
     * 项目Session
     * 
     * @var object
     */
	private $_sessCompany = null;

	/**
     * 项目模块配置
     * 
     * @var object
     */
	private $_iniCompany = null;

	/**
     * 当前企业资料
     * 
     * @var array
     */
	private $_dataCompany = array();

	/**
     * 初始化
     * 
     * @return void
     */
	public function init()
	{
		$this->_sessCommon  = Zend_Registry::get('sessCommon');
		$this->_sessCompany = Zend_Registry::get('sessCompany');
		$this->_iniCompany  = Zend_Registry::get('iniCompany');

        // 获取动作名称
        $actionName = $this->getRequest()->getActionName();
		// 判断管理权限
		(!$this->_sessCompany->manageCid && 'login' != $actionName ? 
		    $this->_redirect('/biz/my/company/type/valid/', array('exit')) : '');
		// 载入企业资料
		$this->_dataCompany = (!$this->_sessCompany->manageCid || 'login' == $actionName ? '' : 
		    CacheLogic::init()->companyLoad($this->_sessCompany->manageCid));

		$this->view->sessCommon  = $this->_sessCommon;
		$this->view->sessCompany = $this->_sessCompany;
		$this->view->dataCompany = $this->_dataCompany;
	}

	/**
     * 企业管理-登录系统
     * 
     * @return void
     */
	public function loginAction()
	{
		// 初始销毁
		$this->_sessCompany->manageCid = '';
		// 企业管理session初始化
		$cid      = CommonFilter::cid(($this->getRequest()->getParam('cid')));
		$redirect = $this->getRequest()->getParam('redirect', '/manage/company/');
		if ($row = CorpCompanyLogic::init()->selectCidUidRow($cid, USER_UID))
		{
			CacheLogic::init()->companySave($row, $cid);

			$this->_sessCompany->manageCid = $cid;
			$this->_redirect($redirect, array('exit'));
		}
		else
		{
			$this->_sessCompany->manageCid = '';
			$this->_redirect('/biz/my/company/type/valid/', array('exit'));
		}
	}

	/**
     * 企业管理-企业资料
     * 
     * @return void
     */
	public function companyAction()
	{
		$type = $this->getRequest()->getParam('type');
        $cid = $this->_sessCompany->manageCid;
        $db = CompanyShowModel::_dao();
        $row = $db->fetchRow('SELECT COUNT(`pid`) AS `numrows` FROM `tbl_corp_company_show` WHERE `cid` = ?', $cid);
		switch ($type)
		{
            case 'prodmanage': // 产品管理
            {
                $page = $this->_getParam('p', 1);
                Page::create(array(
                    'href_open' => '<a href="/biz/manage/company/type/prodmanage/?p=%d">',
                    'href_close' => '</a>',
                    'num_rows' => $row['numrows'],
                    'cur_page' => $page
                ));
                
                $this->view->prods = $db->fetchAll('SELECT * FROM `tbl_corp_company_show`
                                       WHERE `cid` = "'.$cid.'" ORDER BY `pid` DESC LIMIT '.Page::$offset.','.Page::$pagesize);
                
                $this->view->pagination = Page::$page_str;
            }
            case 'production': // 产品上传
            {
                $sorts = $db->fetchAll('SELECT DISTINCT `sort` FROM `tbl_corp_company_show` WHERE `cid` = ?', $cid);
                $this->view->sorts = $sorts;
                break;
            }
			case 'contact': // 联系方式
			{
    			break;
			}
			case 'biz': // 商务供求
			{
    			break;
			}
			case 'logo': // 企业标志
			{
    			break;
			}
			default: // 基础信息
			{
				$type = 'basic';
			}
		}
        
        $this->view->cid = $cid;
        $this->view->prod_num = $row['numrows'];
		$this->view->headTitle($this->_iniCompany->head->titleManageCompany);
		$this->view->headScript()->appendFile('/static/scripts/biz/manage/company.js');
		$this->view->action = 'company';
		$this->view->type   = $type;
		$this->_helper->layout->setLayout('manage');
		$this->getResponse()->insert('nav', $this->view->render('manage-nav.phtml'));
		$this->render("company-{$type}");
	}

    public function dodeleteAction()
    {
        $R = $this->getRequest();
        if($R->isPost())
        {
            $img = $R->getPost('img');
            $cid = $this->_sessCompany->manageCid;
            $root = $_SERVER['DOCUMENT_ROOT'].'/static/bizs/'.$cid;
            if(unlink($root.'/'.$img) == true)
            {
                $dao = CompanyShowModel::_dao();
                $dao->delete('tbl_corp_company_show','pid = '.(int)$R->getPost('pid'));
                echo 'success';
            }
        }
    }

    public function domodifyAction()
    {
        $R = $this->getRequest();
        
        if($R->isPost())
        {
            // 先对表单数据进行检查
            $V = new Lp_Valid();
            $sort = $V->of($R->getPost('sort'), 'sort', '产品分类', 'trim|strip_tags|required');
            $name = $V->of($R->getPost('name'), 'name', '产品名称', 'trim|strip_tags|required');
            $intro = $V->of($R->getPost('intro'), 'intro', '产品介绍', 'trim|required');
            if($V->getMessages() != false)
            {
                echo $V->getMessages('*',"\n");
            }
            else
            {
                $dao = CompanyShowModel::_dao();
                $data = array(
                    'sort' => $sort,
                    'name' => $name,
                    'intro' => $intro
                );
                $dao->update('tbl_corp_company_show', $data, '`pid` = '.(int)$R->getPost('pid'));
                echo '修改成功';
            }
        }
    }

    public function douploadAction()
    {
        $this->getHelper('viewRenderer')->setNoRender();
        $this->getHelper('layout')->disableLayout();
        $R = $this->getRequest();
        if($R->isPost())
        {
            // 生成上传位置
            $cid = $this->_sessCompany->manageCid;
            $root = $_SERVER['DOCUMENT_ROOT'].'/static/bizs/'.$cid;
            if(!file_exists($root))
            @mkdir($root, 0777);
            
            // 先对表单数据进行检查
            $V = new Lp_Valid();
            $sort = $V->of($R->getPost('sort'), 'sort', '产品分类', 'trim|strip_tags|required');
            $sort_n = $V->of($R->getPost('sort_n'), 'sort_n', '新产品分类', 'trim|strip_tags');
            $name = $V->of($R->getPost('name'), 'name', '产品名称', 'trim|strip_tags|required');
            $intro = $V->of($R->getPost('intro'), 'intro', '产品介绍', 'trim|required');
            if($sort_n) $sort = $sort_n;
            if($V->getMessages() != false)
            {
                echo '<script>parent.setTip("<div class=\'tip\'>'.$V->getMessages().'</div>",0);</script>';
            }
            else
            {
                // 图片上传
                Lp_Upload::init(array(
                    'max_size' => 500,
                    'allow_type' => 'jpg|gif|png',
                    'upload_path' => $root
                ));
                if(!Lp_Upload::handle('img'))
                {
                    echo '<script>parent.setTip("<div class=\'tip\'>'.Lp_Upload::getTip().'</div>",0);</script>';
                }
                else
                {
                    $img = Lp_Upload::fetchParam('file_name');
                    // 进行数据库写入
                    $dao = CompanyShowModel::_dao();
                    $data = array(
                        'cid' => $cid,
                        'sort' => $sort,
                        'uid' => USER_UID,
                        'img' => $img,
                        'name' => $name,
                        'intro' => $intro
                    );
                    $dao->insert('tbl_corp_company_show', $data);
                    echo '<script>
                    parent.setTip("<div class=\'tip\'>成功加入</div>",1);
                    </script>';
                }
            }
        }
    }

	/**
     * 企业管理-基础信息
     * 
     * @return void
     */
	public function dobasicAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['cid'] = $this->_sessCompany->manageCid;
			$postArgs['uid'] = USER_UID;

			if ($basicArgs = ManageFilter::init()->basic($postArgs))
			{
				(!CorpCompanyLogic::init()->updateBasic($basicArgs) ? '' : 
				    CacheLogic::init()->companySave(array_merge($this->_dataCompany, $basicArgs), $basicArgs['cid']));

				if ($basicArgs['industry'] != $this->_dataCompany['industry'])
				{
					// 减少旧选行业企业总数
            		CorpIndustryLogic::init()->insertOrUpdate(array(
            		    'count' => new Zend_Db_Expr('count - 1'), 'iid' => $this->_dataCompany['industry'])
            		);

					// 增加新选行业企业总数
            		CorpIndustryLogic::init()->insertOrUpdate(array(
            		    'count' => new Zend_Db_Expr('count + 1'), 'iid' => $basicArgs['industry'])
            		);
				}

				$this->_sessCompany->message = $this->_iniCompany->hint->updateSuccess;

				echo 'message'; // 请求ajax弹出提示
			}
		}
	}

	/**
     * 企业管理-联系方式
     * 
     * @return void
     */
	public function docontactAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['cid'] = $this->_sessCompany->manageCid;
			$postArgs['uid'] = USER_UID;

			if ($contactArgs = ManageFilter::init()->contact($postArgs))
			{
				(!CompanyContactLogic::init()->updateContact($contactArgs) ? '' : 
				    CacheLogic::init()->companySave(array_merge($this->_dataCompany, $contactArgs), $contactArgs['cid']));

				$this->_sessCompany->message = $this->_iniCompany->hint->updateSuccess;

				echo 'message'; // 请求ajax弹出提示
			}
		}
	}

	/**
     * 企业管理-商务供求
     * 
     * @return void
     */
	public function dobizAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['cid'] = $this->_sessCompany->manageCid;
			$postArgs['uid'] = USER_UID;

			if ($bizArgs = ManageFilter::init()->biz($postArgs))
			{
				(!CompanyBizLogic::init()->updateBiz($bizArgs) ? '' : 
				    CacheLogic::init()->companySave(array_merge($this->_dataCompany, $bizArgs), $bizArgs['cid']));

				$this->_sessCompany->message = $this->_iniCompany->hint->updateSuccess;

				echo 'message'; // 请求ajax弹出提示
			}
		}
	}

	/**
     * 企业管理-企业标志
     * 
     * @return void
     */
	public function dologoAction()
	{
	    $this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		$companyRoot = DOCUMENT_ROOT.Commons::getCompanyFolder($this->_sessCompany->manageCid);
		Upload::init(array('max_size' => 55, 'cust_name' => 'original', 'overwrite' => true, 
		    'allow_type' => 'jpg|jpeg|gif|png', 'upload_path' => $companyRoot));

		if(Upload::handle('fileData')) // 上传成功
		{
			$ext   = Upload::fetchParam('file_ext'); // 获取扩展名,带.
			$image = new ImageHandle("{$companyRoot}original{$ext}"); // 原图
			$image->abs_resize(150, 60, $companyRoot.'medium'); // 中图

            // 若是jpg/jpeg则强制转换类型
            if ('.jpg' == $ext || '.jpeg' == $ext)
			{
				$image = new ImageHandle("{$companyRoot}original{$ext}");
                $image->output($companyRoot.'original', 'gif');
                $image->init("{$companyRoot}medium{$ext}");
                $image->output($companyRoot.'medium', 'gif');
			}

            // 若是png则强制转换类型
            if ('.png' == $ext)
			{
				$image = new ImageHandle("{$companyRoot}original{$ext}");
                $image->output($companyRoot.'original', 'gif');
                $image->init("{$companyRoot}medium{$ext}");
                $image->output($companyRoot.'medium', 'gif');
			}

			// 标记企业标志已上传,可读取标志图片
			(!CorpCompanyLogic::init()->updateFace($this->_sessCompany->manageCid) ? '' : 
			    CacheLogic::init()->companySave(array_merge($this->_dataCompany, array('face' => 'Y')), $this->_sessCompany->manageCid));

			echo 'message';

		} else {

            //file_put_contents('BizUploadException.log', Upload::getTip());exit;
			echo 'failure'; 

		}
	}
}
