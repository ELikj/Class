ELi['cms/admin_content'] = {
    url:  dir + Plus + FENGE + "cms" + FENGE + "admin_content" + FENGE,
    TYPE :[],
    MORENID:0,
    tree:layui.tree,
    OFF:[ "关闭","审核","正常"],
    expansion:"",
    linshishuj:null,
    内容模版:{},
    init: function () {
        ELi['cms/admin_content'].get();
    },edit(OBJ, diercai) {

      OBJ.diercai = diercai;
      if (diercai != "add") {
          diercai = "put";
          var title = "编辑内容";
      } else {
          var title = "新建内容";
      }
      window.UIMUI = Array();
 
      var D = OBJ.data;
      ELi['cms/admin_content'].linshishuj = D.expansion;
      var BIAO = [];
      var html = '<form name="form"  class="layui-form ' + $class + '">';
      BIAO = [
            
        'id($$)id($$)hidden($$)($$)id($$)'+D.id,
        'name($$)内容名称($$)text($$)($$)内容名称($$)'+D.name+'($$)required',
        'keywords($$)关键词($$)caidan($$)($$)关键词($$)'+D.keywords,
        'describes($$)内容描述($$)text($$)($$)内容描述($$)'+D.describes,
        'subclass($$)subclass($$)hidden($$)($$)内容id($$)'+D.subclass,
        'subclassxxx($$)内容id($$)text($$)($$)内容id($$)',

        'url($$)内容URL($$)text($$)($$)内容URL($$)'+D.url,
        'link($$)指定链接($$)text($$)($$)指定链接($$)'+D.link,  
     
        'photo($$)缩略图($$)updateshow($$)($$)缩略图($$)'+D.photo,
        'photoalbum($$)图片集($$)moreupdateshow($$)($$)图片集($$)'+D.photoalbum,
        [
          'content',
          "内容",
          "ui",
          'height:300px;',
          ''
          ,
          D.content
        ],
        
        'off($$)分类状态($$)select($$)($$)ELi["cms/admin_content"].OFF($$)'+D.off,
        
        'contenttemplate($$)内容模版($$)select($$)($$)ELi["cms/admin_content"].内容模版($$)'+D.contenttemplate,
        [
          'remarks',
          "备注",
          "textarea",
          'height:88px;',
          ''
          ,
          D.remarks
        ],
        [
          'expansion',
          " ",
          "textshow",
          '',
          "",
          ""
          ,"增加"
        ],
        [
            'Expand',
            " ",
            "textshow",
            '',
            "",
            ""
            ,"增加"
        ],
       
        'int1($$)扩展int($$)text($$)($$)扩展int($$)'+D.int1,
        'int2($$)扩展int2($$)text($$)($$)扩展int2($$)'+D.int2,
        'float1($$)浮点1($$)text($$)($$)扩展浮点($$)'+D.float1,
        'float2($$)浮点2($$)text($$)($$)浮点2($$)'+D.float2,
        [
            'text1',
            "扩展文本",
            "textarea",
            'height:88px;',
            ''
            ,
            D.text1
        ],
            
        'uid($$)所属用户($$)text($$)($$)所属用户($$)'+D.uid,
        'adminuid($$)管理员($$)text($$)($$)管理员($$)'+D.adminuid,
        'recommend($$)推荐数($$)text($$)($$)推荐数($$)'+D.recommend,
        'sortsize($$)排序($$)text($$)($$)排序($$)'+D.sortsize,
        'popularity($$)人气($$)text($$)($$)人气($$)'+D.popularity,
        'atime($$)录入时间($$)time($$)($$)录入时间($$)'+D.atime,
        'xtime($$)修改时间($$)time($$)($$)修改时间($$)'+D.xtime
        
      ]
      for(var z in BIAO){
        html+=jsfrom(BIAO[z]);
      }

      html += jsfrom('ff' + $class + '($$)($$)submit($$)($$)layer.close(OPID);ELi[\'cms/admin_content\'].init()($$)提交($$)tijiao');
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
          apptongxin(ELi['cms/admin_content'].url + TOKEN + FENGE + diercai, formdata, function (data) {
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
                    ELi['cms/admin_content'].get();
                    layer.close(OPID);
                  } else {
                      ELi['cms/admin_content'].get();
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

      ELi['cms/admin_content'].tree.render({
        elem: "."+$class+'idx_subclassxxx',
        id: $class+'idx_subclassxxx'
        ,data: ELi['cms/admin_content'].TYPE
        ,onlyIconControl: true
        ,click: function(obj){
          
          $("."+$class+ "idx_subclassxxx .layui-tree-txt").css({color:"#555"});
          $(obj.elem[0]).find(".layui-tree-txt:first").css({color:"red"});
          $( "."+$class+" [name='subclass']").val(obj.data.id);
          ELi["cms/admin_content"].gokuoid(obj.data.id,D.id);
         
        }

      });
     

      $("."+$class+ "idx_subclassxxx [data-id='"+D.subclass+"'] .layui-tree-txt:first").css({color:"red"});
      ELi["cms/admin_content"].gokuoid(D.subclass,D.id);

      layui.form.render();

    },gokuoid(id,ID){

      apptongxin(ELi['cms/admin_content'].url + TOKEN + FENGE + 'del', {expansion:id,Expandid:ID}, function (data) {
        if (data.token && data.token != "") {
          TOKEN = data.token;
        }
        var D = data.data;
        if(D && D != ""){

            if(D.expansion && D.expansion != ""){
                ELi["cms/admin_content"].expansion = D.expansion;
                $("."+$class+"idx_expansion").replaceWith( jsfrom([
                    'expansion',
                    "扩展数据",
                    "expansion",
                    '',
                    ELi["cms/admin_content"].expansion,
                    ELi['cms/admin_content'].linshishuj
                    ,"增加"
                ])) ;
            }else{
                $("."+$class+"idx_expansion").html('');
            }
            if(D.Expand && typeof( D.Expand) != "undefined" ){
                var htmlx = "";
                for(var xx in D.Expand){
                    var Expand_= D.Expand[xx];
                    for(var x_x in Expand_){
                        htmlx+= jsfrom(Expand_[x_x]);
                    }
                    
                }
                $("."+$class+"idx_Expand").html(htmlx);
            }
            



            layui.form.render();
    
        }else{
          $("."+$class+"idx_expansion").html("");
        }


      });
    },del(OBJ) {
      var fromdata = {
        Format: 'json',
        id: OBJ.data.id
    };
    apptongxin(ELi['cms/admin_content'].url + TOKEN + FENGE + 'del', fromdata, function (data) {
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
      ELi['cms/admin_content'].edit({
          data: { "int2":0,"int1":0,"float1":"0.00","float2":"0.00","text1":"","id":"0","name":"","keywords":"","describes":"","subclass":ELi['cms/admin_content'].MORENID,"url":"","link":"","listtemplate":"","contenttemplate":"","uid":"0","adminuid":"0","recommend":"0","sortsize":"0","popularity":"0","photo":"","photoalbum":"","content":"","expansion":"","expansionform":"","atime":"0","xtime":"0","displayswitch":"0","off":"2","remarks":""}
      }, 'add');

  },get() {
    
      $("#LAY_app_body ." + $class).html('<div class="layui-fluid"><div class="layui-card"><div class="layui-card-body layui-row" style="padding: 15px;" >'
      + '<div class="layui-col-xs2 treexx'+$class+'" style="border: 1px solid #ddd;padding:10px;width:15%;margin-right:15px;"></div>'
      + '<div class="layui-col-xs10 admin_content'+$class+'"><div class="' + $class + 'saixuan" style="display:none;margin-bottom:8px;"><form name="form" class="layui-form"><div class="layui-inline" style="width:128px;"><select name="so_type" class="so_var"></select> </div> <div class="layui-inline" style="width:88px;"> <input class="layui-input so_var" name="so_uid" autocomplete="off" placeholder="用户UID"> </div>  <div class="layui-inline" style="width:88px;"> <input class="layui-input so_var" name="so_name" placeholder="搜索内容" autocomplete="off"> </div> <div class="layui-inline" style="width:88px;"> <input class="layui-input so_var" placeholder="开始时间" id="so_atimestart' + $class + '" name="so_atimestart" autocomplete="off"> </div> <div class="layui-inline" style="width:88px;"> <input class="layui-input so_var" placeholder="结束时间" id="so_atimeend' + $class + '" name="so_atimeend" autocomplete="off"> </div>   <button class="layui-btn" lay-event="sousuo" lay-submit lay-filter="tijiao' + $class + '">搜索</button></form> </div>'
      +
      '<table class="layui-hide" id="admin_content' + $class + '" lay-filter="admin_content' + $class + '"></table></div></div></div></div>');
    
      layui.table.render({
        elem: '#admin_content' + $class,
        url: ELi['cms/admin_content'].url + TOKEN + FENGE + 'get'+FENGE + ELi['cms/admin_content'].MORENID,
        toolbar: '<div><a class="layui-btn layui-btn-normal" lay-event="add"><i class="layui-icon layui-icon-addition"></i>新增</a> <a class="layui-btn " lay-event="refresh"><i class="layui-icon layui-icon-refresh-3"></i> 刷新</a> <a class="layui-btn layui-btn-danger" lay-event="saixuan"><i class="layui-icon layui-icon-search"></i>筛选</a> </div>',
        cols: [
            [{
                field: 'id',
                title: 'ID',
                width: 80,
                fixed: true
            }, {
                field: "uid",
                width: 100,
                title: "上传用户"
            }, {
                field: "name",
                title: "内容名字"
            }, {
                field: "off",
                width: 80,
                title: "状态",
                templet: function (d) {
                  return ELi['cms/admin_content'].OFF[d.off];
                }
            }, {
                field: "atime",
                width: 200,
                title: "添加时间",
                templet: function (d) {
                  return time(d.atime);
                }
            }, {

                title: '操作',
                width: 210,
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
            if(data.ALLTYPE){
              ELi["cms/admin_content"].内容模版 = data.内容模版;
              var xxx = '<option value="">全部类型</option>';
                for (var n in ELi['cms/admin_content'].OFF) {
                    xxx += '<option value="' + n + '">' + ELi['cms/admin_content'].OFF[n] + '</option>';
                }
             
                $('.' + $class + ' [name="so_type"]').html(xxx);
                ELi['cms/admin_content'].TYPE =  data.ALLTYPE;
                layui.laydate.render({
                  elem: '#so_atimestart' + $class,
                  format: 'yyyy-MM-dd' //可任意组合
              });
              layui.laydate.render({
                  elem: '#so_atimeend' + $class,
                  format: 'yyyy-MM-dd' //可任意组合
              });

              ELi['cms/admin_content'].tree.render({
                elem: '.treexx'+$class
                ,onlyIconControl: true 
                ,data: ELi['cms/admin_content'].TYPE
                ,click: function(obj){

                  $(".treexx"+$class+ " .layui-tree-txt").css({color:"#555"});
                  $(".treexx"+$class+ " [data-id='"+ELi['cms/admin_content'].MORENID+"'] .layui-tree-txt:first").css({color:"#009688"});
                  ELi['cms/admin_content'].MORENID = obj.data.id;
                  layui.table.reload('admin_content' + $class, {
                    page: {
                        curr: 1
                    },
                    url: ELi['cms/admin_content'].url + TOKEN + FENGE + 'get'+FENGE + ELi['cms/admin_content'].MORENID
                    
                });
                  
                
                }
              }); 
              $(".treexx"+$class+ " [data-id='"+ELi['cms/admin_content'].MORENID+"'] .layui-tree-txt:first").css({color:"#009688"});
              layui.form.render();
            }

            
        }
    });
    layui.table.on('toolbar(admin_content' + $class + ')', function (obj) {
        if (obj.event === 'add') {
            ELi['cms/admin_content'].add();
        } else if (obj.event === 'refresh') {
            layer.closeAll();
            ELi['cms/admin_content'].init();
        } else if (obj.event === 'saixuan') {
            $("." + $class + "saixuan").toggle();
        }
    });

    layui.table.on('tool(admin_content' + $class + ')', function (obj) {
        if (obj.event === 'del') {
            layer.confirm('真的要删除吗?', function (index) {
                ELi['cms/admin_content'].del(obj);
                layer.close(index);
            });
        } else if (obj.event === 'edit') {
            ELi['cms/admin_content'].edit(obj);
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
        layui.table.reload('admin_content' + $class, {
            page: {
                curr: 1 //重新从第 1 页开始
            },
            url: ELi['cms/admin_content'].url + TOKEN + FENGE + 'get'+FENGE + ELi['cms/admin_content'].MORENID,
            where: zuhesou
        });
        return false;
    });

    layui.table.render();

      
    }
}

ELi['cms/admin_content'].init();