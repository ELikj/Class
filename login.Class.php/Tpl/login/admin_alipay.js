ELi['login/admin_alipay'] = {
    url: dir + Plus + FENGE + "login" + FENGE + "admin_alipay" + FENGE,
    init: function () {
        ELi['login/admin_alipay'].get();
    },
    edit(OBJ, diercai) {
        if (diercai != "add") {
            diercai = "put";
            var title = "编辑用户";
        } else {
            var title = "新建用户";
        }
        window.UIMUI = Array();
        var D = OBJ.data;
        var BIAO = [];
        var html = '<form name="form" id="form" class="layui-form ' + $class + '">';
        BIAO = [
            'id($$)id($$)hidden($$)($$)id($$)' + D.id,
            'uid($$)用户id($$)text($$)($$)($$)' + D.uid,
            'off($$)独立标识($$)text($$)($$)独立标识($$)' + D.off,
            'userid($$)userid($$)textshow($$)($$)userid($$)' + D.userid
   
           
           
            
        ];

        for (var z in BIAO) {
            html += jsfrom(BIAO[z]);
        }

        html += jsfrom('ff' + $class + '($$)($$)submit($$)($$)layer.close(OPID);ELi[\'login/admin_alipay\'].init()($$)提交($$)tijiao');
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
            apptongxin(ELi['login/admin_alipay'].url + TOKEN + FENGE + diercai, formdata, function (data) {
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
                        ELi['login/admin_alipay'].get();
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
        apptongxin(ELi['login/admin_alipay'].url + TOKEN + FENGE + 'del', fromdata, function (data) {
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
        ELi['login/admin_alipay'].edit({
            data: {"id":0,"uid":"0","qq":"","password":"","off":"0"}
        }, 'add');

    },
    get() {
        $("#LAY_app_body ." + $class).html('<style> .' + $class + ' .qfys0{color:#FF5722;} .' + $class + ' .qfys1{color:#009688;}.' + $class + ' .qfys2{color:green;}.' + $class + ' .qfys3{color:#1E9FFF;}</style><div class="layui-fluid"><div class="layui-card"><div class="layui-card-body" style="padding: 15px;">'
            +
            '<div class="' + $class + 'saixuan" style="display:none;margin-bottom:8px;"><form name="form" class="layui-form"> <div class="layui-inline" style="width:88px;"> <input class="layui-input so_var" name="so_uid" autocomplete="off" placeholder="用户UID"> </div> <div class="layui-inline"> <input class="layui-input so_var" name="so_userid" autocomplete="off" placeholder="userid"> </div>  <button class="layui-btn" lay-event="sousuo" lay-submit lay-filter="tijiao' + $class + '">搜索</button></form> </div>'
            +
            '<table class="layui-hide" id="user' + $class + '" lay-filter="user' + $class + '"></table></div></div></div>');
        layui.table.render({
            elem: '#user' + $class,
            url: ELi['login/admin_alipay'].url + TOKEN + FENGE + 'get',
            toolbar: '<div><a class="layui-btn " lay-event="refresh"><i class="layui-icon layui-icon-refresh-3"></i> 刷新</a> <a class="layui-btn layui-btn-danger" lay-event="saixuan"><i class="layui-icon layui-icon-search"></i>筛选</a> </div>',
            cols: [
                [{
                    field: 'id',
                    title: 'ID',
                    width: 120,
                    fixed: true
                }, {
                    field: "userid",
                    title: "userid"
                }, {
                    field: "uid",
                   
                    title: "用户id"
                }, {
                    field: "off",
                    
                    title: "独立标示"
                }, {

                    title: '操作',
                    
                    templet: function (d) {
                        return ' <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a> ';
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
                ELi['login/admin_alipay'].add();
            } else if (obj.event === 'refresh') {
                layer.closeAll();
                ELi['login/admin_alipay'].init();
            } else if (obj.event === 'saixuan') {
                $("." + $class + "saixuan").toggle();
            }
        });

        layui.table.on('tool(user' + $class + ')', function (obj) {
            if (obj.event === 'del') {
                layer.confirm('真的要删除吗?', function (index) {
                    ELi['login/admin_alipay'].del(obj);
                    layer.close(index);
                });
            } else if (obj.event === 'edit') {
                ELi['login/admin_alipay'].edit(obj);
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
                url: ELi['login/admin_alipay'].url + TOKEN + FENGE + 'get',
                where: zuhesou
            });
            return false;
        });

        layui.table.render();
    }
}
ELi['login/admin_alipay'].init();