<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>正在为您跳转...</title>
<style>
*{padding:0px;margin:0px;background: rgb(217, 217, 217);color: #fff;text-align:center;}
iframe{display:none;}
</style>
<script type="text/javascript">
    (function(win,doc){
        var s = doc.createElement("script"), h = doc.getElementsByTagName("head")[0];
        if (!win.alimamatk_show) {
            s.charset = "gbk";
            s.async = true;
            s.src = "http://a.alimama.cn/tkapi.js";
            h.insertBefore(s, h.firstChild);
        };
        var o = {
            pid: "mm_32456102_11278637_41616107",/*推广单元ID，用于区分不同的推广渠道*/
            appkey: "",/*通过TOP平台申请的appkey，设置后引导成交会关联appkey*/
            unid: "",/*自定义统计字段*/
            type: "click" /* click 组件的入口标志 （使用click组件必设）*/
        };
        win.alimamatk_onload = win.alimamatk_onload || [];
        win.alimamatk_onload.push(o);
    })(window,document);
</script>
</head>
<body>

<a  data-type="0" biz-itemid="<?php if(!isset($_GET['id']))$_GET['id']='520989671589'; echo $_GET['id']?>" data-tmpl="230x45" data-tmplid="225" data-rd="2" data-style="2" data-border="1"  id="nidaye" target="_blank" href="https://item.taobao.com/item.htm?id=<?php echo $_GET['id']?>">https://item.taobao.com/item.htm?id=<?php echo $_GET['id']?></a>
<input type="hidden" value="0" id="wodd" />
<h2>正在为您跳转...</h2>
</body>
</html>
<script>

function hello(){
	   if(document.getElementById("wodd").value > 50){
	   
	   window.location.href=(document.getElementsByTagName('a')['0'].href);
	   
	   
	   return false;
	   }
    
	     var woq = document.getElementById('writeable_iframe_0');
         if(woq){
			 
			 
		    var woq2 =  woq.contentWindow.document.getElementsByTagName('a')['0'];
		    if(woq2) window.location.href=(woq2.href);
		    else window.setTimeout(hello,100); 
         }else window.setTimeout(hello,100);
		     document.getElementById("wodd").value =(document.getElementById("wodd").value)*1 +1;

} 
window.setTimeout(hello,100); 

</script>