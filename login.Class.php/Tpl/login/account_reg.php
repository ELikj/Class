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
    return echoapptoken([],-1,  $THIS ->Lang('account_error') );
}

if($findback != ""){
    if(strlen($findback ) < 6 || strlen($findback ) > 64){
        return echoapptoken([],-1,  $THIS ->Lang('findback_error') );
    }
    if($password == $findback){
        return echoapptoken([],-1,  $THIS ->Lang('findback_password') );
    }
}

if(strlen( $password ) < 6 || strlen($password ) > 64){
    return echoapptoken([],-1,  $THIS ->Lang('password_error') );
}

$codeoff  = Plusconfig("account验证码")??0;
if($codeoff > 0){
    if( $code == '' || $code != ELihhGet('code')){
        return echoapptoken([],-1, $THIS ->Lang('code_error') );
    }
}

$db = db("login_account");
$user = $db ->where( [ 'account' => $account ] )->find();
if($user){
    return echoapptoken([],-1,  $THIS ->Lang('account_true') );
}

$chauid = ELihhGet("uid");
if($chauid){
    $chaxun = $db ->where( [ 'uid' => $chauid ] )->find();
    if($chaxun){
        return echoapptoken([],-1, $THIS ->Lang('account_false') ); 
    }else{
        $uid = $chauid;
    }

}else{

    $uid = $THIS -> REG( 
        [
            "name"=> $account,
            "sex"=> -1,
            "tou"=> "",
            "ip"=>ip(),
            'tuid'=>ELihhGet('tuid')??0
        ],$features);
}

if(!$uid){
    return echoapptoken([],-1,$THIS ->Lang('uuid_error') );
}

$reg = [
    'account'=> $account,
    'findback'=> $findback != "" ? ELimm($findback):"",
    'password'=>  ELimm($password),
    'uid' => $uid
];

$fan = $db ->insert($reg);
if($fan){
    ELihhSet(['uid'=>$uid]);
    return echoapptoken([],1, $THIS ->Lang('reg_true') );
}else{
    return echoapptoken([],-1, $THIS ->Lang('reg_false') );
}