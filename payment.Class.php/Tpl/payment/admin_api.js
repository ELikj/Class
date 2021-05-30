ELi['payment/admin_api'] = {
    url: dir + Plus + FENGE + "payment" + FENGE + "admin_api" + FENGE,
    OFF:["未匹配","已匹配"],
    TYPE:[],
    init: function () {
        ELi['payment/admin_api'].get();
    },edit(OBJ, diercai) {
    },del(OBJ) {
    },copy(){
        $("[name='elikjcom']").select();
        document.execCommand("Copy"); 
        alert("已复制好，可贴粘。");
       
    },add() {
    },get() {

        apptongxin(ELi['payment/admin_api'].url + TOKEN + FENGE + "get", {}, function (data) {
            if (data.token && data.token != "") {
                TOKEN = data.token;
            }
            var html = '';
            var leixing = "<select name='type'>";
            var llxin = "";
            if(data.code){
                for(var n in data.data){
                    leixing += '<option value="' + n + '">' + data.data[n] + '</option>';
                    llxin += n+' '+data.data[n] +' ';
                }

            }
            leixing+='</select>';

          
 
            html +='<div class="layui-row"><div class="layui-form-item"><input type="text" class="layui-input" name="elikjcom" value="'+data.msg+'" onclick="ELi[\'payment/admin_api\'].copy();"></div><fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;"><legend>充值测试</legend></fieldset>';
            html +='<div class="layui-col-xs4">';
            html +='<form name="form" class="layui-form layui-form-pane">';
            html += '<div class="layui-form-item"><label class="layui-form-label">支付方式</label><div class="layui-input-inline">'+leixing+' </div></div>';
            html += '<div class="layui-form-item"><label class="layui-form-label">产品id</label><div class="layui-input-inline"><input type="text" name="productid" placeholder="产品库id" value="0" autocomplete="off" class="layui-input"> </div></div>';
            html += '<div class="layui-form-item"><label class="layui-form-label">用户id</label><div class="layui-input-inline"><input type="text" name="uid" placeholder="uid" value="0" autocomplete="off" class="layui-input"> </div></div>';
            html += '<div class="layui-form-item"><label class="layui-form-label">异步地址</label><div class="layui-input-inline"><input type="text" name="notify" placeholder="异步地址" value="'+WZHOST+'payment/paytest/" autocomplete="off" class="layui-input"> </div></div>';

            html += '<div class="layui-form-item"><label class="layui-form-label">同步地址</label><div class="layui-input-inline"><input type="text" name="return" placeholder="同步地址" value="'+WZHOST+'payment/paytest/" autocomplete="off" class="layui-input"> </div></div>';

            html += '<div class="layui-form-item"><label class="layui-form-label">充值金额</label><div class="layui-input-inline"><input type="text" name="moeny" placeholder="支付金额" value="1" autocomplete="off" class="layui-input"> </div></div>';

            html += '<div class="layui-form-item"><div class="layui-input-block"><button  class="layui-btn"  lay-submit  lay-filter="pay' + $class + '">充值测试</button></div></div>';
            html += '</form>';
            html +='</div>';

            html +='<div class="layui-col-xs8">';
            html +='<p>通信地址:'+WZHOST+'payment/pay/ 请求方式:POST 所有数据必须传递可以为空</p>';
            html +='<p>系统订单:  orderid 系统订单全局唯一:201885555201621219102</p> ';
            html +='<p>充值类型:  type 充值类型: '+llxin+'</p> ';
            html +='<p>充值标题:  subject 充值标题可用于授权id:这是一个标题</p> ';
            html +='<p>产品 id:  productid 需要使用产品库才填写 默认:0 </p> ';
            html +='<p>用户id :  uid  用户id 默认:0 不强制验证已分配 </p> ';
            html +='<p>订单金额:  moeny 订单金额2位小数1.00 </p> ';
            html +='<p>充值时间:  time 时间戳:1621219102</p> ';
            html +='<p>异步通知:  notify 异步通知地址 post接收 产品id大于0 的时候可为空</p> ';
            html +='<p>同步通知:  return 同步通知地址 get 接收 可为空</p> ';
            html +='<p>请求返回:  back  请求返回:默认html 可选 html json </p> ';

            html +='<p>back = json 同步返回数据 </p> ';
            html +='<p> {"moeny":"订单金额","paymoeny":"匹配金额","payurl":"二维码图片","id":"商家订单","miao":"过期秒数","time":"过期时间戳"} </p> ';
            
            
            html +='<p>充值备注:  remarks 备注原路返回</p> ';
            html +='<p>通信账号:  mchid  系统设置的通信账号</p> ';
            html +='<p>通信密码:  不需要传递 系统设置的通信密钥</p> ';
            html +='<p>订单签名:  sign  签名算法: md5( uid+mchid +type+ orderid + moeny +time +remarks +subject +productid + 通信密码 )</p> ';
            html +='</div>';

            html +='</div>';

            html +='<div class="layui-row"><fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;"><legend>异步通知</legend></fieldset>';

            html +='<div class="layui-col-xs4">';
            html +='<form name="form" class="layui-form layui-form-pane">';
            
            html += '<div class="layui-form-item"><label class="layui-form-label">充值订单ID</label><div class="layui-input-inline"><input type="text" name="id" placeholder="订单id" autocomplete="off" class="layui-input"> </div></div>';

            html += '<div class="layui-form-item"><div class="layui-input-block"><button  class="layui-btn"  lay-submit  lay-filter="ding' + $class + '">异步通知测试</button></div></div>';
            html += '</form>';
            html +='</div>';



            html +='<div class="layui-col-xs8">';
            html +='<p>异步地址 POST 接收 ! </p>';
            
            html +='<p>通信账号:  mchid  系统设置的通信账号</p> ';
            
            html +='<p>系统订单:  orderid 系统订单全局唯一:201885555201621219102</p> ';
            html +='<p>商家订单:  id 商家唯一订单id:防止重复</p> ';
            html +='<p>订单金额:  moeny 订单的金额</p> ';
            html +='<p>支付金额:  paymoeny 支付的金额</p> ';
            html +='<p>订单备注:  remarks 备注原路返回</p> ';
            html +='<p>订单状态:  off 订单状态:0 等待分配二维码 1 等待支付 2 支付成功 3 支付超时</p> ';
            html +='<p>产品 id:  productid 需要使用产品库才填写 默认:0 </p> ';
            html +='<p>用户id :  uid  用户id 默认:0 不强制验证已分配 </p> ';
            html +='<p>充值时间:  time 时间戳:1621219102</p> ';
            html +='<p>随机码:  token 随机码</p> ';
            html +='<p>通信密码:  不需要传递 系统设置的通信密钥</p> ';
            html +='<p>签名结果:  sign 签名结果</p> ';
            html +='<p>签名算法: md5(mchid+orderid+id+moeny+paymoeny+remarks+off+uid+productid+time+token+通信密码 )</p> ';
            html +='<p>接收到请输出: success</p> ';
            
            html +='</div>';
            html +='</div>';


            html +='<div class="layui-row layui-col-space15">';

            html +='<div class="layui-col-xs4">';
            html +='<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;"><legend>同步返回</legend></fieldset>';

            html +='<p style="color:Red;">这里没有验签名,不能做订单支付成功处理</p> ';
            html +='<p>同步返回 : GET方式接收</p> ';
            html +='<p>系统订单:  orderid 系统订单全局唯一:201885555201621219102</p> ';
            html +='<p>商家订单:  id 商家唯一订单id:防止重复</p> ';
           // html +='<p>订单金额:  moeny 订单的金额</p> ';
           // html +='<p>支付金额:  paymoeny 支付的金额</p> ';
            html +='<p>订单备注:  remarks 备注原路返回</p> ';
            html +='<p>订单状态:  off 订单状态:0 等待分配 1 等待支付 2 支付成功 3 支付超时</p> ';

            

            html +='</div>';

            html +='<div class="layui-col-xs4">';
            html +='<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;"><legend>异步查询</legend></fieldset>';
            html +='<p style="color:Red;">异步通知的时候,在重远程服务端读取下订单状态判断</p> ';
            html +='<p>系统订单:  orderid 系统订单全局唯一:201885555201621219102</p> ';
            html +='<p>请求地址 : '+WZHOST+'payment/find/系统订单/签名算法结果/[64位随机值]/</p> ';
            html +='<p>通信账号:  mchid  系统设置的通信账号</p> ';
            html +='<p>通信密码:  不需要传递 系统设置的通信密钥</p> ';
            html +='<p>签名算法:  md5(通信账号+[64位随机值]+系统订单+通信密码 )</p> ';

            html +='<p>返回值: {"code":1,"data":{"id":"商家订单","type":"支付类型","off":"订单状态","moeny":"订单金额","paymoeny":"支付金额"},"msg":"ok","token":""} </p> ';
          
            html +='</div>';

            html +='<div class="layui-col-xs4">';
            html +='<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;"><legend>产品API处理</legend></fieldset>';
            html +='<p style="color:Red;">建议: 根据商家id作为唯一值,防止多次请求  </p> ';
            html +='<p>产品 API地址 POST 接收 ! </p>';
            html +='<p>商家订单:  id 商家唯一订单id:防止重复</p> ';
            html +='<p>订单金额:  moeny 订单的金额</p> ';
            html +='<p>支付金额:  paymoeny 支付的金额</p> ';
            html +='<p>处理时间:  time 时间戳:1621219102</p> ';
            html +='<p>SKU:  skuid 产品里面设置的SKU,区分产品</p> ';
            html +='<p>subject:  订单里标题 可用于id授权</p> ';
            html +='<p>remarks:  订单里备注 可用于id授权</p> ';
            html +='<p>签名密码:  产品通信密码  为空的时候全局通信密码 </p> ';
            html +='<p>签名结果:  sign 签名算法</p> ';
            html +='<p>签名算法: md5(subject+remarks+id+moeny+paymoeny+skuid+time+签名密码 )</p> ';

            
          
            html +='</div>';

            html +='</div>';
            //&orderid=ELi2021051787235439114132&moeny=1.00&paymoeny=1.00&off=2&remarks=&id=1128

            $("#LAY_app_body ." + $class).html('<div class="layui-fluid"><div class="layui-card"><div class="layui-card-body" style="padding: 15px;">'+html+'</div></div></div>');

            layui.form.on('submit(ding' + $class + ')', function (formdata) {
                formdata = formdata.field;
                formdata.Format = 'json';
                if(formdata.id < 1){
                    layer.msg("请输入订单id");
                    return false;
                }  
                apptongxin(ELi['payment/admin_api'].url + TOKEN + FENGE + "put", formdata, function (data) {
                    if (data.token && data.token != "") {
                        TOKEN = data.token;
                    }
                    console.log(data);
                });

                return false;
            });

            
            layui.form.on('submit(pay' + $class + ')', function (formdata) {
                formdata = formdata.field;
                formdata.Format = 'json';
                 
                apptongxin(ELi['payment/admin_api'].url + TOKEN + FENGE + "add", formdata, function (data) {
                    if (data.token && data.token != "") {
                        TOKEN = data.token;
                    }

                    
                    if(data.code == 1){
                        var xhtml = "<form id='alipaysubmit' name='form' class='layui-form layui-form-pane' action='"+WZHOST+"payment/pay/' method='post' target='_blank'>";
                        xhtml += '<div class="layui-form-item"><input type="submit" class="layui-btn" value="点击 进行提交测试"></div>';
                        for(var nn in data.data){
                            xhtml +='<div class="layui-form-item"><label class="layui-form-label">'+nn+'</label><div class="layui-input-inline"><input type="text" name="'+nn+'"  value="'+data.data[nn]+'" placeholder="'+nn+'" value="0" autocomplete="off" class="layui-input"> </div></div>';
                        }
                        xhtml +='</form>';
                        layer.open({
                            title:"测试数据参数",
                            type: 1, 
                            content: xhtml //这里content是一个普通的String
                          });

                    }else{
                        layer.msg(data.msg);
                    }
                    
                    
                    
                });
                return false;
            });
            layui.form.render();
        });
        
       
        
    }
}
ELi['payment/admin_api'].init();