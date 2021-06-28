ELi['cms/admin_type'] = {
    url:  dir + Plus + FENGE + "cms" + FENGE + "admin_type" + FENGE,
    TYPE :[],
    OFF:[ "关闭","审核","正常"],
    WITCH:[ "隐藏","显示"],
    分类类型:['单页','列表'],
    内容模版:{},
    列表模版:{},
    
    init: function () {
        ELi['cms/admin_type'].get();
    },
    edit(id) {
      window.UIMUI = Array();
      apptongxin(ELi['cms/admin_type'].url  + TOKEN + FENGE +  'get',{id:id},function(data){
        if(data.token && data.token != ""){
            TOKEN = data.token;
        }
        if (data.code == 99) {
          layer.msg("请登陆", {
              offset: 'c',
              time: 2000
          }, function () {
              loadjs("home");
          });
      }
        if(data.data){
          var D = data.data;
          var BIAO = [];
          var html ='<form name="form"  class="layui-form '+$class+'">';

          BIAO = [
            
            'id($$)id($$)hidden($$)($$)id($$)'+D.id,
            'name($$)分类名称($$)text($$)($$)分类名称($$)'+D.name+'($$)required',
            'keywords($$)关键词($$)caidan($$)($$)关键词($$)'+D.keywords,
            'describes($$)分类描述($$)text($$)($$)分类描述($$)'+D.describes,
            'subclass($$)subclass($$)hidden($$)($$)分类id($$)'+D.subclass,
            'subclassxxx($$)分类id($$)text($$)($$)分类id($$)',

            'url($$)分类URL($$)text($$)($$)分类URL($$)'+D.url,
            'link($$)指定链接($$)text($$)($$)指定链接($$)'+D.link,  
         
            'photo($$)缩略图($$)updateshow($$)($$)缩略图($$)'+D.photo,
            'photoalbum($$)图片集($$)moreupdateshow($$)($$)图片集($$)'+D.photoalbum,
            [
              'content',
              "分类内容",
              "ui",
              'height:300px;',
              ''
              ,
              D.content
            ],

            'off($$)分类状态($$)select($$)($$)ELi["cms/admin_type"].OFF($$)'+D.off,
            'displayswitch($$)导航显示($$)select($$)($$)ELi["cms/admin_type"].WITCH($$)'+D.displayswitch,
            'templatetype($$)分类类型($$)select($$)($$)ELi["cms/admin_type"].分类类型($$)'+D.templatetype,
            'listtemplate($$)列表模版($$)select($$)($$)ELi["cms/admin_type"].列表模版($$)'+D.listtemplate,
            'contenttemplate($$)内容模版($$)select($$)($$)ELi["cms/admin_type"].内容模版($$)'+D.contenttemplate,
            [
              'remarks',
              "备注",
              "textarea",
              'height:88px;',
              ''
              ,
              D.remarks
            ],
            'expansionform($$)扩展菜单($$)caidan($$)($$)扩展菜单($$)'+D.expansionform,  
            D.expansionform != "" ?[
              'expansion',
              "扩展数据",
              "expansion",
              '',
              D.expansionform,
              D.expansion
              ,"增加"
            ]:"",

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
            'Expand($$)扩展表单<br/>插件_函<br />参看说明($$)caidan($$)($$)插件_函数($$)'+D.Expand,
            
            'atime($$)录入时间($$)time($$)($$)录入时间($$)'+D.atime,
            'xtime($$)修改时间($$)time($$)($$)修改时间($$)'+D.xtime
            
          ]
          for(var z in BIAO){
            html+=jsfrom(BIAO[z]);
          }

          html+=jsfrom('ff'+$class+'($$)($$)submit($$)($$)ELi[\'cms/admin_type\'].init()($$)'+( id > 0?'提交':'新建')+'($$)nojs');
          html+="</form>";

          $('.admin_type'+$class).html(html);

          var tree = layui.tree;
       

          layui.form.on('submit(ff'+$class+')', function(formdata){
            formdata = formdata.field;
            formdata.Format = 'json';
            $.post(ELi['cms/admin_type'].url  + TOKEN + FENGE + (id > 0?"put":"add"),formdata,function(data){
              if(data.token && data.token != ""){
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
              
                ELi['cms/admin_type'].init();

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

          tree.render({
            elem: "."+$class+'idx_subclassxxx',
            id: $class+'idx_subclassxxx'
            ,data: D.ALLTYPE
            ,onlyIconControl: true
            ,click: function(obj){
              if(obj.data.id == D.id ){
                return ;
              }
              $("."+$class+ "idx_subclassxxx .layui-tree-txt").css({color:"#555"});
              $(obj.elem[0]).find(".layui-tree-txt:first").css({color:"red"});
              $( "."+$class+" [name='subclass']").val(obj.data.id);
            }

          });

          $("."+$class+ "idx_subclassxxx [data-id='"+D.subclass+"'] .layui-tree-txt:first").css({color:"red"});
          layui.form.render();
        };
       
      });

      
        
    },
    del(OBJ) {
        var fromdata = {
            Format: 'json',
            id: OBJ.data.id
        };
        apptongxin(ELi['cms/admin_type'].url + TOKEN + FENGE + 'del', fromdata, function (data) {
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
              ELi['cms/admin_type'].init();
            } else {
                layer.msg(data.msg, {
                    zIndex: 99999,
                    offset: 'c',
                    time: 2000
                });
            }
        });

    },htmlqing(){ //html清理

      apptongxin(ELi['cms/admin_type'].url  + TOKEN + FENGE +  'del',{id:-1},function(data){
        if(data.token && data.token != ""){
            TOKEN = data.token;
        }
        if (data.code == 99) {
          layer.msg("请登陆", {
              offset: 'c',
              time: 2000
          }, function () {
              loadjs("home");
          });
          return ;
        }
        layer.msg(data.msg, {
            offset: 'c',
            time: 2000
        });

        p(data);
      });

    },get() {
      $("#LAY_app_body ." + $class).html('<div class="layui-fluid"><div class="layui-card"><div class="layui-card-body layui-row" style="padding: 15px;" > <button type="button" class="layui-btn" onclick="ELi[\'cms/admin_type\'].htmlqing();">清理html缓存</button> <br /><br />'
      + '<div class="layui-col-xs2 tree'+$class+' " style="border: 1px solid #ddd;padding:10px;width:15%;margin-right:15px;"></div>'
      + '<div class="layui-col-xs10 admin_type'+$class+'"></div>'
      +
      '</div></div></div>');
      var tree = layui.tree;

      apptongxin(ELi['cms/admin_type'].url  + TOKEN + FENGE +  'get',{id:0},function(data){
        if(data.token && data.token != ""){
            TOKEN = data.token;
        }
        if (data.code == 99) {
          layer.msg("请登陆", {
              offset: 'c',
              time: 2000
          }, function () {
              loadjs("home");
          });
          return ;
      }
        if(data.data){
          ELi['cms/admin_type'].TYPE =  data.data.ALLTYPE;
          ELi["cms/admin_type"].内容模版 = data.data.内容模版;
          ELi["cms/admin_type"].列表模版 = data.data.列表模版;
          tree.render({
            elem: '.tree'+$class
            ,onlyIconControl: true 
            ,data: ELi['cms/admin_type'].TYPE
            ,edit: ['del']
            ,click: function(obj){
              
              $(".tree"+$class+ " .layui-tree-txt").css({color:"#555"});
              $(".tree"+$class+ " [data-id='"+obj.data.id+"'] .layui-tree-txt:first").css({color:"#009688"});
              ELi['cms/admin_type'].edit(obj.data.id);

            },operate: function(obj){
               
                if(obj.data.id  == '-1'){
                  setTimeout(function(){
                    $('.tree'+$class+' .layui-tree-emptyText').hide();
                  },10);
                  
                  return false;
                }
                if(obj.type == 'del' ){
                  ELi['cms/admin_type'].del(obj);
                }
                return true;
            }
          }); 

          $(".tree"+$class+ "  [data-id='0'] .layui-tree-txt:first").css({color:"red"});

        }
      });
    }
}
ELi['cms/admin_type'].init();