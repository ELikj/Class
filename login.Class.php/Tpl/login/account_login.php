<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 */
/*
account  登陆账号 2-32
password 登陆密码 6-64
code     验证码
*/
$account  =  ELiSecurity($_POST['account'] ?? "");
$password =  $_POST['password'] ?? "";
$code     =  $_POST['code'] ?? "";
$chauid = ELihhGet("uid");

if($chauid){
    return echoapptoken([],1, $THIS ->Lang("login_true") );
}

if(strlen($account ) < 2 || strlen($account ) > 32){
    return echoapptoken([],-1,  $THIS ->Lang('account_error')  );
} 

if(strlen($password ) < 6 || strlen($password ) > 64){
    return echoapptoken([],-1, $THIS ->Lang('password_error') );
}

$codeoff  = Plusconfig("account验证码")??0;
if($codeoff > 0){
    if( $code =='' || $code != ELihhGet('code')){
        return echoapptoken([],-1, $THIS ->Lang('code_error') );
    }
}

$HASH = "security/".ip();
if( (int)$ELiMem ->g($HASH) > $THIS ->securitynum){
    return echoapptoken([],-1, $THIS ->Lang("account_no")  );
}

$db = db("login_account");
$user = $db ->where( [ 'account' => $account ] )->find();

if(!$user){
    $ELiMem ->ja($HASH,1, $THIS ->securitytime);
    return echoapptoken([],-1, $THIS ->Lang("account_no")  );
}

if( ELimm($password) != $user['password']){
    $ELiMem ->ja($HASH,1, $THIS ->securitytime );
    return echoapptoken([],-1, $THIS ->Lang("password_no") );
}

$ELiMem ->d($HASH);
$USER = uid( $user['uid'] );

if($USER['accountoff'] != 1){
    return echoapptoken([],-1, $THIS ->Lang("accountoff") );
}

ELihhSet(['uid'=>$user['uid']]);
ELilog('userlog',$user['uid'],0,[],'account_login',$THIS-> plugin );
return echoapptoken([],1, $THIS ->Lang("login_true") );