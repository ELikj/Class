<?php

        $CDN = '';
        $APID = "idd";
        $APKY = "密码";
        $NEIRONG = $tmpneirong ;
        $duankou = "chongchongzhengba";
        $cmd5 = base64_encode(md5($NEIRONG, true));
        $shijia = gmdate('D, d M Y H:i:s \G\M\T',time());
        $REST   = 'PUT';
        $LUJIN = $returnfile;
        $request_url = "http://chongchongzhengba.oss-cn-hangzhou.aliyuncs.com";
        $string_to_sign =  $REST."\n".
            $cmd5."\n" .
            "application/octet-stream\n" .
            $shijia."\n" .
            "/".trimE( $duankou ,'/').'/'.trimE($LUJIN,'/');
        $qianm = base64_encode(hash_hmac('sha1', $string_to_sign,  $APKY, true));
        $temp_headers = array( 
            'Content-MD5: '.$cmd5,
            'Content-Type: application/octet-stream',
            'Authorization: OSS '.$APID.':'.($qianm),
            'Date: '.$shijia,
            'Host: chongchongzhengba.oss-cn-hangzhou.aliyuncs.com',
            'Content-Length: '.strlen($NEIRONG)
        );
        $fan = ELipost($request_url.$LUJIN,$NEIRONG,[
            CURLOPT_HTTPHEADER =>$temp_headers,
            CURLOPT_USERAGENT=>"RequestCore/1.4.3",
            CURLOPT_CUSTOMREQUEST=>"PUT"
        ]);


        if($fan != ""){
            return  array( 'code'=> 0, 'msg' => $LANG['update']['meiwenjian']);
        }
        
        