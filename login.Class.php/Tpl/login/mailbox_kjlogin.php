<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 */
/*
mailbox  登陆账号 2-32
password 登陆密码 6-64
code     验证码
*/
$account  =  $_POST['mailbox'] ?? "";
$fcode    =  $_POST['fcode'] ?? "";
$code     =  $_POST['code'] ?? "";

$chauid = ELihhGet("uid");
if($chauid && $chauid > 0){
    $USER = uid( $chauid );
    if(!$USER || $USER['accountoff'] != 1){
        return echoapptoken([],-1, $THIS ->Lang("accountoff"),$SESSIONID   );
    }
    $USER['avatar'] = pichttp($USER['avatar']==""?"Tpl/login/avatar/".($USER['id']%10).".png":$USER['avatar']);
    return echoapptoken($USER,1, $THIS ->Lang("login_true"),$SESSIONID  );
}

if( !$THIS ->Isemail ($account) ){
    return echoapptoken([],-1, $THIS ->Lang('mailbox_error'));
}


$codeoff  = Plusconfig("mailbox验证码")??0;
if($codeoff > 0){
    if( $code == '' || $code != ELihhGet('code') ){
        return echoapptoken([],-1, $THIS ->Lang('vcode_error') );
    }
}

$HASH = "security/".ip();
if( (int)$ELiMem ->g($HASH) > $THIS ->securitynum){
    return echoapptoken([],-1, $THIS ->Lang("mailbox_no")  );
}
$GetCode = $THIS ->GetCode($account);
if(!$GetCode){
    return echoapptoken([],-1, $THIS ->Lang("fasong_code") );
}

if((int)$GetCode != (int)$fcode){
    return echoapptoken([],-1,$THIS ->Lang( "code_error") );
}

$db = db("login_mailbox");
$user = $db ->where( [ 'mailbox' => $account ] )->find();

if(!$user){
    /* 开启快捷注册 */
    $kjreg  = Plusconfig("快捷注册") ?? 0;
    if( $kjreg < 1 ){
        $ELiMem ->ja($HASH,1, $THIS ->securitytime );
        return echoapptoken([],-1, $THIS ->Lang("mailbox_no")  );
    }

    $uid = $THIS -> REG( 
    [
        "name"=> $account,
        "sex"=>-1,
        "tou"=> "",
        "ip"=>ip(),
        'tuid'=>ELihhGet('tuid')??0
        
    ],$features);

    if(!$uid){
        return echoapptoken([],-1, $THIS ->Lang("uuid_error") );
    }

    $user =[
        'mailbox'=> $account,
        'password'=> '',
        'uid' => $uid
    ];
    
    $fan = $db ->insert($user);
    if(!$fan){
        return echoapptoken([],-1, $THIS ->Lang('reg_false') );
    }
}

$THIS ->DelCode($account);
$ELiMem ->d($HASH);
$USER = uid( $user['uid'] );
if($USER['accountoff'] != 1){
    return echoapptoken([],-1, $THIS ->Lang("accountoff") );
}

ELihhSet(['uid'=>$user['uid']]);
ELilog( 'userlog' , $user['uid'] , 0 , [] , 'account_login' , $THIS-> plugin );
$USER['avatar'] = pichttp($USER['avatar']==""?"Tpl/login/avatar/".($USER['id']%10).".png":$USER['avatar']);
return echoapptoken($USER,1, $THIS ->Lang("login_true"),$SESSIONID );