<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$GLOBALS['head'] = "html";
$MCHID = $features['configure']['订单录入']['0']??"";
$MCKEY = $features['configure']['订单录入']['1']??"";
$TYPE = $features['configure']['支付方式']??[];
$UUID = $CANSHU['0']??"";//验证UUID
$ORDERID = (int)($CANSHU['1']??0);//订单id
$TOKEN = $CANSHU['2']??"";//随机token
$SIGN = $CANSHU['3']??"";//签名
$TIME =(int)( $CANSHU['4']??0);//到期时间
$GTIME = (int)($features['configure']['过期时间']['0']??300);
if($ORDERID < 1){
    return $THIS->HTMLOUT("订单错误!",$features);
}
if($SIGN != md5($TIME.$MCHID.$ORDERID.$TOKEN.$MCKEY).md5($TIME.$UUID.$MCKEY)){
    return $THIS->HTMLOUT("订单错误!!",$features);
}
$db = db("payment_order");
$DATA = $db ->where(['id' =>$ORDERID])->find();
if(!$DATA){
    return $THIS->HTMLOUT("订单错误!!!",$features);
}
if($TIME < time() && $DATA['off'] != 2){
    return $THIS->HTMLOUT("订单过期!",$features);
}
if($DATA['off'] == 3){
    return $THIS->HTMLOUT("订单过期!",$features);
}
$code = [];
if($DATA['off'] == 1){ 
    $code = $db ->setbiao('payment_code')->where(['id'=>$DATA['codeid']])->find();
    if(!$code){
        return $THIS->HTMLOUT("二维码错误!",$features);
    }
}

if($DATA['off'] == 2 && $DATA['productid'] == 0){

    if($DATA['return']!= ""  && strstr($DATA['return'], '://') !== false ){
        $TICAN = [
            'orderid' =>$DATA['orderid'],
            'off'=>$DATA['off'],
         // 'moeny' =>$DATA['moeny'],
            // 'paymoeny' =>$DATA['paymoeny'],
            'remarks'=>$DATA['remarks'],
            'id'=>$DATA['id']
        ];
        tiaozhuan($DATA['return'].'?&'.getarray($TICAN));
        return ;
    }else{
        return $THIS->HTMLOUT("已支付!",$features);
    }
}

$SHUOMING = $features['configure'][$TYPE[$DATA['type']]]['1']??"请扫码支付";
$GUOTIME = $TIME -time();
$QMING = md5($MCHID.$SIGN.$DATA['orderid'].$MCKEY);
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta name="renderer" content="webkit">
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="360"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <title><?php echo plusconfig('站点标题');?></title>
    <link rel="stylesheet" href="<?php echo CDNHOST;?>Tpl/layui/css/layui.css">
    <style>
    body{ background: #fafbfc;font-size: 16px;color: #444; font-family: "Microsoft YaHei";}
    .bodyx { margin: 10px auto 0px auto; max-width: 330px;min-height:100px; text-align:center;padding:15px 0;}
    .bodyx h1{font-size:20px;margin-bottom:10px;}
    .zhongse{padding:10px 30px;background:#fff; border-radius: 10px;margin-top:20px;}
    .ZHIFUPAY{font-size:30px;font-weight:bold;}
    img.qrcode{    width: 248px;
    
    border-radius: 5px;
    overflow: hidden;
    border: 1px solid #f2f2f2;margin-bottom:20px;}
    .footer{margin-top:10px;}
    .footer a{color:red;font-size:18px;}
    </style>
</head>
<body>
    <div class="bodyx">
        <h1><?php echo $DATA['subject']!=""?$DATA['subject']: plusconfig('站点标题'); ?></h1>
        <p style="color: #CD5555;">请输入以下金额支付，否则会支付失败</p>
        <div class="zhongse">
            <?php 
            if($DATA['off'] == 1){ 
                $codeimg = $code['codeimg'] != '' ? $code['codeimg']: ($features['configure'][$TYPE[$DATA['type']]]['0']??"");
            ?>  <p class="ZHIFUPAY">￥<?php echo $code['paymoeny'];?></p>
                <p style="padding:20px 0;"> <?php echo $code['remarks']!=''?$code['remarks']: $SHUOMING;?></p>
                <p><img class="qrcode" src="<?php echo pichttp($codeimg);?>" alt="<?php echo $code['remarks']!=''?$code['remarks']: $SHUOMING;?>"></p>
                <p>剩 <span  id="daojimiao"><?php echo $GUOTIME;?> 秒</span></p>
            
            <?php }else if($DATA['off'] == 0){ ?>
                <p class="ZHIFUPAY">等待匹配请稍后</p>
                <p style="padding:20px 0;">￥<?php echo $DATA['moeny'];?></p>
                <p><img class="qrcode" src="<?php echo CDNHOST;?>Tpl/payment/pipei.png" alt="等待匹配请稍后"></p>
                <p> 剩 <span id="daojimiao"><?php echo $GUOTIME;?> 秒</span></p>
            <?php }else if($DATA['off'] == 2){ ?>
                <p class="ZHIFUPAY">已支付</p>
                <p style="padding:10px 0;">￥<?php echo $DATA['paymoeny'];?></p>
                <pre style="padding:30px 0px;"><?php echo $DATA['body']?></pre>
            <?php }?> 
           
        </div>
        <div class="footer">
            <p> <a href="<?php echo $features['configure']['联系客服']['0']??"";?>" target="_blank">如需咨询请 点击这里</a> ！</p>
        </div>
    </div>

</body>
</html>
<script src="<?php echo CDNHOST;?>/Tpl/jquery.js" charset="utf-8"></script>
<script>
var GUOTIME = <?php echo $GUOTIME;?>;
var OFF = <?php echo $DATA['off'];?>;
var JISHIIQ = null;
var ZURL = '<?php echo ELiLink([ $THIS -> plugin,'find',$DATA['orderid'],$QMING,$SIGN])?>';
function miaojishi(){
    if(GUOTIME%3 == 0 || GUOTIME <= 1){
        $.getJSON(ZURL,function(data){
            if(data.code == 1){
                if(data.data.off >= 2){
                    
                    clearInterval(JISHIIQ); 
                    JISHIIQ = null;
                    setTimeout(() => {
                        window.location.href = window.location.href;
                    }, 2000);
                    return ;
                }else if(data.data.off == "0" && data.data.tiaourl){
                    window.location.href = data.data.tiaourl;
                    clearInterval(JISHIIQ); 
                    JISHIIQ = null;
                }
            }
        });
    }
    GUOTIME--;
    if(GUOTIME < 1){
        clearInterval(JISHIIQ); 
        JISHIIQ = null;
        window.location.href=window.location.href;
        return ;
    }
    $("#daojimiao").html(GUOTIME +' 秒');
}

$(function(){
    if(OFF != 2){
        JISHIIQ = setInterval("miaojishi()", 1000);
    }
});
</script>