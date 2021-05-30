<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); } ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>邮箱注册模版测试</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/Tpl/layui/css/layui.css"  media="all">
</head>
<body>           
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>注册<img src="/login/code/"></legend>
</fieldset>
<form class="layui-form" method="post" >
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">登陆邮箱</label>
            <div class="layui-input-inline">
                <input type="tel" name="mailbox" utocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">登陆密码</label>
            <div class="layui-input-inline">
                <input type="text" name="password" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">邮箱码</label>
            <div class="layui-input-inline">
                <input type="text" name="fcode"  autocomplete="off" class="layui-input">
            </div>
        </div>

       

        <div class="layui-inline">
        <label class="layui-form-label">验证码</label>
        <div class="layui-input-inline">
            <input type="text" name="code"  autocomplete="off" class="layui-input">
        </div>
        </div>
        <input type="hidden" name="type" value="reg">

        <div class="layui-inline">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="mailbox_code">点击发送</button>
        </div>


        <div class="layui-inline">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="mailbox_reg">立即注册</button>
        </div>
    </div>
</form>


<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>登陆</legend>
</fieldset>
<form class="layui-form" method="post" >
    <div class="layui-form-item">
        <div class="layui-inline">
        <label class="layui-form-label">登陆账号</label>
        <div class="layui-input-inline">
            <input type="tel" name="mailbox" utocomplete="off" class="layui-input">
        </div>
        </div>
        <div class="layui-inline">
        <label class="layui-form-label">登陆密码</label>
        <div class="layui-input-inline">
            <input type="text" name="password" autocomplete="off" class="layui-input">
        </div>
        </div>
       

        <div class="layui-inline">
        <label class="layui-form-label">验证码</label>
        <div class="layui-input-inline">
            <input type="text" name="code"  autocomplete="off" class="layui-input">
        </div>
        </div>
        <div class="layui-inline">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="mailbox_login">立即登陆</button>
        </div>
    </div>
</form>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>找回密码</legend>
</fieldset>
<form class="layui-form" method="post" >
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">登陆邮箱</label>
            <div class="layui-input-inline">
                <input type="tel" name="mailbox" utocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">登陆密码</label>
            <div class="layui-input-inline">
                <input type="text" name="password" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">邮箱码</label>
            <div class="layui-input-inline">
                <input type="text" name="fcode"  autocomplete="off" class="layui-input">
            </div>
        </div>

       

        <div class="layui-inline">
        <label class="layui-form-label">验证码</label>
        <div class="layui-input-inline">
            <input type="text" name="code"  autocomplete="off" class="layui-input">
        </div>
        </div>
        <input type="hidden" name="type" value="findback">

        <div class="layui-inline">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="mailbox_code">点击发送</button>
        </div>


        <div class="layui-inline">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="mailbox_findback">立即找回</button>
        </div>
    </div>
</form>





<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>快捷登陆</legend>
</fieldset>
<form class="layui-form" method="post" >
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">登陆邮箱</label>
            <div class="layui-input-inline">
                <input type="tel" name="mailbox" utocomplete="off" class="layui-input">
            </div>
        </div>
       

        <div class="layui-inline">
            <label class="layui-form-label">邮箱码</label>
            <div class="layui-input-inline">
                <input type="text" name="fcode"  autocomplete="off" class="layui-input">
            </div>
        </div>

       

        <div class="layui-inline">
        <label class="layui-form-label">验证码</label>
        <div class="layui-input-inline">
            <input type="text" name="code"  autocomplete="off" class="layui-input">
        </div>
        </div>
        <input type="hidden" name="type" value="login">

        <div class="layui-inline">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="mailbox_code">点击发送</button>
        </div>


        <div class="layui-inline">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="mailbox_kjlogin">快捷登陆</button>
        </div>
    </div>
</form>

</body>
</html>
<script src="/Tpl/jquery.js"></script>
<script src="/Tpl/layui/layui.all.js"></script>

<script>
window.UIMUI =[];
var form = layui.form;


form.on('submit(mailbox_code)', function(data){

$.post( "/login/mailbox_code/",data.field,function(data){

    if(data.msg){
        layer.msg(data.msg);
    }
});

return false;
});

form.on('submit(mailbox_reg)', function(data){

    $.post( "/login/mailbox_reg/",data.field,function(data){

        if(data.msg){
            layer.msg(data.msg);
        }
    });
   
    return false;
});

form.on('submit(mailbox_login)', function(data){
    $.post( "/login/mailbox_login/",data.field,function(data){
        if(data.msg){
            layer.msg(data.msg);
        }
    });
    return false;
});

form.on('submit(mailbox_findback)', function(data){
    $.post( "/login/mailbox_findback/",data.field,function(data){
        if(data.msg){
            layer.msg(data.msg);
        }
    });
    return false;
});

form.on('submit(mailbox_kjlogin)', function(data){
    $.post( "/login/mailbox_kjlogin/",data.field,function(data){
        if(data.msg){
            layer.msg(data.msg);
        }
    });
    return false;
});




</script>