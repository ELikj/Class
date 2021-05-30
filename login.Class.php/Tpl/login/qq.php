<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 */
$appid = $features['configure']['qq']['0']??"";
$redirect_uri = ELilink( ['login','qq_back']);
$display = ELishouji($_SERVER["HTTP_USER_AGENT"]);

$state = $CANSHU['0'] ?? "";
if( $state == "" ){
    $state = md5($SESSIONID);
    if(isset($_GET['tiaozhuan'])){
        $tiaozhuan = urldecode($_GET['tiaozhuan']);
    }else{
        $tiaozhuan =  ELihhGet("tiaozhuan");
    }
    $ELiMem ->s('opentoken/'.$state,['platform'=> strpos( $_SERVER["HTTP_USER_AGENT"] , "Messenger") !== false?'mobile':'pc' ,'SESSIONID'=>$SESSIONID,'tiaozhuan'=> $tiaozhuan?$tiaozhuan:WZHOST.$ELiConfig['Plus'].'/?apptoken='.$SESSIONID ],300);
}
$url = 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.$appid.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state='.$state.'&display='.($display?"mobile":"");
tiaozhuan($url);
return ;