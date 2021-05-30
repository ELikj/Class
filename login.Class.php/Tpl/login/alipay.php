<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* ******************************************
 * 系统名称：以厘建站系统
 * 版权所有：成都市以厘科技有限公司 www.eLikj.com
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 * 特别申明：本软件不得用于从事违反所在国籍相关法律所禁止的活动， 
 * 对于用户擅自使用本软件从事违法活动不承担任何责任
 * ******************************************
*/

$appid = $features['configure']['支付宝网页']['0']??"";
$secret = $features['configure']['支付宝网页']['1']??"";
$state = $CANSHU['0'] ?? "";

$redirect_uri = ELilink( ['login','alipay_back']);

if( $state == "" ){
    $state = md5($SESSIONID);
    if(isset($_GET['tiaozhuan'])){
        $tiaozhuan = urldecode($_GET['tiaozhuan']);
    }else{
        $tiaozhuan =  ELihhGet("tiaozhuan");
    }
    $ELiMem ->s('opentoken/'.$state,['platform'=> strpos( $_SERVER["HTTP_USER_AGENT"] , "Messenger") !== false?'mobile':'pc' ,'SESSIONID'=>$SESSIONID,'tiaozhuan'=> $tiaozhuan?$tiaozhuan:WZHOST.$ELiConfig['Plus'].'/?apptoken='.$SESSIONID ],300);
}

$url = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id='.$appid.'&scope=auth_user,auth_base&state='.$state.'&redirect_uri='.urlencode($redirect_uri);
tiaozhuan($url);
return ;