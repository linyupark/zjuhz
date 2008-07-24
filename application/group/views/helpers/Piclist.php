<?php

/* 图片列表显示 */

class Zend_View_Helper_Piclist
{
    function piclist($gid, $pagesize, $page, $pagination = false)
    {
        $result = GroupAlbumModel::index($gid, $pagesize, $page);
        $str = '<h3 class="pd5 mg10">相册图片列表
        <small class="f12" style="font-weight:normal">共
        <span style="color:red">'.$result['numrows'].'</span> 张图片</small>
        <a style="float:right; margin-top:-15px;" href="/group/album/new?gid='.$gid.'">发新图片</a></h3>';
        if($result['numrows'] == 0) $str .= '<p class="mglf10">相册中没有图片</p>';
        else
        {
            foreach($result['rows'] as $pic)
            {
                $new = '';
                if((time() - $pic['pubtime']) < 3600*18)
                $new = Cmd::icon('new.png');
            	$str .= '<div style="float:left; padding:10px 30px;">
                <a href="/group/album/show?gid='.$gid.'&aid='.$pic['album_id'].'"><img src="'.$this->getPic($gid, 'sample_'.$this->tolower($pic['file']), $pic['pubtime']).'" /></a>
                <p>'.$new.' 发布人: <a href="/group/member/profile?uid='.$pic['user_id'].'">'.UserModel::fetch($pic['user_id'], 'realName').'</a></p></div>
                ';
            }
            
            // 是否显示分页
            if($pagination != false)
            {
                Page::$pagesize = $pagesize;
                Page::create(array(
                    'href_open' => '<a href="/group/album?gid='.$gid.'&p=%d">',
                    'href_close' => '</a>',
                    'num_rows' => $result['numrows'],
                    'cur_page' => $page
                ));
                $str .= '<div class="pagination">'.Page::$page_str.'</div>';
            }
        }
        return $str;
    }
    
    function getPic($gid, $file, $time)
    {
        return '/static/groups/'.$gid.'/images/'.date('y_m_d', $time).'/'.$file;
    }
    
    function tolower($str)
    {
        $EXT = array('JPG','JPEG','PNG','GIF');
        $ext = array('jpg','jpeg','png','gif');
        return str_replace($EXT, $ext, $str);
    }
}

?>