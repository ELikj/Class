<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$GLOBALS['head'] = "html";
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
    <meta name="keywords" content="免签支付,个人收款,个人即时到账接口,个人支付接口,个人二维码支付,个人免签,支付宝监控,微信支付监控,无需认证支付,支付回调,即时到账api,支付api,pay api callback"/>
	<meta name="description" content="独立开发者免签即时到账收款接口，只需个人支付宝或微信账号即可收款，无需费率，快捷接入，安全稳定不漏单。" />	
    <title> 个人收款通知免签支付系统独立版本 </title>
    <link rel="stylesheet" href="<?php echo CDNHOST;?>Tpl/layui/css/layui.css">
    <style>
    body{ background: #000;font-size: 16px;color: #444; font-family: "Microsoft YaHei";}
    .bodyx { margin: 0px auto; max-width: 330px;min-height:100px; text-align:center;padding:15px 0;}
    
    .zhongse{padding:10px;background:#eee; border-radius: 10px;margin-top:20px;}
    .ZHIFUPAY{font-size:30px;font-weight:bold;}
  
    .footer{margin-top:10px;}
    .footer a{color:red;font-size:18px;}
    .layui-tab-title li{min-width:80px;padding:0 8px;}
    .layui-tab-content{padding:0px;}
    .neirong{padding:10px 0;font-size:12px;}
    .neirong li{margin-bottom:3px;}
    .neirong li b{float:right;}
    .neirong li b.code1{color:green;}
    .neirong li b.code-1{color:red;}
    .neirong li span{margin:0 8px;}
    </style>
    <script>
        window.UIMUI =[];
        if( typeof(ELi) == "undefined"){
            window.ELi = {
                AD:function(qqq,xxxx){
                },
                ADHIDE:function(guanbi){
                },
                ADPM:function(qqq,xxxx){
                },SETTONG:function(qqq){
                },MSG:function(qqq){

                    layer.msg(qqq);

                }
            }
        }
        window.ELIBACK = {
            createInterstitialAd:{
                onLoad:null,
                onError:null,
                onClose:null,
                catch:null,
                then:null
            },
            createRewardedVideoAd:{
                onLoad:null,
                onError:null,
                onClose:null,
                catch:null,
                then:null
            },
            createGridAd:{
                onLoad:null,
                onError:null,
                onClose:null,
                catch:null,
                then:null,
                destroy:null
                
            },playjine:function(data){
                $("[name='bug']").val(data);
                var lenx = $(".neirong li").length;
                if(lenx >= 16){
                    $(".neirong li:last").remove();
                }
                if(lenx == 1){
                    if($(".neirong").html().indexOf("没有通知记录")> -1 ){
                        $(".neirong li:last").remove();
                    }
                }
                var shuju = null;
                try {
                    shuju = JSON.parse(data);
                } catch (error) {
    
                }
                if(shuju){
                    shuju.jine = ((shuju.jine*1)/100).toFixed(2);
                    $(".neirong").prepend('<li title="'+shuju.msg+'">'+shuju.time+' <span>'+shuju.name+'</span> <b class="code'+shuju.code+'">'+shuju.jine+'</b>  </li>');
                }
            }
        };
        function ELibanner(){
            var config ={
                style:{top:0,height:150}
            };
            if(isNaN(config.style.top)  ){
                config.style.top = 0;
            }   
            if(!config.style.height){
                config.style.height = 150;
            }
            ELi.AD("banner",JSON.stringify( config.style));
        }
    </script>
</head>

<body>
    <div class="bodyx">
        <p style="color: #CD5555;">请勿用于违反国家法律法规的业务!</p>
        <div class="zhongse">
            <p class="ZHIFUPAY">个人收款通知系统</p>
            <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                <ul class="layui-tab-title">
                    <li class="layui-this">通知记录</li>
                    <li>通信设置</li>
                    <li>调试信息</li>
                    
                </ul>
                <div class="layui-tab-content" >
                    <div class="layui-tab-item  layui-show">
                        <ul class="neirong"><li>没有通知记录</li></ul>

                    </div>

                    <div class="layui-tab-item">
                        <div class="layui-form-item" style="padding-top:10px;">
                            <textarea name="tongxin" contenteditable="true" placeholder="请把通信数据贴入然后提交" class="layui-textarea"></textarea>
                        </div>
                        <button class="layui-btn" onclick="luruxitong();" lay-filter="formDemo">录入通信数据</button>
                    </div>

                    <div class="layui-tab-item">
                        <div class="layui-form-item" style="padding-top:10px;">
                            <textarea name="bug" contenteditable="true" placeholder="调试信息" class="layui-textarea" style="heidht:auto;"></textarea>
                        </div>
                    </div>

                </div>

            </div>     
        </div>
    </div>
</body>
</html>
<script src="<?php echo CDNHOST;?>/Tpl/jquery.js" charset="utf-8"></script>
<script src="<?php echo CDNHOST;?>Tpl/layui/layui.all.js" charset="utf-8"></script>
<script>
    function luruxitong(){
        var tong =  $("[name='tongxin']").val();
        if(tong == ""){
            ELi.MSG("请输入通信密码");
            return false;
        }
        ELi.SETTONG(tong);
        var jiexile =  tong.split("ELiKJ");
        if(jiexile.length < 5){
            ELi.MSG("通信密码错误");
            return false;
        }
        window.localStorage.setItem("通信密码",tong)
        ELi.MSG("通信密码设置成功");
    }
    $(function(){
        var shopcatCookit=window.localStorage.getItem("通信密码");
        if(shopcatCookit){
            $("[name='tongxin']").val(shopcatCookit);
            luruxitong();
        }
        ELibanner();
    });
</script>