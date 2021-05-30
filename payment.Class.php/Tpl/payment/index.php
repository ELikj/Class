<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$GLOBALS['head'] = "html";
if(isset($_POST['ccc'])){

    $DATA = [
        'orderid'=>orderid(),
        'type'=>$_POST['type']??0,
        'uid'=> 0,
        'subject'=>'支付测试',
        'notify'=>"https://www.eliphp.com/payment/paytest/",
        'return'=>"https://www.eliphp.com/payment/paytest/",
        'productid'=>"0",
        'moeny'=> number_format( ($_POST['jine']??1),2,".",""),
        'time'=>time(),
        'remarks'=>'',
        'back'=>'html',
        'mchid'=> $features['configure']['订单录入']['0']
    ];
    $PAYHTTP = "http://192.168.1.13/payment/pay/";
    $DATA['sign'] = md5( $DATA['uid'].$DATA['mchid'].$DATA['type'].$DATA['orderid'].$DATA['moeny'] .$DATA['time'] .$DATA['remarks'].$DATA['subject'].$DATA['productid'].$features['configure']['订单录入']['1'] );

    $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$PAYHTTP."' method='post'>";
    foreach( $DATA  as $key =>$val ) {
        $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
    }
    $sHtml = $sHtml."<input type='submit' value='loading..'></form>";
    $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
    echo  $sHtml;

    

    return ;
}
?><!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta name="keywords" content="免签支付,个人收款,个人即时到账接口,个人支付接口,个人二维码支付,个人免签,支付宝监控,微信支付监控,无需认证支付,支付回调,即时到账api,支付api,pay api callback"/>
	<meta name="description" content="独立开发者免签即时到账收款接口，只需个人支付宝或微信账号即可收款，无需费率，快捷接入，安全稳定不漏单。" />	
    <title> 个人收款通知免签支付系统独立版本 </title>
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
        <h1>个人收款免签支付系统独立版本</h1>
        <p style="color: #CD5555;">请勿用于违反国家法律法规的业务!</p>
        <div class="zhongse"> 
            <p class="ZHIFUPAY">充值测试</p>
            <form method="post" class="layui-form" style="margin-top:20px;">
            <input type="hidden" name="ccc" value="ccc" />
            <div class="layui-form-item">
                    <label class="layui-form-label">支付方式</label>
                    <div class="layui-input-block">
                    <select name="type" lay-verify="required">
                       
                        <option value="0">支付宝</option>
                        <option value="1" selected>微信</option>
                    
                    </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">支付金额</label>
                    <div class="layui-input-block">
                    <select name="jine" lay-verify="required">
                        <?php $jinx = explode(",",'1,5,10,20,50,100,500');
                            if($jinx){
                                foreach($jinx as $vv){
                                    echo '<option value="'.$vv.'"> '.$vv.' 元</option>';
                                }

                            }
                        
                        ?>
                    
                      
                        
                    
                    </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="submit" name="submit" class="layui-btn" value="立即测试" />
                    </div>
                </div>
            </form>
           
        </div>  
        <div class="footer">
            <p> <a href="<?php echo $features['configure']['联系客服']['0']??"";?>" target="_blank">如需咨询请 点击这里</a> ！</p>
        </div>
    </div>
</body>
</html>
<script src="<?php echo CDNHOST;?>Tpl/jquery.js" charset="utf-8"></script>
<script src="<?php echo CDNHOST;?>Tpl/layui/layui.all.js" charset="utf-8"></script>
<script>
</script>