<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* ******************************************
 * 系统名称：以厘建站系统
 * 版权所有：成都市以厘科技有限公司 www.eLikj.com
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 * 特别申明：本软件不得用于从事违反所在国籍相关法律所禁止的活动， 
 * 对于用户擅自使用本软件从事违法活动不承担任何责任
 * ******************************************
*/

$appid = $features['configure']['支付宝网页']['0'];
$secret = $features['configure']['支付宝网页']['1'];
$keycc = $features['configure']['支付宝网页']['2'];

$state = $_GET['state']??"";
$CODE = $_GET['auth_code']??"";

$BACKCAN = ['SESSIONID' => $SESSIONID];
if($state != ""){
    $BACKCAN = $ELiMem ->g('opentoken/'.$state);
    if($BACKCAN){
        $BACKCAN['state'] = $state;
        if(isset( $BACKCAN['SESSIONID'])){
            $SESSIONID = $BACKCAN['SESSIONID'];
        }
    }else{
        $BACKCAN = [];
    }
}

$CAN = [
    'app_id'=>$appid,
    'method'=> 'alipay.system.oauth.token',
    'charset'=>'utf-8',
    'sign_type'=>'RSA2',
    'timestamp'=>date("Y-m-d H:i:s"),
    'version'=>'1.0',
    'grant_type'=>'authorization_code',
    'code'=>$CODE
];

$CAN['sign']=  SHA256_sign( getarray(azpaixu($CAN)),$keycc );

$fan = ELipost("https://openapi.alipay.com/gateway.do",$CAN);
$fan = iconv("gb2312","utf-8//IGNORE",$fan);
$fan = json_decode($fan,true);

if(isset( $fan['error_response'])){
    return echoapptoken($fan['error_response']['code'],-1,$fan['error_response']['sub_msg']);
}

$DATA = $fan['alipay_system_oauth_token_response'];
$user_id = $DATA['user_id']??"";
$access_token = $DATA['access_token']??"";

$db = db('login_alipay');
$user = $db ->where( ['userid' => $user_id] ) ->find();
if( $user ){

    $USER = uid( $user['uid'] );
    if($USER['accountoff'] != 1){
        return echoapptoken([],-1,"账号关闭");
    }
    ELihhSet(['uid'=>$user['uid']]);
    ELilog('userlog',$user['uid'],0,[],'phone_login',"login");
    $BACKCAN['USER'] = $USER;
    return  $THIS -> Start($BACKCAN); 
}

$chauid = ELihhGet("uid");
if($chauid){
    $chaxun = $db ->where( [ 'uid' => $chauid ] )->find();
    if($chaxun){
        return echoapptoken([],-1,"已经绑定无法重复绑定"); 
    }else{
        $uid = $chauid;
    }

}else{

    $CAN = [
        'app_id'=>$appid,
        'method'=> 'alipay.user.info.share',
        'charset'=>'utf-8',
        'sign_type'=>'RSA2',
        'timestamp'=>date("Y-m-d H:i:s"),
        'version'=>'1.0',
        'grant_type'=>'authorization_code',
        'code'=>$CODE,
        'auth_token'=>$access_token,
        'biz_content'=>"{}"
    
    ];
    $CAN['sign']=  SHA256_sign( getarray(azpaixu($CAN)),$keycc );
    
    $fan = ELipost("https://openapi.alipay.com/gateway.do",$CAN);
    $fan = iconv("gb2312","utf-8//IGNORE",$fan);
    $fanxx = json_decode($fan,true);
   

    if(isset(  $fanxx['error_response'])){
        $fan_ = [
            'nickname' => $user_id,
            'sex' => -1,
            'headimgurl'=>''
        ]; 

    }else{
        $fanuser = $fanxx['alipay_user_info_share_response'];

        $fan_ = [
            'nickname' => $fanuser['nick_name']??$user_id,
            'sex' => isset($fanuser['gender']) && $fanuser['gender'] == 'F'?'0':1,
            'headimgurl'=>$fanuser['avatar']??""
        ]; 
       
    }


    $uid =  $THIS -> REG([
        "name"=>$fan_['nickname'],
        "sex"=> $SEX [$fan_['sex']]??-1,
        "tou"=> $fan_['headimgurl'],
        "ip"=>ip(),
        'tuid'=>ELihhGet('tuid')??0
    ],$features);
}


if(!$uid){
    return echoapptoken([],-1,"创建uid失败");
}

$reg =[
  
    'userid'=> $user_id,
    'uid' => $uid
];

$fan = $db->insert($reg);
if($fan){
    $USER = uid( $uid );
    ELihhSet(['uid'=>$uid]);
    $BACKCAN['USER'] = $USER;
    return  $THIS -> Start($BACKCAN);

}else{
    return echoapptoken([],-1,"登陆失败");
}