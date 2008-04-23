<?php

/**
 * @category   zjuhz.com
 * @package    common
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:Paging.php
 */

/**
 * 公共分页类
 */
class Paging 
{
	/**
     * 标签名
     *
     * @var string
     */
	public $pageName  = 'p';

	/**
     * 首页
     *
     * @var string
     */
	public $firstPage = null;

	/**
     * 末页
     *
     * @var string
     */
	public $lastPage  = null;

	/**
     * 控制记录条的个数
     *
     * @var string
     */
	private $_pageBarNum = 10;

	/**
     * 总页数
     *
     * @var string
     */
	private $_totalPage  = 0;

	/**
     * 当前页
     *
     * @var string
     */
	private $_nowIndex = 1;

	/**
     * url地址头
     *
     * @var string
     */
	private $url = null;


		 function Paging($array)
		 {
			 if(is_array($array))
			 {
				 if(!array_key_exists('total',$array))$this->error(__FUNCTION__,'need a param of total');
			     $total=intval($array['total']);
			     $perpage=(array_key_exists('perpage',$array))?intval($array['perpage']):10;
			     $_nowIndex=(array_key_exists('_nowIndex',$array))?intval($array['_nowIndex']):'';
				 $url=(array_key_exists('url',$array))?$array['url']:'';
			 }
			 else
			 {
				 $total=$array;
				 $perpage=10;
			     $_nowIndex='';
			     $url='';
			 }
			 if((!is_int($total))||($total<0))$this->error(__FUNCTION__,$total.' is not a positive integer!');
			 if((!is_int($perpage))||($perpage<=0))$this->error(__FUNCTION__,$perpage.' is not a positive integer!');
			 if(!empty($array['pageName']))$this->set('pageName',$array['pageName']);//设置pagename
			 $this->_set__nowIndex($_nowIndex);//设置当前页
			 $this->_set_url($url);//设置链接地址
			 $this->_totalPage=ceil($total/$perpage);
			 $this->perpage=$perpage;
			 $this->total=$total;

		    $this->firstPage=1;
		    $this->lastPage=$this->_totalPage;

		 }
		 
		 /*
		  * 获取显示“首页”的代码
		  *
		  * @return string
		  */
		 function firstPage($style='')
		 {
			 if($this->_nowIndex-5<1)
			 {
				 return '';
			     //return '<span class="'.$style.'">'.$this->firstPage.'</span>';
			 }
			 else
			 {
				 $str = '&nbsp;...&nbsp;';
			 }
			 $str = $this->_get_link($this->_get_url(1),$this->firstPage,$style) . $str ;
			 return $str;
		 }
		 		
		 /*
		  * 获取显示“尾页”的代码
		  *
		  * @return string
		  */
		 function lastPage($style='')
		 {
			 if($this->_nowIndex+5>=$this->_totalPage)
			 {
				 return ;
			     //return '<span class="'.$style.'">'.$this->lastPage.'</span>';
			 }
			 return '&nbsp;...&nbsp;'.$this->_get_link($this->_get_url($this->_totalPage),$this->lastPage,$style);
		 }
		 
		 function nowbar($style='',$_nowIndex_style='')
		 {
			 $plus=ceil($this->_pageBarNum/2);
			 if($this->_pageBarNum-$plus+$this->_nowIndex>$this->_totalPage)$plus=($this->_pageBarNum-$this->_totalPage+$this->_nowIndex);
			 $begin=$this->_nowIndex-$plus+1;
			 $begin=($begin>=1)?$begin:1;
			 $return='';
			 for($i=$begin;$i<$begin+$this->_pageBarNum;$i++)
			 {
				 if($i<=$this->_totalPage){
				    if($i!=$this->_nowIndex)
			        $return.=$this->_get_text($this->_get_link($this->_get_url($i),$i,$style));
			     else 
			        $return.=$this->_get_text('<span class="'.$_nowIndex_style.'">'.$i.'</span>');
				 }else{
				    break;
				 }
			    $return.="\n";
			  }
			  unset($begin);
			  return $return;
		 }
		
