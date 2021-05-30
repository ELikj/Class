<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 *
 * phone  手机号
 * password 登陆密码
 * fcode    发送的二维码
 * code     图形验证码
*/
$mailbox  =  $_POST['phone'] ?? "";
$password =  $_POST['password'] ?? "";
$fcode    =  $_POST['fcode'] ?? "";
$code     =  $_POST['code'] ?? "";

if( ! $THIS ->Isshouji ($mailbox) ){
    return echoapptoken([],-1,$THIS ->Lang("phone_error") ) ;
}
if( strlen($password ) < 6 || strlen($password ) > 64 ){
    return echoapptoken([],-1,$THIS ->Lang("password_error"));
}

$HASH = "security/".ip();
if($ELiMem ->g($HASH) > $THIS ->securitynum){
    return echoapptoken([],-1, $THIS ->Lang("phone_no") );
}

$codeoff  = Plusconfig("phone验证码")??0;
if($codeoff > 0){
    if( $code == '' ||  $code != ELihhGet('code')){
        return echoapptoken([],-1, $THIS ->Lang('vcode_error') );
    }
}

$GetCode = $THIS ->GetCode($mailbox);
if(!$GetCode){
    return echoapptoken([],-1, $THIS ->Lang("fasong_code") );
}

if((int)$GetCode != (int)$fcode){
    return echoapptoken([],-1,$THIS ->Lang( "code_error") );
}

$db = db("login_phone");
$user = $db ->where( [ 'phone' => $mailbox ] )->find();
if(!$user){

    $ELiMem ->ja($HASH,1,$THIS ->securitytime);
    return echoapptoken([],-1,$THIS ->Lang("phone_no") );
    
}else{
    if($user['password'] == ELimm( $password ) ){
        return echoapptoken([],-1, $THIS ->Lang("password_ok") );
    }
}

$ELiMem ->d($HASH);
$THIS ->DelCode($mailbox);
$fan = $db ->where( [ 'phone' => $mailbox ] )->update( [ 'password' => ELimm($password)] );
if($fan){
    ELilog('userlog',$user['uid'],3,[ 'yuan'=>$user['password'],'data'=>  ELimm($password) ],'phone_findback',"login");
    return echoapptoken([], 1, $THIS ->Lang("password_upok") );
}else{
    return echoapptoken([],-1, $THIS ->Lang("password_upno") );
}