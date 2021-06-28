<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 */

class ELikj_cms{

    public  $plugin = "cms";
    public  $MSGBOX = "";

    function seo_optimization($CANSHU,$features){
        $AC = $CANSHU['1']??"get";
        $MM = $CANSHU['0']??"";
        if( !isset($features['configure']['采集密码']) ||  
            $features['configure']['采集密码']['0'] == "" ||
            $features['configure']['采集密码']['0'] != $MM
        ){
            return echoapptoken([],-1,"Pass Error","");
        }

        if($AC == 'get'){
            $db = db('cms_type');
            $hh = $db ->zhicha('id,name,subclass')->where()->select();
            $shuju = [];
            if($hh){
                foreach($hh as $DATA){
                    $shuju[] = $DATA;
                }
            }
            return echoapptoken($shuju,1,"","");

        }else if($AC == 'add'){   
            if(!isset($_POST['name']) ||  $_POST['name'] == ""){
                return echoapptoken([],-1,"","");
            }
            $cms_content = db("cms_content");
            $gengurl = false;
            if(! isset($_POST['url']) || $_POST['url'] == "" ){
                $gengurl = true;
            }else {
                $fanx = $this -> ADMIN_FINDURL($_POST['url']);
                if(!$fanx){
                    $gengurl = true;
                }
            }
            if(!isset($_POST['atime'])){
                $_POST['atime'] = $_POST['xtime'] = time();
            }

            $fanjie = $cms_content ->insert($_POST);
            if( $fanjie){
                if($gengurl){
                    $_POST['url'] = ($features['configure']['默认前缀']['1']?? "" ).$fanjie;
                    $fanx = $this -> ADMIN_FINDURL( $_POST['url'] );
                    if(!$fanx){
                        $_POST['url']  = 'content_'.$fanjie;
                    }
                    $cms_content ->where(['id'=>$fanjie ])->update( ['url' => $_POST['url'] ]);
                }
                return echoapptoken($fanjie,1,"","");
            }
            return echoapptoken([],-1,"","");
        }
    }


    function seo_rss($CANSHU,$features){
        $GLOBALS['head'] = "html";
        $urls = ['<?xml version="1.0" encoding="utf-8"?>'];
        $urls[] = '<rss version="2.0">';
        $urls[] = '<channel>';
        $db = db('cms_type');

        $WZID = $features['configure']['网站信息id']['0']??0;
        if($WZID > 0){
            $DATA = $this -> TYPE_find( $db , ['id'=>$WZID] );
            if($DATA ){
                $urls[] ='<title>'.$DATA['name'].'</title>';
                $urls[] ='<link>'.WZHOST.'</link>';
                $urls[] ='<description>'.$DATA['describes'].'</description>';
            }
            
        }
         
        $hh = $db ->zhicha('off,url,atime,name,describes,content')->where(['off'=>2])->select();
        if($hh){
            foreach($hh as $DATA){
                $urls[] ='<item>';
                $urls[] ='<title>'.$DATA['name'].'</title>';
                $urls[] ='<link>'.ELiLink([ $this->plugin, $DATA['url'] ]).'</link>';
                $urls[] ='<description>'.($DATA['describes'] != "" ?$DATA['describes']: ELisub (strip_tags($DATA['content']),0,150) ).'</description>';
                $urls[] ='</item>';
            }
        }

        $hh = $db ->setbiao('cms_content')->zhicha('off,url,atime,name,describes,content')->where(['off'=>2])->order('id desc')->limit(2000)->select();
        if($hh){
            foreach($hh as $DATA){
                $urls[] ='<item>';
                $urls[] ='<title>'.$DATA['name'].'</title>';
                $urls[] ='<link>'.ELiLink([ $this->plugin, $DATA['url'] ]).'</link>';
                $urls[] ='<description>'.($DATA['describes'] != "" ?$DATA['describes']: ELisub (strip_tags($DATA['content']),0,150) ).'</description>';
                $urls[] ='</item>';
            }
        }
        $urls[] = '</channel>';
        $urls[] = '</rss>';
        echo implode("\n",$urls);
    }

