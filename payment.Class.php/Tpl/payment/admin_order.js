ELi['payment/admin_order'] = {
    url: dir + Plus + FENGE + "payment" + FENGE + "admin_order" + FENGE,
    OFF:["等待分配","等待支付","支付成功","订单过期"],
    init: function () {
        ELi['payment/admin_order'].get();
        var xxx = '<option value="">全部状态</option>';
        for (var n in ELi['payment/admin_order'].OFF) {
            xxx += '<option value="' + n + '">' +ELi['payment/admin_order'].OFF[n] + '</option>';
        }
        $('.' + $class + ' [name="so_off"]').html(xxx);
    },
    edit(OBJ, diercai) {
        if (diercai != "add") {
            diercai = "put";
            var title = "查看通知";
        } else {
            var title = "新建产品";
        }
        window.UIMUI = Array();
        var D = OBJ.data;
        var BIAO = [];
        var html = '<form name="form" id="form" class="layui-form ' + $class + '">';
        BIAO = [
            'id($$)id($$)hidden($$)($$)id($$)' + D.id,
            'orderid($$)用户订单($$)textshow($$)($$)用户订单($$)' + D.orderid,
            'subject($$)订单标题($$)text($$)($$)订单标题($$)' + D.subject,
            'type($$)支付类型($$)selectshow($$)($$)ELi["payment/admin_order"].TYPE($$)' + D.type,
            'off($$)订单状态($$)selectshow($$)($$)ELi["payment/admin_order"].OFF($$)' + D.off,
            'uid($$)用户uid($$)text($$)($$)用户uid($$)' + D.uid,
            'moeny($$)订单金额($$)text($$)($$)订单金额($$)' + D.moeny,
            'notify($$)异步通知($$)text($$)($$)异步通知($$)' + D.notify,
            'return($$)同步通知($$)text($$)($$)同步通知($$)' + D.return,
            'anget($$)浏览器($$)text($$)($$)浏览器($$)' + D.anget,
            'ip($$)ip($$)text($$)($$)ip($$)' + D.ip,
            'codeid($$)码库id($$)text($$)($$)码库id($$)' + D.codeid,
            'paymoeny($$)支付金额($$)text($$)($$)支付金额($$)' + D.paymoeny,
            'noticeid($$)通知id($$)textshow($$)($$)异步处理的对应id($$)' + D.noticeid,
            'remarks($$)订单备注($$)text($$)($$)订单备注($$)' + D.remarks,
            'productid($$)产品id($$)textshow($$)($$)产品id($$)' + D.productid,
            'body($$)产品返回($$)textarea($$)($$)产品返回($$)' + D.body,
            'atime($$)录入时间($$)time($$)($$)录入时间($$)' + D.atime,
            'ctime($$)处理时间($$)time($$)($$)处理时间($$)' + D.ctime
        ];

        for (var z in BIAO) {
            html += jsfrom(BIAO[z]);
        }

        html += jsfrom('ff' + $class + '($$)($$)submit($$)($$)layer.close(OPID);ELi[\'payment/admin_order\'].init()($$)异步通知补单($$)tijiao');
        html += "</form>";
        OPID = layer.open({
            type: 1,
            zIndex: 10000,
            title: title,
            area: ['100%', '100%'],
            fixed: true,
            maxmin: true,
            content: html,
            success: function (layero, index) {
            }
        });

        layui.form.on('submit(ff' + $class + ')', function (formdata) {
            formdata = formdata.field;
            formdata.Format = 'json';
            apptongxin(ELi['payment/admin_order'].url + TOKEN + FENGE + diercai, formdata, function (data) {
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

                    if (diercai == "put") {
                        OBJ.update(data.data);
                        layer.close(OPID);
                        layer.msg(data.msg, {
                            zIndex: 99999,
                            offset: 'c',
                            time: 2000
                        });
                    } else {
                        ELi['payment/admin_order'].get();
                        layer.close(OPID);
                    }

                } else {

                    layer.msg(data.msg, {
                        zIndex: 99999,
                        offset: 'c',
                        time: 2000
                    });
                }
            });
            return false;
        });

        layui.form.render();

    },soso(zuhesou){

        $("." + $class + "saixuan").show();
        for(var n in zuhesou){
    
            if($('.' + $class + " [name='so_"+n+"']").length >0){
                $('.' + $class + " [name='so_"+n+"']").val(zuhesou[n]); 
            }   
        }
        layui.table.reload('user' + $class, {
            page: {
                curr: 1 //重新从第 1 页开始
            },
            url: ELi['payment/admin_order'].url + TOKEN + FENGE + 'get',
            where: zuhesou
        });
        layui.form.render("select");
        layui.table.render();
        
    },del(OBJ) {
        var fromdata = {
            Format: 'json',
            id: OBJ.data.id
        };
        apptongxin(ELi['payment/admin_order'].url + TOKEN + FENGE + 'del', fromdata, function (data) {
            if (data.token && data.token != "") {
                TOKEN = data.token;
            }
            if (data.code == 99) {
                layer.msg("请登陆", {
                    offset: 'c',
                    time: 2000
                }, function () {
                    loadjs("home");
                });
            } else if (data.code == 1) {
                OBJ.del();
            } else {
                layer.msg(data.msg, {
                    zIndex: 99999,
                    offset: 'c',
                    time: 2000
                });
            }
        });
    },
    add() {
        ELi['payment/admin_order'].edit({
            data: {"id":0,"codeid":"0","type":"0","moeny":"0.00","atime":"0","remarks":"","orderid":"0"} }, 'add');

    },
    get() {
        $("#LAY_app_body ." + $class).html('<style> .' + $class + ' .qfys0{color:#FF5722;} .' + $class + ' .qfys1{color:#009688;}.' + $class + ' .qfys2{color:green;}.' + $class + ' .qfys3{color:#1E9FFF;}</style><div class="layui-fluid"><div class="layui-card"><div class="layui-card-body" style="padding: 15px;">'
            +
            '<div class="' + $class + 'saixuan" style="display:none;margin-bottom:8px;"><form name="form" class="layui-form"><div class="layui-inline" style="width:128px;"><select name="so_type" class="so_var"></select> </div> <div class="layui-inline" style="width:128px;"><select name="so_off" class="so_var"></select> </div> <div class="layui-inline" style="width:128px;"> <input class="layui-input so_var" name="so_orderid" autocomplete="off" placeholder="搜索系统订单"> </div>  <div class="layui-inline" style="width:128px;"> <input class="layui-input so_var" name="so_noticeid" autocomplete="off" placeholder="通知id"> </div>  <div class="layui-inline" style="width:128px;"> <input class="layui-input so_var" name="so_productid" autocomplete="off" placeholder="产品id"> </div> <div class="layui-inline" style="width:128px;"> <input class="layui-input so_var" name="so_id" autocomplete="off" placeholder="订单id"> </div>    <div class="layui-inline" style="width:128px;"> <input class="layui-input so_var" placeholder="开始时间" id="so_atimestart' + $class + '" name="so_atimestart" autocomplete="off"> </div> <div class="layui-inline" style="width:128px;"> <input class="layui-input so_var" placeholder="结束时间" id="so_atimeend' + $class + '" name="so_atimeend" autocomplete="off"> </div> <button class="layui-btn" lay-event="sousuo" lay-submit lay-filter="tijiao' + $class + '">搜索</button></form> </div>'
            +
            '<table class="layui-hide" id="user' + $class + '" lay-filter="user' + $class + '"></table></div></div></div>');
        layui.table.render({
            elem: '#user' + $class,
            url: ELi['payment/admin_order'].url + TOKEN + FENGE + 'get',
            toolbar: '<div> <a class="layui-btn " lay-event="refresh"><i class="layui-icon layui-icon-refresh-3"></i> 刷新</a> <a class="layui-btn layui-btn-danger" lay-event="saixuan"><i class="layui-icon layui-icon-search"></i>筛选</a> </div>',
            cols: [
                [{
                    field: 'id',
                    title: 'ID',
                    width: 150,
                    fixed: true
                },{
                    field: "orderid",
                    width: 288,
                    title: "系统订单"
                    

                },{
                    field: "type",
                    title: "通知类型",
                    templet: function (d) {

                        return ELi['payment/admin_order'].TYPE[d.type] ;
                    }
                },{
                    field: "moeny",
                    title: "订单金额"
                    
                },{
                    field: "paymoeny",
                    title: "支付金额",
                    templet: function (d) {
                        
                            return   '<span class="qfys'+d.off+'">' +d.paymoeny +'<span>';
                       
                    }
                },{
                    field: "off",
                    title: "订单状态",
                    templet: function (d) {
                        return '<span class="qfys'+d.off+'">' +ELi['payment/admin_order'].OFF[d.off]+'<span>';
                       
                    }
                }, {
                    field: "atime",
                    title: "订单时间",
                    templet: function (d) {

                        return time(d.atime);
                    }
                }, {

                    title: '操作',
                    templet: function (d) {
                        return '<a class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>查看</a>';
                    }
                }]
            ],
            limit: 20,
            page: true,
            done: function (data, curr, count) {
                if (data.token && data.token != "") {
                    TOKEN = data.token;
                }

                if (data.type) {
                    ELi['payment/admin_order'].TYPE = data.type;
                    layui.laydate.render({
                        elem: '#so_atimestart' + $class,
                        format: 'yyyy-MM-dd' //可任意组合
                    });
                    layui.laydate.render({
                        elem: '#so_atimeend' + $class,
                        format: 'yyyy-MM-dd' //可任意组合
                    });
                    
                    var xxx = '<option value="">全部类型</option>';
                    for (var n in data.type) {
                        xxx += '<option value="' + n + '">' + data.type[n] + '</option>';
                    }
                    $('.' + $class + ' [name="so_type"]').html(xxx);

                    

                    layui.form.render();
                }  

                
            }
        });
        layui.table.on('toolbar(user' + $class + ')', function (obj) {
            if (obj.event === 'add') {
                ELi['payment/admin_order'].add();
            } else if (obj.event === 'refresh') {
                layer.closeAll();
                ELi['payment/admin_order'].init();
            } else if (obj.event === 'saixuan') {
                $("." + $class + "saixuan").toggle();
            }
        });

        layui.table.on('tool(user' + $class + ')', function (obj) {
            if (obj.event === 'del') {
                layer.confirm('真的要删除吗?', function (index) {
                    ELi['payment/admin_order'].del(obj);
                    layer.close(index);
                });
            } else if (obj.event === 'edit') {
                ELi['payment/admin_order'].edit(obj);
            }
        });

        layui.form.on('submit(tijiao' + $class + ')', function (formdata) {
            formdata = formdata.field;
            var zuhesou = {};
            $('.' + $class + 'saixuan .so_var').each(function (i, v) {
                if ($(v).val() != "") {
                    zuhesou[$(v).attr('name').replace('so_', '')] = $(v).val();
                }
            });
            layui.table.reload('user' + $class, {
                page: {
                    curr: 1 //重新从第 1 页开始
                },
                url: ELi['payment/admin_order'].url + TOKEN + FENGE + 'get',
                where: zuhesou
            });
            return false;
        });

        layui.table.render();
    }
}
ELi['payment/admin_order'].init();