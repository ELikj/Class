<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* ******************************************
 * 系统名称：以厘建站系统
 * 版权所有：成都市以厘科技有限公司 www.eLikj.com
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 * 特别申明：本软件不得用于从事违反所在国籍相关法律所禁止的活动， 
 * 对于用户擅自使用本软件从事违法活动不承担任何责任
 * ******************************************
*/
$appid = $features['configure']['qq']['0']??"";
$secret = $features['configure']['qq']['1']??"";
$state = $_GET['state']??"";
$CODE = $_GET['code']??"";
$redirect_uri =  ELilink( ['login','qq_back']);
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

$canshu = [
    'grant_type'=>"authorization_code",
    "client_id"=>$appid,
    'client_secret'=>$secret,
    'code'=>$CODE,
    'redirect_uri'=>$redirect_uri,
    'fmt'=>'json'

];

$fan  = ELiget('https://graph.qq.com/oauth2.0/token?'.getarray($canshu));
$fan = json_decode($fan,true);
if( ! isset($fan['access_token'])){
    return echoapptoken($fan['error'],-1,$fan['error_description']);
}
$access_token = $fan['access_token']??"";
$fan  = ELiget("https://graph.qq.com/oauth2.0/me?access_token=".$fan['access_token']."&fmt=json&unionid=1");
$fan = json_decode($fan,true);

if( ! isset($fan['openid'])){
    return echoapptoken($fan['error'],-1,$fan['error_description']);
}

$openid = $fan['openid']??"";
$unionid = $fan['unionid']??"";
if($unionid == ""){
    return echoapptoken([],-1,"缺少unionid");
}

$db = db('login_qq');
$user = $db ->where( ['unionid' => $unionid] ) ->find();
if( $user ){
    // 去登陆
    if($user['openid'] == ""){
        $db ->where( ['unionid' => $unionid] ) ->update( [ 'openid' => $openid ]);
    }
    $USER = uid( $user['uid'] );
    if($USER['accountoff'] != 1){
        return echoapptoken([],-1,"账号关闭");
    }
    ELihhSet(['uid'=>$user['uid']]);
    ELilog('userlog',$user['uid'],0,[],'qq_back',"login");
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

    $getdata = [
        "oauth_consumer_key"=>$appid,
        'access_token' => $access_token,
        'openid'=>$openid
    ];
    $fan_  = ELiget("https://graph.qq.com/user/get_user_info?".getarray($getdata ));
    $fan_ = json_decode($fan_,true);


    $user = [
        'nickname'=> $fan_['nickname']??$openid,
        'sex'=>$fan_['gender_type']??-1,
        'headimgurl'=>$fan_['figureurl_qq']??"",
    ];
   
    $SEX = [
        "-1"=>"1",
        "1"=>"1",
        "2"=>"0"
    ];
    $uid = $THIS -> REG([
                "name"=>$user['nickname'],
                "sex"=> $SEX [$user['sex']]??-1,
                "tou"=> $user['headimgurl'],
                "ip"=>ip(),
                'tuid'=>ELihhGet('tuid')??0
            ],$features);
}

if(!$uid){
    return echoapptoken([],-1,"创建uid失败");
}

$reg =[
    'openid'=> $openid,
    'unionid'=> $unionid,
    'uid' => $uid
];

$fan = $db->insert($reg);
if($fan){
    $USER = uid( $uid );
    ELihhSet(['uid'=>$uid]);
    $BACKCAN['USER'] = $USER;
    return  $THIS -> Start($BACKCAN);
}else{
    return echoapptoken([],-1,"注册失败");
}