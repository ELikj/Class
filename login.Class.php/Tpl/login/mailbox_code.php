<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 */
$mailbox  =  $_POST['mailbox'] ?? "";
$password =  $_POST['password'] ?? "";
$code  =  $_POST['code'] ?? "";
$TYPE =  $_POST['type'] ?? "reg"; //reg login
$MOBAN = [
    'reg'=>'注册模版',
    'findback'=>'找回模版',
    'login'=>'登陆模版'
];

if( ! $THIS ->Isemail ($mailbox) ){
    return echoapptoken([],-1,$THIS ->Lang("mailbox_error") ) ;
}

if( ( strlen($password ) < 6 || strlen($password ) > 64  ) && $TYPE != "login" ){
    return echoapptoken([],-1,$THIS ->Lang("password_error"));
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
    if( $TYPE == "reg" ){
        return echoapptoken([],-1,$THIS ->Lang("mailbox_ok") );
    } 
}else{
    if($TYPE  == 'findback'){
        return echoapptoken([],-1,$THIS ->Lang("mailbox_no") );
    } 
}

$GetCode = $THIS ->GetCode($mailbox);
if($GetCode){
    return echoapptoken([],-1,$THIS ->Lang("fasong_ok") );
}

$fan = $THIS ->Fasong(['type'=>1 , 'zhanghao' => $mailbox , 'leixing' =>  $MOBAN[$TYPE]??'注册模版' ],$features);
if($fan){
    return echoapptoken([],1, $THIS ->Lang("fasong_true") );
}else{
    return echoapptoken([],1, $THIS ->Lang("fasong_false") );
}