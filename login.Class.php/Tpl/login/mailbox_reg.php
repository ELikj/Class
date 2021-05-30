<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 *
 * mailbox 邮箱
 * password登陆密码
 * fcode   发送的二维码
 * code    图形验证码
*/
$mailbox  =  $_POST['mailbox'] ?? "";
$password =  $_POST['password'] ?? "";
$fcode    =  $_POST['fcode'] ?? "";
$code     =  $_POST['code'] ?? "";

if( !$THIS ->Isemail ($mailbox) ){
    return echoapptoken([],-1, $THIS ->Lang('mailbox_error'));
}

if(strlen($password ) < 6 || strlen($password ) > 64){
    return echoapptoken([],-1, $THIS ->Lang('password_error') );
}

$GetCode = $THIS ->GetCode($mailbox);
if(!$GetCode){
    return echoapptoken([],-1, $THIS ->Lang("fasong_code") );
}

if((int)$GetCode != (int)$fcode){
    return echoapptoken([],-1,$THIS ->Lang( "code_error") );
}

$codeoff  = Plusconfig("mailbox验证码")??0;
if($codeoff > 0){
    if( $code == '' ||  $code != ELihhGet('code')){
        return echoapptoken([],-1, $THIS ->Lang('vcode_error') );
    }
}

$db = db("login_mailbox");
$user = $db ->where( [ 'mailbox' => $mailbox ] )->find();
if($user){
    return echoapptoken([],-1, $THIS ->Lang("mailbox_ok") );
}

$THIS ->DelCode($mailbox);
$chauid = ELihhGet("uid");
if($chauid){
    $chaxun = $db ->where( [ 'uid' => $chauid ] )->find();
    if($chaxun){
        return echoapptoken([],-1, $THIS ->Lang("account_false") ); 
    }else{
        $uid = $chauid;
    }
}else{
    $uid = $THIS -> REG( 
        [
            "name"=>$mailbox,
            "sex"=>-1,
            "tou"=> "",
            "ip"=>ip(),
            'tuid'=>ELihhGet('tuid')??0
        ],$features);
}
if(!$uid){
    return echoapptoken([],-1, $THIS ->Lang("uuid_error") );
}
$reg =[
    'mailbox'=> $mailbox,
    'password'=>  ELimm($password),
    'uid' => $uid
];
$fan = $db ->insert($reg);
if($fan){
    ELihhSet(['uid'=>$uid]);
    return echoapptoken([], 1, $THIS ->Lang('reg_true') );
}else{
    return echoapptoken([],-1, $THIS ->Lang('reg_false') );
}