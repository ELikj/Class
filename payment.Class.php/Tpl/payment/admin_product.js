ELi['payment/admin_product'] = {
    url: dir + Plus + FENGE + "payment" + FENGE + "admin_product" + FENGE,
    OFF:["关闭","审核","正常"],
    TYPE:["显示产品内容","API处理"],
    init: function () {
        ELi['payment/admin_product'].get();
        var dataoff = ELi['payment/admin_product'].OFF;
        var xxx = '<option value="">全部状态</option>';
        for (var n in dataoff) {
            xxx += '<option value="' + n + '">' + dataoff[n] + '</option>';
        }
        $('.' + $class + ' [name="so_off"]').html(xxx);
        layui.form.render();
    },
    edit(OBJ, diercai) {
        if (diercai != "add") {
            diercai = "put";
            var title = "编辑产品";
        } else {
            var title = "新建产品";
        }
        window.UIMUI = Array();
        var D = OBJ.data;
        var BIAO = [];
        var html = '<form name="form" id="form" class="layui-form ' + $class + '">';
        BIAO = [
            D.id> 0?'qdtimxxxe($$)id($$)textshow($$)($$)($$)' + D.id:null,
            'id($$)id($$)hidden($$)($$)id($$)' + D.id,
            'name($$)产品名字($$)text($$)($$)产品名字($$)' + D.name,
            'moeny($$)价格金额($$)text($$)($$)价格金额($$)' + D.moeny,
            'skuid($$)SKU($$)text($$)($$)自己的产品id($$)' + D.skuid,
            'body($$)产品内容($$)textarea($$)($$)产品内容($$)' + D.body,
            'remarks($$)产品备注($$)text($$)($$)产品备注($$)' + D.remarks,
            'type($$)处理方式($$)select($$)($$)ELi["payment/admin_product"].TYPE($$)' + D.type,
            'notify($$)API地址($$)text($$)($$)API地址($$)' + D.notify,
            'akey($$)通信密码($$)text($$)($$)通信密码($$)' + D.akey,
            'off($$)产品状态($$)select($$)($$)ELi["payment/admin_product"].OFF($$)' + D.off,
            'atime($$)处理时间($$)time($$)($$)处理时间($$)' + D.atime
        ];



        for (var z in BIAO) {
            html += jsfrom(BIAO[z]);
        }

        html += jsfrom('ff' + $class + '($$)($$)submit($$)($$)layer.close(OPID);ELi[\'payment/admin_product\'].init()($$)提交($$)tijiao');
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
            apptongxin(ELi['payment/admin_product'].url + TOKEN + FENGE + diercai, formdata, function (data) {
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
                    } else {
                        ELi['payment/admin_product'].get();
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

    },
    del(OBJ) {
        var fromdata = {
            Format: 'json',
            id: OBJ.data.id
        };
        apptongxin(ELi['payment/admin_product'].url + TOKEN + FENGE + 'del', fromdata, function (data) {
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
        ELi['payment/admin_product'].edit({
            data: {"id":0,"name":"","remarks":"","moeny":"0.00","notify":"","skuid":"","akey":"","type":"0","body":"","off":"0","atime":"0"}
        }, 'add');

    },soso(id){
        loadjs('payment/admin_order','充值订单');
        setTimeout(function(){
            if(ELi['payment/admin_order'] && typeof(ELi['payment/admin_order'].soso) != "undefined" ){
                ELi['payment/admin_order'].soso({ productid:id,off:2});
            }
        },10);
        
    },
    get() {
        $("#LAY_app_body ." + $class).html('<style> .' + $class + ' .qfys0{color:#FF5722;} .' + $class + ' .qfys1{color:#009688;}.' + $class + ' .qfys2{color:green;}.' + $class + ' .qfys3{color:#1E9FFF;}</style><div class="layui-fluid"><div class="layui-card"><div class="layui-card-body" style="padding: 15px;">'
            +
            '<div class="' + $class + 'saixuan" style="display:none;margin-bottom:8px;"><form name="form" class="layui-form"><div class="layui-inline" style="width:128px;"><select name="so_off" class="so_var"></select> </div> <div class="layui-inline" style="width:88px;"> <input class="layui-input so_var" name="so_name" autocomplete="off" placeholder="搜索产品"> </div>   <button class="layui-btn" lay-event="sousuo" lay-submit lay-filter="tijiao' + $class + '">搜索</button></form> </div>'
            +
            '<table class="layui-hide" id="user' + $class + '" lay-filter="user' + $class + '"></table></div></div></div>');
        layui.table.render({
            elem: '#user' + $class,
            url: ELi['payment/admin_product'].url + TOKEN + FENGE + 'get',
            toolbar: '<div><a class="layui-btn layui-btn-normal" lay-event="add"><i class="layui-icon layui-icon-addition"></i>新增</a> <a class="layui-btn " lay-event="refresh"><i class="layui-icon layui-icon-refresh-3"></i> 刷新</a> <a class="layui-btn layui-btn-danger" lay-event="saixuan"><i class="layui-icon layui-icon-search"></i>筛选</a> </div>',
            cols: [
                [{
                    field: 'id',
                    title: 'ID',
                    width: 150,
                    fixed: true
                },  {
                    field: "name",
                    title: "产品名字",
                    templet: function (d) {

                        return   '<button onclick="ELi[\'payment/admin_product\'].soso('+d.id+')" class="layui-btn layui-btn-sm">查看销售</button> '+d.name;

                        
                    }
                }, {
                    field: "moeny",
                    title: "产品金额"
                },{
                    field: "off",
                    title: "产品状态",
                    templet: function (d) {

                        return '<span class="qfys' + d.off + '">' + ELi['payment/admin_product'].OFF[d.off] + '</span>';
                    }
                }, {
                    field: "atime",
                    title: "添加时间",
                    templet: function (d) {

                        return time(d.atime);
                    }
                }, {

                    title: '操作',
                    templet: function (d) {
                        return '<a class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a><a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>';
                    }
                }]
            ],
            limit: 20,
            page: true,
            done: function (data, curr, count) {
                if (data.token && data.token != "") {
                    TOKEN = data.token;
                }

                
            }
        });
        layui.table.on('toolbar(user' + $class + ')', function (obj) {
            if (obj.event === 'add') {
                ELi['payment/admin_product'].add();
            } else if (obj.event === 'refresh') {
                layer.closeAll();
                ELi['payment/admin_product'].init();
            } else if (obj.event === 'saixuan') {
                $("." + $class + "saixuan").toggle();
            }
        });

        layui.table.on('tool(user' + $class + ')', function (obj) {
            if (obj.event === 'del') {
                layer.confirm('真的要删除吗?', function (index) {
                    ELi['payment/admin_product'].del(obj);
                    layer.close(index);
                });
            } else if (obj.event === 'edit') {
                ELi['payment/admin_product'].edit(obj);
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
                url: ELi['payment/admin_product'].url + TOKEN + FENGE + 'get',
                where: zuhesou
            });
            return false;
        });

        layui.table.render();
    }
}
ELi['payment/admin_product'].init();