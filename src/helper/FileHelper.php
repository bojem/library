<?php
namespace q\helper;
class FileHelper
{
    /**
     * 文件尺寸大小换算
     * @param unknown $size
     * @return string
     */
    public function size_conversion($size_num)
    {

        switch ($size_num) {
            case $size_num >= 1073741824:
                $size_str = round($size_num / 1073741824 * 100) / 100 . ' GB';
                break;
            case $size_num >= 1048576:
                $size_str = round($size_num / 1048576 * 100) / 100 . ' MB';
                break;
            case $size_num >= 1024:
                $size_str = round($size_num / 1024 * 100) / 100 . ' KB';
                break;
            default:
                $size_str = $size_num . ' Bytes';
                break;
        }
        return $size_str;
    }

    /**
     * 文件强制下载
     * @param unknown $dir
     */
    public function dir_readfile($dir)
    {

        if (file_exists($dir)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($dir));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($dir));
            ob_clean();
            flush();
            readfile($dir);
        }
    }

    /**
     * 删除指定目录下的文件和文件夹
     * @param unknown $dirpath
     * @return boolean
     */
    public function del_dir($dirpath)
    {
        $dh = opendir($dirpath);
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {
                $fullpath = $dirpath . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->del_dir($fullpath);
                    rmdir($fullpath);
                }
            }
        }
        closedir($dh);
        $isEmpty = true;
        $dh = opendir($dirpath);
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {
                $isEmpty = false;
                break;
            }
        }
        return $isEmpty;
    }

    /**
     * 下载微信头像
     * @param $avatarUrl
     * @param $newFilePath
     * @param string $fileName
     * @return bool|string
     */
    public static function downloadWechatHead($avatarUrl, $newFilePath, $fileName = '')
    {
        $header = array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',
            'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding: gzip, deflate',);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $avatarUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($code == 200) {//把URL格式图片转成base64_encode格式！
            $imgBase64Code = "data:image/jpeg;base64," . base64_encode($data);
        }
        $img_content=$imgBase64Code;//图片内容
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)) {
            $type = $result[2];//得到图片类型png?jpg?gif?
            self::makeDir($newFilePath);
            $fileName = !empty($fileName) ? $fileName : StringHelper::generateCode(16);
            $newFile = $newFilePath . $fileName . '.' . $type;
            if (file_put_contents($newFile, base64_decode(str_replace($result[1], '', $img_content)))) {
                return $newFile;
            }
        }
        return false;
    }

    /**
     * 创建目录
     * @param $dir
     * @return string
     */
    public static function makeDir($dir): string
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    /**
     * 远程图片下载工具
     * @param string $url  远程图片url地址
     * @param string $save_dir 要保存的目录
     * @param string $filename 文件名
     * @param int $type
     * @return array
     */
    public static function downLoadImg($url, $save_dir='', $filename='', $type=0){
        if(trim($url)==''){
            return array('file_name'=>'','save_path'=>'','error'=>1);
        }
        if(trim($save_dir)==''){
            $save_dir='./';
        }
        if(trim($filename)==''){//保存文件名
            $ext=strrchr($url,'.');
            if($ext!='.gif'&&$ext!='.jpg'){
                return array('file_name'=>'','save_path'=>'','error'=>3);
            }
            $filename=time().$ext;
        }
        if(0!==strrpos($save_dir,'/')){
            $save_dir.='/';
        }
        //创建保存目录
        if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
            return array('file_name'=>'','save_path'=>'','error'=>5);
        }
        //获取远程文件所采用的方法
        if($type){
            $ch=curl_init();
            $timeout=300;
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
            $img=curl_exec($ch);
            curl_close($ch);
        }else{
            ob_start();
            readfile($url);
            $img=ob_get_contents();
            ob_end_clean();
        }
        //$size=strlen($img);
        //文件大小
        $fp2=@fopen($save_dir.$filename,'a');
        fwrite($fp2,$img);
        fclose($fp2);
        unset($img,$url);
        return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
    }
}




