<?php

class Zend_View_helper_Upload
{
    function upload($userfile, $maxsize, $allowtype, $gid)
    {
        if (!isset($_FILES[$userfile]))
		{
			return '
				<form enctype="multipart/form-data" target="doUpload" action="/group/file/upload?gid='.$gid.'" method="post">
				<p><label>附件</label> <input type="file" name="'.$userfile.'" />
				<input type="submit" value="上传" /> 
				<span class="quiet">(文件名禁用中文，有效类型:'.$allowtype.', 大小限制:'.$maxsize.'KB)</span></p>
				</form>
                <p id="file_list"></p>
				<iframe id="doUpload" class="hide" name="doUpload"></iframe>
				';
		}
		else 
		{
			$upload_path = $_SERVER['DOCUMENT_ROOT'].'/static/groups/'.$gid.'/files/';
			if(!file_exists($upload_path))
			@mkdir($upload_path, 0777);
			Lp_Upload::init(array(
				'max_size' => $maxsize,
				'allow_type' => $allowtype,
				'upload_path' => $upload_path
			));
			$result = Lp_Upload::handle($userfile);
            $file_name = Lp_Upload::fetchParam('file_name');
            if(false == $result)
            {
                return '<script>alert("'.Lp_Upload::getTip().'");</script>';
            }
            else
            {
                return '<script type="text/javascript">
                    parent.KE_CUSTOM_STR("<a href=\'/static/groups/'.$gid.'/files/'.$file_name.'\'>下载文件：'.$file_name.'</a>");
                    parent.file_list("<a href=\'/static/groups/'.$gid.'/files/'.$file_name.'\'>'.$file_name.'</a>");
                </script>';
            }
		}
    }
}

?>