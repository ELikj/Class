ELi['payment/admin_tongji'] = {
    url: dir + Plus + FENGE + "payment" + FENGE + "admin_tongji" + FENGE,
    OFF:["未匹配","已匹配"],
    TYPE:[],
    init: function () {
        ELi['payment/admin_tongji'].get();
    },edit(formdata) {

        apptongxin(ELi['payment/admin_tongji'].url + TOKEN + FENGE + "get", formdata, function (data) {
            if (data.token && data.token != "") {
                TOKEN = data.token;
            }
            if (data.code == 99) {
                layer.close(OPID);
                layer.msg("请登陆", {
                    offset: 'c',
                    time: 2000
                }, function () {
                    loadjs("home");
                });
            } else if (data.code == 1) {
                var D = data.data.总统计;
                var html = "";
                html +='<div class="layui-form">';
                html +='<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;"><legend>总统计</legend> </fieldset>';
                html +='<table class="layui-table">';
                 for(var u  in D){
                    html +='<tr>';
                    var i = 0;
                    if(D[u]){
                        for(var mm in D[u]){
                            html +='<td>';
                            html += (i==0?"<span style='color:red;'>"+u+"</span>":"")+' '+mm+" : <span style='color:#1E9FFF;'>"+D[u][mm]+"</span>";
                            html +='</td>';
                            i++;
                        }
                    }
                    html +='</tr>';
                }  
                html +='</table>';


                D = data.data.订单统计;
                html +='<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;"><legend>订单统计</legend> </fieldset>';
                html +='<table class="layui-table">';

                 for(var u  in D){
                    html +='<tr>';
                    var i = 0;
                    if(D[u]){
                        for(var mm in D[u]){
                            html +='<td>';
                            html += (i==0?"<span style='color:#1E9FFF;'>"+u+"</span>":"")+' '+mm+" : <span style='color:#000;'>"+D[u][mm]+"</span>";
                            html +='</td>';
                            i++;
                        }
                    }
                    html +='</tr>';
                }  
                html +='</table>';

                D = data.data.APP通知;
                html +='<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;"><legend>APP通知</legend> </fieldset>';
                html +='<table class="layui-table">';
                 for(var u  in D){
                    html +='<tr>';
                    var i = 0;
                    if(D[u]){
                        for(var mm in D[u]){
                            html +='<td>';
                            html += (i==0?"<span style='color:#1E9FFF;'>"+u+"</span>":"")+' '+mm+" : <span style='color:#000;'>"+D[u][mm]+"</span>";
                            html +='</td>';
                            i++;
                        }
                    }
                    html +='</tr>';
                }  
                html +='</table>';

                D = data.data.产品销量;
                html +='<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;"><legend>产品销量</legend> </fieldset>';
                html +='<table class="layui-table">';
                 for(var u  in D){
                    html +='<tr>';
                    var i = 0;
                    if(D[u]){
                        for(var mm in D[u]){
                            html +='<td>';
                            html += (i==0?"<span style='color:#1E9FFF;'>"+u+"</span>":"")+' '+mm+" : <span style='"+(i>2?'color:red;':'color:#000;')+"'>"+D[u][mm]+"</span>";
                            html +='</td>';
                            i++;
                        }
                    }
                    html +='</tr>';
                }  
                html +='</table>';




                html +='</div>';

                $("#tongji" + $class ).html(html);
                
            } else {

                layer.msg(data.msg, {
                    zIndex: 99999,
                    offset: 'c',
                    time: 2000
                });
            }

            return false;
        });
     
  
      


    },del(OBJ) {
    },add() {
    },get() {

        var html = '<div class="' + $class + 'saixuan layui-table-tool" style="margin-bottom:8px;"><a class="layui-btn " lay-event="refresh" onclick="ELi[\'payment/admin_tongji\'].init();"><i class="layui-icon layui-icon-refresh-3"></i> 刷新</a> </div>';

        $("#LAY_app_body ." + $class).html('<div class="layui-fluid"><div class="layui-card"><div class="layui-card-body" style="padding: 15px;">'+html+'<div id="tongji' + $class + '" lay-filter="tongji' + $class + '"></div></div></div></div>');

        layui.laydate.render({
            elem: '#so_atimestart' + $class,
            format: 'yyyy-MM-dd' //可任意组合
        });
        layui.laydate.render({
            elem: '#so_atimeend' + $class,
            format: 'yyyy-MM-dd' //可任意组合
        });
        

        layui.form.on('submit(tijiao' + $class + ')', function (formdata) {
            formdata = formdata.field;
            var zuhesou = {};
            $('.' + $class + 'saixuan .so_var').each(function (i, v) {
                if ($(v).val() != "") {
                    zuhesou[$(v).attr('name').replace('so_', '')] = $(v).val();
                }
            });
            ELi['payment/admin_tongji'].edit(zuhesou);
            return false;
        });

        layui.form.render();
        ELi['payment/admin_tongji'].edit({});
        
    }
}
ELi['payment/admin_tongji'].init();