    function google_sitemap($CANSHU,$features){
        $GLOBALS['head'] = "html";
        $urls =['<?xml version="1.0" encoding="UTF-8"?>'];
        $urls[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $db = db('cms_type');
        $hh = $db ->zhicha('off,url,atime')->where(['off'=>2])->select();
        if($hh){
            foreach($hh as $DATA){

                $urls[] ='<url>';
                $urls[] = '<loc>'.ELiLink([ $this->plugin, $DATA['url'] ]).'</loc>';
                $urls[] = '<lastmod>'.date('Y-m-d',$DATA['atime']).'</lastmod>';
                $urls[] = '</url>';
            }
        }
        $hh = $db ->setbiao('cms_content')->zhicha('off,url,atime,id')->where(['off'=>2])->order('id desc')->limit(2000)->select();
        if($hh){
            foreach($hh as $DATA){
                $urls[] ='<url>';
                $urls[] = '<loc>'.ELiLink([ $this->plugin, $DATA['url'] ]).'</loc>';
                $urls[] = '<lastmod>'.date('Y-m-d',$DATA['atime']).'</lastmod>';
                $urls[] = '</url>';
            }
        }
        $urls[] = '</urlset>';
        echo implode("\n",$urls);
    }

    function baidu_sitemap($CANSHU,$features){
        $GLOBALS['head'] = "html";
        $urls =['<?xml version="1.0" encoding="UTF-8"?>'];
        $urls[] = '<urlset>';

        $db = db('cms_type');
        $hh = $db ->zhicha('off,url,atime')->where(['off'=>2])->select();
        if($hh){
            foreach($hh as $DATA){

                $urls[] ='<url>';
                $urls[] = '<loc>'.ELiLink([ $this->plugin, $DATA['url'] ]).'</loc>';
                $urls[] = '<lastmod>'.date('Y-m-d',$DATA['atime']).'</lastmod>';
                $urls[] = '<changefreq>always</changefreq>';
                $urls[] = '<priority>1.0</priority>';
                $urls[] = '</url>';
            }
        }

        $hh = $db ->setbiao('cms_content')->zhicha('off,url,atime,id')->where(['off'=>2])->order('id desc')->limit(2000)->select();
        if($hh){
            foreach($hh as $DATA){
                $urls[] ='<url>';
                $urls[] = '<loc>'.ELiLink([ $this->plugin, $DATA['url'] ]).'</loc>';
                $urls[] = '<lastmod>'.date('Y-m-d',$DATA['atime']).'</lastmod>';
                $urls[] = '<changefreq>always</changefreq>';
                $urls[] = '<priority>1.0</priority>';
                $urls[] = '</url>';
            }
        }
        $urls[] = '</urlset>';
        echo implode("\n",$urls);
    }

    function sitemap($CANSHU,$features){

        return $this->map($CANSHU,$features);
    }

    function map($CANSHU,$features){

        $GLOBALS['head'] = "html";
        $urls = array(WZHOST);
        $db = db('cms_type');
        $hh = $db ->zhicha('off,url')->where(['off'=>2])->select();
        if($hh){
            foreach($hh as $DATA){
                $urls[] = ELiLink([ $this->plugin, $DATA['url'] ]);
            }
        }
        $hh = $db ->setbiao('cms_content')->zhicha('off,url')->where(['off'=>2])->select();
        if($hh){
            foreach($hh as $DATA){
                $urls[] = ELiLink([ $this->plugin, $DATA['url'] ]);
            }
        }
        echo implode("\n",$urls);
    }


    function INSTALL($DBprefix = DBprefix,$features=[]){
        //安装常见
        $db = db('features');
        $fan = $db  ->where(['pluginid' => $this->plugin])->find();
        if($fan){
            return "已经安装".$this->plugin;
        }
        $fan  = $db ->qurey( "INSERT INTO `".$DBprefix."features` VALUES (NULL, 'cms', 'cms', 'layui-icon-website', 'cms系统', '常用cms 系统', 'U', 'http://www.elikj.com', '1', 1, '', 1590801009, '', '', '{\"列表模版\":[\"gonglist\",\"ztlist\"],\"内容模版\":[\"gonglv\",\"paihang\",\"zhuanti\"],\"默认前缀\":[\"list-\",\"\"],\"默认风格\":[\"game\"],\"网站信息id\":[\"13\"],\"缓存时间\":[\"0\"],\"游戏id\":[\"1\"],\"排行id\":[\"4\"],\"攻略id\":[\"3\"],\"语言下标\":[\"1\"]}', 1, 'index', '{\"admin_type\":{\"name\":\"分类管理\",\"typeico\":\"layui-icon-tree\",\"link\":\"\"},\"admin_content\":{\"name\":\"内容管理\",\"typeico\":\"layui-icon-table\",\"link\":\"\"}}', 0);","accse");
        $db ->qurey("CREATE TABLE `".$DBprefix."cms_content` (
         `id` bigint unsigned NOT NULL AUTO_INCREMENT,
         `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '分类名字',
         `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '关键词',
         `describes` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '描述',
         `subclass` bigint unsigned DEFAULT '0' COMMENT '子分类',
         `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '内容url',
         `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '外联url',
         `contenttemplate` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '内容模版',
         `uid` bigint unsigned DEFAULT '0' COMMENT '用户uid',
         `adminuid` bigint unsigned DEFAULT '0' COMMENT '管理员id',
         `recommend` int unsigned DEFAULT '0' COMMENT '推荐参数',
         `sortsize` int unsigned DEFAULT '0' COMMENT '排序',
         `popularity` bigint unsigned DEFAULT '0' COMMENT '人气',
         `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '图片',
         `photoalbum` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '图片集',
         `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '分类内容',
         `expansion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '扩展数据',
         `atime` int unsigned DEFAULT '0' COMMENT '录入时间',
         `xtime` int unsigned DEFAULT '0' COMMENT '修改时间',
         `int1` bigint UNSIGNED NULL DEFAULT 0 COMMENT '数字1',
         `int2` bigint UNSIGNED NULL DEFAULT 0 COMMENT '数字2',
         `float1` decimal(20, 2) UNSIGNED NULL DEFAULT 0 COMMENT '金额1',
         `float2` decimal(20, 2) UNSIGNED NULL DEFAULT 0 COMMENT '金额2',
         `text1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '第二备注',
         `off` smallint unsigned DEFAULT '0' COMMENT '状态0 关闭 1 审核 2 正常',
         `remarks` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '备注',
         PRIMARY KEY (`id`),
         KEY `url` (`url`(250)),
         KEY `subclass` (`subclass`) USING BTREE,
         KEY `off` (`off`) USING BTREE,
         KEY `int1` (`int1`) USING BTREE,
         KEY `int2` (`int2`) USING BTREE,
         KEY `float1` (`float1`) USING BTREE,
         KEY `float2` (`float2`) USING BTREE,
         KEY `name` (`name`(250)) USING BTREE,
         KEY `keywords` (`keywords`(250)) USING BTREE,
         KEY `describes` (`describes`(250)) USING BTREE,
         KEY `subclass_off` (`subclass`,`off`) USING BTREE
       ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='cms内容';","accse");
        
        $fan = $db ->qurey("CREATE TABLE `".$DBprefix."cms_type` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '分类名字',
        `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '关键词',
        `describes` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '描述',
        `subclass` bigint unsigned DEFAULT '0' COMMENT '子分类',
        `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '内容url',
        `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '外联url',
        `listtemplate` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '列表模版',
        `contenttemplate` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '内容模版',
        `templatetype` smallint unsigned DEFAULT '1' COMMENT '类型 0 单页 1 列表',
        `uid` bigint unsigned DEFAULT '0' COMMENT '用户uid',
        `adminuid` bigint unsigned DEFAULT '0' COMMENT '管理员id',
        `recommend` int unsigned DEFAULT '0' COMMENT '推荐参数',
        `sortsize` int unsigned DEFAULT '0' COMMENT '排序',
        `popularity` bigint unsigned DEFAULT '0' COMMENT '人气',
        `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '图片',
        `photoalbum` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '图片集',
        `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '分类内容',
        `expansion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '扩展数据',
        `expansionform` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '扩展表单数据',
        `atime` int unsigned DEFAULT '0' COMMENT '录入时间',
        `xtime` int unsigned DEFAULT '0' COMMENT '修改时间',
        `displayswitch` smallint unsigned DEFAULT '0' COMMENT '显示开关',
        `off` smallint unsigned DEFAULT '0' COMMENT '状态0 关闭 1 审核 2 正常',
        `int1` bigint UNSIGNED NULL DEFAULT 0 COMMENT '数字1',
        `int2` bigint UNSIGNED NULL DEFAULT 0 COMMENT '数字2',
        `float1` decimal(20, 2) UNSIGNED NULL DEFAULT 0 COMMENT '金额1',
        `float2` decimal(20, 2) UNSIGNED NULL DEFAULT 0 COMMENT '金额2',
        `text1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '第二备注',
        `remarks` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '备注',
        `Expand` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '插件调用',
        PRIMARY KEY (`id`),
        KEY `url` (`url`(250)),
        KEY `subclass` (`subclass`) USING BTREE,
        KEY `off` (`off`) USING BTREE,
        KEY `int1` (`int1`) USING BTREE,
        KEY `int2` (`int2`) USING BTREE,
        KEY `float1` (`float1`) USING BTREE,
        KEY `float2` (`float2`) USING BTREE,
        KEY `displayswitch` (`displayswitch`) USING BTREE,
        KEY `name` (`name`(250)) USING BTREE,
        KEY `keywords` (`keywords`(250)) USING BTREE,
        KEY `describes` (`describes`(250)) USING BTREE,
        KEY `subclass_off` (`subclass`,`off`) USING BTREE
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='cms分类';","accse");
    

       return  $fan;
    }

    
    //读取扩展
    function ADMIN_expansionform($id){
        $db = db('cms_type');
        $data = $db ->zhicha("expansionform")->where(['id' => $id])->find();
        if(!$data){
            $datax = "";
        }else{
            $datax = $data['expansionform'];
        }
        return $datax;
    }
    //选择分类 排除上一个
    function ADMIN_FINDSUBCLASS($id,$pan){
        $db = db('cms_type');
        if($id < 1){
            return true;
        }
        $sss = $db ->where( ['id' => $id ])->find();
        if($sss){
            if($sss['subclass'] == $pan){
                return false;
            }
            return $this -> ADMIN_FINDSUBCLASS($sss['subclass'],$pan);
        }
        return true;
    }
    //前段查询url
    function ADMIN_FINDURL($urlx){
        if(!$urlx || $urlx == "" ){
            return false;
        }
        $db = db('cms_type');
        $ccc = $db ->zhicha('url')->where(['url' => $urlx])->find();
        if($ccc){
            return false;
        }
        $ccc = $db ->setbiao("cms_content")->zhicha('url')->where(['url' => $urlx])->find();
        if($ccc){
            return false;
        }
        return $urlx;

    }
    //前段删除分类及其子分类
    function ADMIN_TYPEDEL( $id ){
        $db = db('cms_type');
        $shuju = $db ->where(['subclass'=>$id])->select();
        if( $shuju ){
            foreach($shuju as $vvx){
                $fan = $db ->where(['id'=>$vvx['id']])->delete();
                if($fan){
                    ELilog('adminlog',$_SESSION['adminid'],5,$vvx,'admin_type');
                    $this-> ADMIN_TYPEDEL( $vvx['id'] );
                }
            }
        }
    }

    //前段获取分类列表
    function ADMIN_TYPELIST( $id ){
        $db = db('cms_type');
        $fanx = $db ->zhicha('id,subclass,name,templatetype')->where( ['subclass'=> $id ] )->order("sortsize desc ,recommend desc ,id asc")->select();
        $zuhe = [];
        if($fanx){

            foreach($fanx as $vv){
                $shujux = [
                    'title' =>$vv['id'].'.'.$vv['name'],
                    'id'=>$vv['id'],
                    'templatetype'=>$vv['templatetype'],
                    'spread'=>true
                ];
                $cccc =  $this->ADMIN_TYPELIST($vv['id']);
                if($cccc){
                    $shujux['children'] = $cccc ;
                }
                $zuhe[] = $shujux;
            }
        }
        return $zuhe;
    }

    function admin_type($CANSHU,$features){
        global $YHTTP;
        if( callELi("admin","loginok",[$YHTTP],[],false)){
            return ;
        }
        $ClassFunc = "admin_type";
        try {

            ELitpl($this -> plugin,$ClassFunc,$this);
           
        } catch (\Throwable $th) {
            return echoapptoken([],-1,$th->getmessage());
        }
    }

    function admin_other($CANSHU,$features){
        global $YHTTP;
        if( callELi("admin","loginok",[$YHTTP],[],false)){
            return ;
        }
        $ClassFunc = "admin_other";
        try {
            ELitpl($this -> plugin,$ClassFunc,$this);
        } catch (\Throwable $th) {
            return echoapptoken([],-1,$th->getmessage());
        }
    }

    function admin_content($CANSHU,$features){
        global $YHTTP;
        if( callELi("admin","loginok",[$YHTTP],[],false)){
            return ;
        }
        $ClassFunc = "admin_content";
        try {
            ELitpl($this -> plugin,$ClassFunc,$this);
        } catch (\Throwable $th) {
            return echoapptoken([],-1,$th->getmessage());
        }
    }

    // 读取单个类型
    function TYPE_find( $db = null, $where = [ ] ){
        if($db){
            
            $db ->setbiao('cms_type');
        }else{
            $db = db('cms_type');
        }
        $shuju = $db -> where($where)->find();
        if($shuju){

            $shuju['href'] = ELiLink([ $this->plugin, $shuju['url'] ]);
            $shuju['expansion'] = explode('(_$$_)',$shuju['expansion']);
            return $shuju;
        }
        return false;
    }

    //读取单个内容
    function CENTER_find( $db = null, $where = [ ] ){
        if($db){
            $db ->setbiao('cms_content');
        }else{
            $db = db('cms_content');
        }
        $shuju = $db -> where($where)->find();
        if($shuju){
            $shuju['href'] = ELiLink([ $this->plugin, $shuju['url'] ]);
            $shuju['expansion'] = explode('(_$$_)',$shuju['expansion']);
            return $shuju;
        }
        return false;
    }

    function TYPE_select( $db = null, $where = [ ],$order ='' ,$limit =''){
        if($db){
            
            $db ->setbiao('cms_type');
        }else{
            $db = db('cms_type');
        }
        $shuju = $db -> where($where)->order($order)->limit($limit)->select();
        return $shuju;
    }

    function CENTER_select( $db = null, $where = [ ],$order ='' ,$limit =''){
        if($db){
            
            $db ->setbiao('cms_content');
        }else{
            $db = db('cms_content');
        }
        $shuju = $db -> where($where)->order($order)->limit($limit)->select();
        return $shuju;
    }

    function TYPE_BAOHAN( $db = null,$id = 0){
        $zifuha = $this-> TYPE_BAOHAN_( $db ,(int)$id );
        $suode = [];
        $jixi = explode(',',$zifuha);
        foreach($jixi as $v){
            $suode[$v]= $v;
        }
      
        return implode(',',$suode);
    }

    function TYPE_BAOHAN_( $db = null,$id = 0){

        if($id < 0){
            return "";
        }
        if($db){
            
            $db ->setbiao('cms_type');
        }else{
            $db = db('cms_type');
        }
        $SUHAN = $id.',';
        $data = $db ->zhicha('id,subclass,off')->where(['subclass' => $id,'off'=>2])->select();
        if($data){
            foreach($data as $ssss){
               
                $SUHAN.= $ssss['id'].',';
                $SUHAN.= $this->TYPE_BAOHAN_($db,$ssss['id']).',';
            }
        }

        return trim($SUHAN,',');
    }

    function TIQUimg($neirong,$xiaobiao = -1){
        $neirong = str_replace(['"',"'"],["@","@"],$neirong);
        $IMG = [];
        preg_match_all('#src=@(.*)@#iUs', $neirong ."@", $matchesxx );
        if($matchesxx['1']){
            foreach($matchesxx['1'] as $tupian){
                $tupian = trim($tupian);
                if($tupian != ""){
                    $IMG[$tupian] = pichttp($tupian);
                }
            }
        }
        if(!$IMG){
            if($xiaobiao >= 0){
                return "";
            }else{
                return [];
            }
        }
        if($xiaobiao >= 0){
            $i = 0;
            foreach($IMG as $vv){
                if($i == $xiaobiao){
                    return $vv;
                }
            }
            return reset($IMG);
        }else{
            return $IMG;
        }
    }
    //搜索
    function e404($CANSHU,$features){
        $GLOBALS['head'] = "html";
        global $YHTTP,$ELiMem,$DATA,$ZDATA;
        $DATA=$ZDATA=[];
        $HUANTIME = (int)plusconfig('缓存时间');
        $GLOBALS['htmlcache'] = true;
        if($HUANTIME > 0){
            
            $HASH =  'html/'.md5(implode('_',$YHTTP ));
        
            $shuju = $ELiMem ->g($HASH);
            if($shuju){
                echo  $shuju;
                return ;
            } 
        }

        if($HUANTIME > 0){
            ob_flush();
        }
        $_STYLE = $features['configure']['默认风格']??['elikj'];
        $STYLE = reset($_STYLE );
        try {
            ELitpl($this -> plugin,$STYLE ."/404",$this);
        } catch (\Throwable $th) {
            echo $th->getmessage();
        }
        
        if($HUANTIME > 0 && $GLOBALS['htmlcache']){
            $neirong = ob_get_contents();
            $ELiMem ->s($HASH,$neirong  ,60);
        }
    }

    //搜索
    function soso($CANSHU,$features){
        $GLOBALS['head'] = "html";
        global $YHTTP,$ELiMem,$DATA,$ZDATA;
        $DATA=$ZDATA=[];
        $HUANTIME = (int)plusconfig('缓存时间');
        $GLOBALS['htmlcache'] = true;
        if($HUANTIME > 0){
            $YHTTP[] = $_GET['key']??"";
            $HASH =  'html/'.md5(implode('_',$YHTTP ));
        
            $shuju = $ELiMem ->g($HASH);
            if($shuju){
                echo  $shuju;
                return ;
            } 
        }
        if($HUANTIME > 0){
            ob_flush();
        } 
        $STYLE = plusconfig('默认风格' );
        try {
            ELitpl($this -> plugin,$STYLE ."/soso",$this);
        } catch (\Throwable $th) {
            echo $th->getmessage();
        }
        if($HUANTIME > 0 && $GLOBALS['htmlcache']){
            $neirong = ob_get_contents();
            $ELiMem ->s($HASH,$neirong  ,$HUANTIME );
        }
    }

    //首页
    function index($CANSHU,$features){
        $GLOBALS['head'] = "html";
        global $YHTTP,$ELiMem,$DATA,$ZDATA;
        $DATA=$ZDATA=[];
        $HUANTIME = (int)plusconfig('缓存时间');
        $GLOBALS['htmlcache'] = true;
        if($HUANTIME > 0){
            $HASH =  'html/'.md5(implode('_',['index']));
            $shuju = $ELiMem ->g($HASH);
            if($shuju){
                echo  $shuju;
                return ;
            }
        }

        $STYLE = plusconfig('默认风格' );
        if($HUANTIME > 0){
            ob_flush();
        }

        try {
            ELitpl($this -> plugin,$STYLE ."/index",$this);
        } catch (\Throwable $th) {
            echo $th->getmessage();
        }
        
        if($HUANTIME > 0 && $GLOBALS['htmlcache']){
            $neirong = ob_get_contents();
            $ELiMem ->s($HASH,$neirong  ,$HUANTIME );
        }
       
    }

    function get_renqi($CANSHU,$features){
        $id = (int)$CANSHU['0'];
        if($id > 0){
            global $ELiMem;
            $hahs = $ELiMem ->ja("get_renqi/".$id);

            if($hahs <=1 ){
                $db = db("cms_content");
                $data = $db ->zhicha("popularity") ->where(["id"=>$id])->find();
                if($data){
                    $hahs = $data['popularity']+1;
                    $ELiMem ->s("get_renqi/".$id,$hahs);
                }
            }
            if( $hahs%50 == 0){
                $db = db("cms_content");
                $db ->where(["id"=>$id])->update( ['popularity' => $hahs ]);
            }
           
            return echoapptoken($hahs);

        
        }

        
    }



    //默认读取参数
    function Construct($CANSHU,$features){

        $GLOBALS['head'] = "html";

        global $DATA,$ZDATA,$YHTTP,$ELiMem;
        $DATA=$ZDATA=[];
        $HUANTIME = (int)plusconfig('缓存时间');
        $GLOBALS['htmlcache'] = true;
        if($HUANTIME > 0){

            $HASH =  'html/'.md5(implode('_', $YHTTP ));
            $shuju = $ELiMem ->g($HASH);
            if($shuju){
                echo  $shuju;
                return ;
            }   
           
        }
   
        if($HUANTIME > 0){
            ob_flush();
        } 


        $STYLE = plusconfig('默认风格');
        $ClassFunc = $CANSHU['-1'];
        unset($CANSHU['-1']);
        
        $MOBAN = "";
        $db = db("cms_type");
        $lexing = "type";
        $DATA = $db ->where( ['url' => $ClassFunc])->find();
        if(!$DATA){
            $lexing = "content";
            $DATA = $db ->setbiao("cms_content")->where( ['url' => $ClassFunc])->find();
        }
        if(!$DATA){
            $this ->MSGBOX = $ClassFunc."不存在";
            return $this ->e404($CANSHU,$features);
        }

        if($DATA['off'] != 2){
            $this ->MSGBOX = $ClassFunc."关闭";
            return $this ->e404($CANSHU,$features);
        }

        $DATA['href'] = ELiLink([ $this->plugin, $DATA['url'] ]);
        if($DATA['subclass'] != '0'){
            $ZDATA = $db ->setbiao("cms_type")->where( ['id' => $DATA['subclass']])->find();
            if(!$ZDATA){
                $ZDATA = [];
            }else{
                $ZDATA['href'] = ELiLink([ $this->plugin, $ZDATA['url'] ]);
            }
        }

        if($lexing == "type"){

            if($DATA['templatetype']  == 1){
            
                if($DATA['listtemplate'] != ""){
                    $MOBAN = $DATA['listtemplate'];
                }else if(  isset($ZDATA['listtemplate']) &&  $ZDATA['listtemplate'] != "") {
                    $MOBAN = $ZDATA['listtemplate'];
                }else{
                    $MOBAN ="list";
                }
            }else{
                if($DATA['contenttemplate'] != ""){
                    $MOBAN = $DATA['contenttemplate'];
                }else if(  isset($ZDATA['contenttemplate']) &&  $ZDATA['contenttemplate'] != "") {
                    $MOBAN = $ZDATA['contenttemplate'];
                }else{
                    $MOBAN ="neirong";
                }
            }

        }else{

            if($DATA['contenttemplate'] != ""){
                $MOBAN = $DATA['contenttemplate'];
            }else if(  isset($ZDATA['contenttemplate']) &&  $ZDATA['contenttemplate'] != "") {
                $MOBAN = $ZDATA['contenttemplate'];
            }else{
                $MOBAN ="neirong";
            }
        }


        try {
            ELitpl($this -> plugin,$STYLE ."/".$MOBAN,$this);
        } catch (\Throwable $th) {
            echo $th->getmessage();
        }

        if($HUANTIME > 0 && $GLOBALS['htmlcache']){
            $neirong = ob_get_contents();
            $ELiMem ->s($HASH,$neirong  ,$HUANTIME );
        }
    }
}