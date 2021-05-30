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
findback 安全密码 6-64
code     验证码
*/
$account  =  ELiSecurity($_POST['account'] ?? "");
$password =  $_POST['password'] ?? "";
$findback =  $_POST['findback'] ?? "";
$code     =  $_POST['code'] ?? "";

if(strlen($account ) < 2 || strlen($account ) > 32){
    return echoapptoken([],-1, $THIS ->Lang('account_error')  );
} 
if(strlen($password ) < 6 || strlen($password ) > 64){
    return echoapptoken([],-1,$THIS ->Lang('password_error') );
}
if(strlen($findback ) < 6 || strlen($findback ) > 64){
    return echoapptoken([],-1, $THIS ->Lang('findback_error') );
}

$HASH = "security/".ip();
if((int)$ELiMem ->g($HASH) > $THIS ->securitynum ){
    return echoapptoken([],-1, $THIS ->Lang("account_no") );
}

$codeoff  = Plusconfig("account验证码")??0;
if($codeoff > 0){
    if( $code =='' || $code != ELihhGet('code')){
        return echoapptoken([],-1, $THIS ->Lang('code_error') );
    }
}

$db = db("login_account");
$user = $db ->where( [ 'account' => $account ] )->find();
if(!$user){
    $ELiMem ->ja($HASH,1,$THIS ->securitytime);
    return echoapptoken([],-1,  $THIS ->Lang("account_no") );
}

if($user['findback'] == ""){
    return echoapptoken([],-1, $THIS ->Lang("findback_kong") );
}

if($user['findback'] !=  ELimm($findback) ){
    $ELiMem ->ja($HASH,1,$THIS ->securitytime);
    return echoapptoken([],-1,  $THIS ->Lang("findback_error") );
}

$ELiMem ->d($HASH);

if( ELimm($password) == $user['password']){
    return echoapptoken([],-1, $THIS ->Lang("password_ok") );
}

$fan = $db ->where( [ 'account' => $account ] )->update( [ 'password' => ELimm($password)] );
if($fan){
    ELilog('userlog',$user['uid'],3,[ 'yuan'=>$user['password'],'data'=>  ELimm($password) ],'account_pass',$THIS-> plugin );
    return echoapptoken([], 1, $THIS ->Lang("password_upok") );
}else{
    return echoapptoken([],-1, $THIS ->Lang("password_upno") );
}