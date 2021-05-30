<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 */
$appid  = $features['configure']['公众号']['0']??"";
$secret = $features['configure']['公众号']['1']??'';
$state  = $_GET['state']??"";
$CODE   = $_GET['code']??"";
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
$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$CODE.'&grant_type=authorization_code';
$fan = ELiget($url);
$fan = json_decode($fan,true);
if(isset($fan['errcode'])){
    return echoapptoken($fan['errcode'],-1,$fan['errmsg']);
}
$openid = $fan['openid']??"";
$unionid = $fan['unionid']??"";
$access_token = $fan['access_token']??"";
if($unionid == ""){
    return echoapptoken([],-1,"缺少unionid");
}

$db = db('login_weixin');
$user = $db ->where( ['unionid' => $unionid] ) ->find();
if( $user ){
    if($user['openid'] == ""){
        $db ->where( ['unionid' => $unionid] ) ->update( [ 'openid' => $openid ]);
    }
    $USER = uid( $user['uid'] );
    if($USER['accountoff'] != 1){
        return echoapptoken([],-1,"账号关闭");
    }
    ELihhSet(['uid'=>$user['uid']]);
    ELilog('userlog',$user['uid'],0,[],'weixin_openid_back',"login");
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

    $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
    $fan = ELiget($url);
    $fan = json_decode($fan,true);
    if(!isset(  $fan['openid'])){
        $fan = [
            'nickname' => $openid,
            'sex' => -1,
            'headimgurl'=>''
        ]; 
    }
    $SEX = [
        "-1"=>"1",
        "1"=>"1",
        "2"=>"0"
    ];
    $uid =  $THIS -> REG( 
        [
            "name"=>$fan['nickname'],
            "sex"=> $SEX [$fan['sex']]??-1,
            "tou"=> $fan['headimgurl'],
            "ip"=>ip(),
            'tuid'=>ELihhGet('tuid')??0
        ],$features);
}

if(!$uid){
    return echoapptoken([],-1,"创建uid失败");
}

$reg =[
    'openid'=>  $openid,
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