		 /*
		  * 获取显示跳转按钮的代码
		  *
		  * @return string
		  */
		 function select()
		 {	
			 $return='<select name="Page_Select" >';
			 for($i=1;$i<=$this->_totalPage;$i++)
			 {
				 if($i==$this->_nowIndex){
				    $return.='<option value="'.$i.'" selected>'.$i.'</option>';
			     }else{
				    $return.='<option value="'.$i.'">'.$i.'</option>';
				 }
			 }
			 unset($i);
		    $return.='</select>';
			return $return;
		 }
 
		 /*
		  * 控制分页显示风格（你可以增加相应的风格）
		  *
		  * @param int $mode
		  * @return string
		  */
		function show()
		{
			$pagestr = '<div class="pagingnavi" id="lopage">有<span class="num">'.$this->total.'</span>条记录';
			$pagestr.= '&nbsp;&nbsp;&nbsp;共<span class="num">'.$this->_totalPage.'</span>页';
			
			if ($this->_totalPage > 1) //只有大1页时才显示分页按钮
			{
				$pagestr.= '&nbsp;&nbsp;|&nbsp;&nbsp;';
				$pagestr.= $this->firstPage().' ';
				$pagestr.= $this->nowbar('','curr');
				$pagestr.= $this->lastPage();
			}
			
			$pagestr.= '</div>';
			return $pagestr;
		}
		 
		 /*----------------private function (私有方法)-----------------------------------------------------------*/
		 /*
		  * 设置url头地址
		  * @param: String $url
		  * @return boolean
		  */
		 function _set_url($url="")
		 {
			 if(!empty($url))
			 {
				 //手动设置
			     $this->url=$url.((stristr($url,'?'))?'&':'?').$this->pageName."=";
			 }
			 else
			 {
				 //自动获取
			     if(empty($_SERVER['QUERY_STRING']))
				 {
					 //不存在QUERY_STRING时
					 $this->url=$_SERVER['REQUEST_URI']."?".$this->pageName."=";
				 }
				 else
				 {
					 //
				     if(stristr($_SERVER['QUERY_STRING'],$this->pageName.'='))
					 {
						 //地址存在页面参数
					     $this->url=str_replace($this->pageName.'='.$this->_nowIndex,'',$_SERVER['REQUEST_URI']);
						 $last=$this->url[strlen($this->url)-1];

					     if($last=='?'||$last=='&')
						 {
							 $this->url.=$this->pageName."=";
					     }
						 else
						 {
							 $this->url.='&'.$this->pageName."=";
					     }
					 }
					 else
					 {
						 //
						$this->url=$_SERVER['REQUEST_URI'].'&'.$this->pageName.'=';
 					 }//end if    
				 }//end if
			 }//end if
		 }
 
		 /*
	  	  * 设置当前页面
	 	  *
		  */
	 	 function _set__nowIndex($_nowIndex)
		 {
			 if(empty($_nowIndex)){
		     //系统获取
				   
			 if(isset($_GET[$this->pageName])){
			    $this->_nowIndex=intval($_GET[$this->pageName]);
			 }
			 }else{
		     //手动设置
			 $this->_nowIndex=intval($_nowIndex);
			}
		 }
		
		 /*
		  * 为指定的页面返回地址值
		  *
		  * @param int $pageno
		  * @return string $url
		  */
		 function _get_url($pageno=1)
		 {
			 return $this->url.$pageno;
		 }
		  
		/*
		 * 获取分页显示文字，比如说默认情况下_get_text('<a href="">1</a>')将返回[<a href="">1</a>]
		 *
		 * @param String $str
		 * @return string $url
		 */ 
		 function _get_text($str)
		 {
		 	  return $str;
		 }
		 
		 /*
		  * 获取链接地址
		  */
		 function _get_link($url,$text,$style='')
		 {
			 $style=(empty($style))?'':'class="'.$style.'"';
			 return '<a '.$style.' href="'.$url.'">'.$text.'</a>';
		 }

		 /*
		  * 出错处理方式
		  */
		 function error($function,$errormsg)
		 {
			 die('Error in file <b>'.__FILE__.'</b> ,Function <b>'.$function.'()</b> :'.$errormsg);
		 }

		function limit()
		{
			$num	 = ($this->_nowIndex-1)*$this->perpage; //计算当前取值起点
			if ($num <= 0 OR $this->_nowIndex>$this->total)
			{
				$num = 0;
			}
			$limitSql = $num.','.$this->perpage;
			return $limitSql;
		}
}
