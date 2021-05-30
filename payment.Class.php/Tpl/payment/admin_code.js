ELi['payment/admin_code'] = {
    url: dir + Plus + FENGE + "payment" + FENGE + "admin_code" + FENGE,
    OFF:["未匹配","已匹配"],
    TYPE:[],
    init: function () {
        ELi['payment/admin_code'].get();
        var dataoff = ELi['payment/admin_code'].OFF;
        var xxx = '<option value="">全部匹配状态</option>';
        for (var n in dataoff) {
            xxx += '<option value="' + n + '">' + dataoff[n] + '</option>';
        }
        $('.' + $class + ' [name="so_off"]').html(xxx);
        layui.form.render();
    },pladd(){
        var title = "批量生成";
        var BIAO = [];
        var html = '<form name="form" id="form" class="layui-form ' + $class + '">';
        BIAO = [
            'pldel($$)id($$)hidden($$)($$)id($$)add',
            'type($$)支付类型($$)select($$)($$)ELi["payment/admin_code"].TYPE($$)',
            'codeimg($$)批量码图<br />不传默认($$)updateshow($$)($$)($$)',
            'remarks($$)批量备注($$)text($$)($$)批量备注($$)',
            'moeny($$)金额范围($$)text($$)($$)金额范围 1-100 5,10,20,30($$)1,5,10,20,50,100,500',
            'jiange($$)金额间隔($$)text($$)($$)金额间隔($$)1',
            'num($$)次数($$)text($$)($$)次数($$)10',
            'paymoeny($$)每次增减($$)text($$)($$)每次增减($$)0.1',
            

        ];
        for (var z in BIAO) {
            html += jsfrom(BIAO[z]);
        }
        html += jsfrom('ff' + $class + '($$)($$)submit($$)($$)layer.close(OPID);ELi[\'payment/admin_code\'].init()($$)确认生成($$)tijiao');
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
            apptongxin(ELi['payment/admin_code'].url + TOKEN + FENGE + "add", formdata, function (data) {
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

                   
                    ELi['payment/admin_code'].get();
                    layer.close(OPID);
                    

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
    edit(OBJ, diercai) {
        if (diercai != "add") {
            diercai = "put";
            var title = "编辑匹配二维码";
        } else {
            var title = "新建匹配二维码";
        }
        window.UIMUI = Array();
        var D = OBJ.data;
        var BIAO = [];
        var html = '<form name="form" id="form" class="layui-form ' + $class + '">';
        BIAO = [
            'id($$)id($$)hidden($$)($$)id($$)' + D.id,
            'type($$)支付类型($$)select($$)($$)ELi["payment/admin_code"].TYPE($$)' + D.type,
            'moeny($$)匹配金额($$)text($$)($$)匹配金额($$)' + D.moeny,
            'paymoeny($$)支付金额($$)text($$)($$)支付金额($$)' + D.paymoeny,
            'codeimg($$)二维码图<br />不传默认($$)updateshow($$)($$)($$)' + D.codeimg,
            'remarks($$)备注($$)text($$)($$)备注($$)' + D.remarks,
            'orderid($$)匹配订单($$)text($$)($$)匹配订单($$)' + D.orderid,
            'atime($$)占用时间($$)time($$)($$)占用时间($$)' + D.atime
        ];

        for (var z in BIAO) {
            html += jsfrom(BIAO[z]);
        }
        html += jsfrom('ff' + $class + '($$)($$)submit($$)($$)layer.close(OPID);ELi[\'payment/admin_code\'].init()($$)提交($$)tijiao');
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
            apptongxin(ELi['payment/admin_code'].url + TOKEN + FENGE + diercai, formdata, function (data) {
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
                        ELi['payment/admin_code'].get();
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
        apptongxin(ELi['payment/admin_code'].url + TOKEN + FENGE + 'del', fromdata, function (data) {
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
    },add() {
        ELi['payment/admin_code'].edit({data: {"id":0,"type":"0","moeny":"","paymoeny":"","codeimg":"","remarks":"","orderid":"0","atime":"0"}}, 'add');
    },soso(id){
        loadjs('payment/admin_order','充值订单');
        setTimeout(function(){
            if( ELi['payment/admin_order'] &&  typeof(ELi['payment/admin_order'].soso) != "undefined" ){
                ELi['payment/admin_order'].soso({id:id});
            }
        },600);
    },
    get() {
        $("#LAY_app_body ." + $class).html('<style> .' + $class + ' .qfys0{color:#FF5722;} .' + $class + ' .qfys1{color:#009688;}.' + $class + ' .qfys2{color:green;}.' + $class + ' .qfys3{color:#1E9FFF;}</style><div class="layui-fluid"><div class="layui-card"><div class="layui-card-body" style="padding: 15px;">'
            +
            '<div class="' + $class + 'saixuan" style="display:none;margin-bottom:8px;"><form name="form" class="layui-form"><div class="layui-inline" style="width:128px;"><select name="so_type" class="so_var"></select> </div> <div class="layui-inline" style="width:128px;"><select name="so_off" class="so_var"></select> </div>  <div class="layui-inline" style="width:88px;"> <input class="layui-input so_var" name="so_moeny" autocomplete="off" placeholder="搜索金额"> </div> <div class="layui-inline" style="width:88px;"> <input class="layui-input so_var" name="so_orderid" autocomplete="off" placeholder="搜索订单"> </div>    <div class="layui-inline" style="width:88px;"> <input class="layui-input so_var" name="so_id" autocomplete="off" placeholder="码库id"> </div> <button class="layui-btn" lay-event="sousuo" lay-submit lay-filter="tijiao' + $class + '">搜索</button></form> </div>'
            +
            '<table class="layui-hide" id="user' + $class + '" lay-filter="user' + $class + '"></table></div></div></div>');
        layui.table.render({
            elem: '#user' + $class,
            url: ELi['payment/admin_code'].url + TOKEN + FENGE + 'get',
            toolbar: '<div><a class="layui-btn layui-btn-normal" lay-event="add"><i class="layui-icon layui-icon-addition"></i>新增</a> <a class="layui-btn " lay-event="refresh"><i class="layui-icon layui-icon-refresh-3"></i> 刷新</a> <a class="layui-btn layui-btn-normal" lay-event="pladd"><i class="layui-icon layui-icon-addition"></i>批量生成</a> <a class="layui-btn layui-btn-danger" lay-event="saixuan"><i class="layui-icon layui-icon-search"></i>筛选</a>  </div>',
            cols: [
                [{
                    field: 'id',
                    title: 'ID',
                    width: 150,
                    fixed: true
                },  {
                    field: "type",
                    title: "支付类型",
                    templet: function (d) {

                        return ELi['payment/admin_code'].TYPE[d.type] ;
                    }
                }, {
                    field: "moeny",
                    title: "匹配金额"
                }, {
                    field: "paymoeny",
                    title: "支付金额"
                },{
                    field: "orderid",
                    title: "匹配订单",
                    templet: function (d) {
                        if(d.orderid > 0){

                            return   '<button onclick="ELi[\'payment/admin_code\'].soso('+d.orderid+')" class="layui-btn layui-btn-sm">'+d.orderid+'</button>';

                        }else{
                            return d.orderid;
                        }
                    }

                }, {
                    field: "atime",
                    title: "占用时间",
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
                if (data.type) {
                    ELi['payment/admin_code'].TYPE = data.type;
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
                ELi['payment/admin_code'].add();
            } else if (obj.event === 'pladd') {
                ELi['payment/admin_code'].pladd();
            } else if (obj.event === 'refresh') {
                layer.closeAll();
                ELi['payment/admin_code'].init();
            } else if (obj.event === 'saixuan') {
                $("." + $class + "saixuan").toggle();
            }
        });
        layui.table.on('tool(user' + $class + ')', function (obj) {
            if (obj.event === 'del') {
                layer.confirm('真的要删除吗?', function (index) {
                    ELi['payment/admin_code'].del(obj);
                    layer.close(index);
                });
            } else if (obj.event === 'edit') {
                ELi['payment/admin_code'].edit(obj);
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
                url: ELi['payment/admin_code'].url + TOKEN + FENGE + 'get',
                where: zuhesou
            });
            return false;
        });
        layui.table.render();
    }
}
ELi['payment/admin_code'].init();