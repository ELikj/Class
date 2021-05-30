<?php
function upload()
{    $ext_arr = array(
        'friend' => array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'mp4'),
        'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
        'flash' => array('swf', 'flv'),
        'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb', 'mp4'),
        'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2', '7z'),
        'all' => array('gif', 'bmp', 'jpg', 'jpeg', 'png', 'swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2', '7z', 'mp4')
    );
    if (isset($_GET['uplx']) && isset($ext_arr[$_GET['uplx']])) $LX = $_GET['uplx'];
    else  $LX = 'all';
    global $ELiConfig, $LANG;
    $max_size = isset($_GET['maxsize']) && $ELiConfig['maxsize'] >= $_GET['maxsize'] &&  $_GET['maxsize'] > 10 ? $_GET['maxsize'] : $ELiConfig['maxsize'];
    if (!isset($_FILES[$LX])) {
        return  array('code' => '0', 'msg' => $LANG['update']['meiwenjian']);
    }
    if (!empty($_FILES[$LX]['error'])) {
        switch ($_FILES[$LX]['error']) {
            case '1':
                $error = $LANG['update']['error1'];
                break;
            case '2':
                $error = $LANG['update']['error2'];
                break;
            case '3':
                $error = $LANG['update']['error3'];
                break;
            case '4':
                $error = $LANG['update']['error4'];
                break;
            case '6':
                $error = $LANG['update']['error6'];
                break;
            case '7':
                $error = $LANG['update']['error7'];
                break;
            case '8':
                $error = $LANG['update']['error8'];
                break;
            case '999':
            default:
                $error =  $LANG['update']['error999'];
        }
        return  array('code' => '0', 'msg' => $error);
    }
    $qianzui = 'attachment/' . $LX . '/' . date('Ym') . '/';
    $files =  $ELiConfig['dir'] . $qianzui;
    if (empty($_FILES) === false) {
        $file_name = $_FILES[$LX]['name'];
        $tmp_name  = $_FILES[$LX]['tmp_name'];
        $file_size = $_FILES[$LX]['size'];
        if (!$file_name) return  array('code' => '0', 'msg' => $LANG['update']['meiwenjian']);
        if ($file_size > $max_size) return array('code' => '0', 'msg' => $LANG['update']['maxsizeda']);
        $temp_arr = explode(".", $file_name);
        $file_ext = array_pop($temp_arr);
        $file_ext = trimE($file_ext);
        $file_ext = strtolower($file_ext);
        $expa = array_flip($ext_arr[$LX]);
        if (!isset($expa[$file_ext])) {
            return  array('code' => '0', 'msg' => $LANG['update']['shangchuanyun'] . implode(',', $ext_arr[$LX]));
        }
        $Nfile =  date('d') . '_' . ELimm(time() . rand(1, 9999999)) . '.' . $file_ext;
        $returnfile = $files . $Nfile;
        $tmpneirong = file_get_contents($tmp_name);
        if (strpos(strtolower($tmpneirong), '<?php') !== false) {
            return  array('code' => 0, 'msg' => $LANG['update']['meiwenjian']);
        }
        if (strpos(substr($tmpneirong, 0, 50), ';base64,') !== false) {
            $nnn = explode(';base64,', $tmpneirong);
            $tmpneirong = base64_decode($nnn['1']);
            file_put_contents($tmp_name,$tmpneirong );
        }

        $request_url = "upload-z2.qiniup.com";
        $AK = 'm';//	AccessKey
        $SK = 'X';//SecretKey
        $tongname = '';//桶名字


        $returnfile_ = ltrimE( $returnfile,'/');
        $putPolicy = [
            'scope'=> $tongname.":".$returnfile_,
            'deadline'=>time()+3600
        ];
        $encodedPutPolicy =  str_replace(['+','/'],['-','_'],  base64_encode(json_encode($putPolicy)));
        $sign = hash_hmac('sha1', $encodedPutPolicy,  $SK,true);
        $encodedSign =str_replace(['+','/'],['-','_'],  base64_encode($sign ));
        $upload_token =  $AK. ':' .$encodedSign.':'.$encodedPutPolicy;

        $NEIRONG = [
            'key'=>$returnfile_,
            'token'=> $upload_token,
            'file'=>new \CURLFile($tmp_name)
        ];

        $fan =  ELipost("https://".$request_url , $NEIRONG);

       
        if ($fan  == "" || strstr( $fan ,"hash") === false) {
            return  array('code' => 0, 'msg' => $LANG['update']['meiwenjian']);
        }

        if (is_file($tmp_name)) {
            @unlink($tmp_name);
        }

        if (!defined("Residentmemory")) {
            if (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE")) header('Content-type:text/html; charset=UTF-8');
            else  header('Content-type:application/json ;charset=UTF-8');
        }
        return  array('code' => 1, 'content' =>  array('pic' => $returnfile, 'size' => $file_size, 'houzui' => $file_ext));
    }
}