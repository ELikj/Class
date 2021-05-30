<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 */
/*
wx.getUserInfo({
      success: function(res) {
        // 登录
       
    wx.login({
      success: revvs => {
        console.log(revvs);
        

        wx.request({
          url: 'http://test.elikeji.com/@/login/weixin_openidx_back/', //仅为示例，并非真实的接口地址
          method:"POST",
          data: {
            rawData: res.rawData,
            signature: res.signature,
            encryptedData: res.encryptedData,
            iv: res.iv,
            code:revvs.code
          },
          header: {
            'content-type': 'application/x-www-form-urlencoded' // 默认值
          },
          success (res) {
            console.log(res.data)
          }
        });

        // 发送 res.code 到后台换取 openId, sessionKey, unionId
      }
    })

*/

$appid = $features['configure']['小程序']['0']??"";
$secret = $features['configure']['小程序']['1']??"";
$signature = $_POST['signature']??"";
$encryptedData = $_POST['encryptedData']??"";
$iv = $_POST['iv']??"";
$code = $_POST['code']??"";
$state  = $_POST['state']??"";
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

$url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
$fanxx = ELiget($url);
$fanxx = json_decode($fanxx,true);
if(isset($fanxx['errcode'])){
    return echoapptoken($fanxx['errcode'],-1,$fanxx['errmsg']);
}

$session_key = $fanxx['session_key'];
$aesKey = base64_decode($session_key );
$aesIV= base64_decode($iv);
$aesCipher=base64_decode($encryptedData);
$result= openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
$dataObj = json_decode( $result,true );

if(!$dataObj){
    return echoapptoken([],-1,"解析失败");
}
if( $dataObj['watermark']['appid'] != $appid){
    return echoapptoken([],-1,$dataObj['watermark']['appid']);
}

$openid = $fanxx['openid']??"";
$unionid = $fanxx['unionid']??$openid;

$db = db('login_weixin');
$user = $db ->where( ['unionid' => $unionid] ) ->find();
if( $user ){

    if($user['openidx'] == ""){

        $db ->where( ['unionid' => $unionid] ) ->update( [ 'openidx' => $openid ]);

    }
    // 去登陆
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
    $fan = [
        'nickname' => $dataObj['nickName'],
        'sex' => $dataObj['gender'],
        'headimgurl'=> $dataObj['avatarUrl']
    ];

    $SEX = [
        "-1"=>"1",
        "1"=>"1",
        "2"=>"0"
    ];

    $uid = $THIS -> REG([
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
    'openidx'=> $openid,
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