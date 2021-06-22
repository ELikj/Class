<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 */
$appid = $features['configure']['公众号']['0']??"";
$redirect_uri = ELilink( ['login','weixin_openid_back']);
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
$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect';

if (strpos( $_SERVER["HTTP_USER_AGENT"] , "Messenger") !== false) {
    tiaozhuan($url);
}else{
    $GLOBALS['head'] = "html";
    ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>微信登陆</title>
  <meta name="renderer" content="webkit" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <style>
  *{margin:0;padding:0;}
  body{background:#000;}
  #zzz{padding:80px;margin:0 auto;width:350px;text-align:center;color:#fff;font-size:16px;}
  p img{width:100%;}
  p.wztishi{margin-bottom:20px;background:#333;padding:10px;}
  </style>
</head>
<body> 
    <div id="zzz">
        <p class="wztishi">
            请使用微信 “扫一扫” 登陆
        </p>
        <p id="qrcode"></p>
    </div>
</body>
</html>
<script src="<?php echo $ELiConfig['cdnhost'];?>Tpl/jquery.js"></script>
<script src="<?php echo $ELiConfig['cdnhost'];?>Tpl/qrcode.min.js"></script>
<script type="text/javascript">
window.UIMUI =[];
var NUM = 0;
$(function(){
    function getuser(){
        NUM ++;
        console.log(NUM);
        if(NUM > 50){

        }else{
            $.getJSON("<?php echo WZHOST.$ELiConfig['Plus'].'/login/getuser/?state='.$state;?>",function(data){
              
                if(data && data.code == 1){
                    
                    if(data.data.tiaozhuan){
                        window.location.href = data.data.tiaozhuan;
                    }else{
                        window.location.href = '<?php echo WZHOST?>';
                    }

                }else{
                    setTimeout(getuser, 3000);
                }
            });
            
        }  
    }

   var qrcode = new QRCode(document.getElementById("qrcode"), {
	text: "<?php echo ($url);?>",
            width: 350,
            height: 350,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
       
    setTimeout(getuser, 3000);

});
</script>
<?php
}