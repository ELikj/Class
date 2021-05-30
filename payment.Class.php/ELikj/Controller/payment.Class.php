<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 */

class ELikj_payment{

    public  $plugin = "payment";

    function INSTALL($DBprefix = DBprefix,$features =[]){ //安装模版
        $db = db('features');
        $fan = $db  ->where(['pluginid' => $this->plugin])->find();
        if($fan){
            return "已经安装".$this->plugin;
        }

        $fan  = $db ->qurey("INSERT INTO `".$DBprefix."features` VALUES (NULL, 'payment', '', 'layui-icon-form', '个人免签', '个人二维码免签通知系统', '请勿用于违反国家法律法规的业务!', '请勿用于违反国家法律法规的业务!', '1', 1, '', 1621141824, '', '', '{\"码配置\":[\"默认二维码图片\",\"默认二维码说明备注\"],\"支付宝\":[\"\",\"请打开支付宝扫码支付\"],\"微信\":[\"\",\"请打开微信扫码支付\"],\"银行\":[\"\",\"请转账到银行\"],\"通信密钥\":[\"通信的密钥ID\",\"通信的密钥key\"],\"订单录入\":[\"\",\"\"],\"APP通知\":[\"\",\"\"],\"自动生成\":[\"\",\"\"],\"支付方式\":[\"支付宝\",\"微信\",\"银行\"],\"过期时间\":[\"600\"],\"联系客服\":[\"\"],\"站点标题\":[\"收银台\"],\"统计天数\":[\"7\"]}', 0, '', '{\"admin_product\":{\"name\":\"产品仓库\",\"typeico\":\"layui-icon-cart\",\"link\":\"\"},\"admin_order\":{\"name\":\"充值订单\",\"typeico\":\"layui-icon-rmb\",\"link\":\"\"},\"admin_code\":{\"name\":\"匹配码库\",\"typeico\":\"layui-icon-engine\",\"link\":\"\"},\"admin_notice\":{\"name\":\"APP通知\",\"typeico\":\"layui-icon-notice\",\"link\":\"\"},\"admin_tongji\":{\"name\":\"收款统计\",\"typeico\":\"layui-icon-chart-screen\",\"link\":\"\"},\"admin_api\":{\"name\":\"APi调试\",\"typeico\":\"layui-icon-app\",\"link\":\"\"}}', 0);","accse");
        $db ->qurey("CREATE TABLE `".$DBprefix."payment_code` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `type` tinyint unsigned DEFAULT '0' COMMENT '二维码类型',
            `moeny` decimal(30,2) unsigned DEFAULT '0.00' COMMENT '匹配金额',
            `paymoeny` decimal(30,2) unsigned DEFAULT '0.00' COMMENT '需要支付',
            `codeimg` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '二维码图片',
            `remarks` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '备注',
            `orderid` bigint unsigned DEFAULT '0' COMMENT '锁定订单id',
            `atime` int unsigned DEFAULT '0' COMMENT '占用时间',
            PRIMARY KEY (`id`),
            UNIQUE KEY `type` (`type`,`paymoeny`),
            KEY `moeny` (`moeny`),
            KEY `atime` (`atime`),
            KEY `type_2` (`type`,`moeny`),
            KEY `type_3` (`type`,`moeny`,`atime`),
            KEY `orderid` (`orderid`),
            KEY `atime_2` (`atime`,`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=200000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='二维码库';","accse");

        $db ->qurey("CREATE TABLE `".$DBprefix."payment_notice` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `codeid` bigint unsigned DEFAULT '0' COMMENT '码库维码id',
            `type` tinyint unsigned DEFAULT '0' COMMENT '充值类型',
            `moeny` decimal(30,2) unsigned DEFAULT '0.00' COMMENT '充值金额',
            `atime` int unsigned DEFAULT '0' COMMENT '记录时间',
            `remarks` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '备注',
            `orderid` bigint unsigned DEFAULT '0' COMMENT '订单id',
            `security` char(32) COLLATE utf8mb4_general_ci DEFAULT '',
            PRIMARY KEY (`id`),
            UNIQUE KEY `security` (`security`) USING BTREE,
            KEY `codeid` (`codeid`),
            KEY `type` (`type`),
            KEY `atime` (`atime`),
            KEY `orderid` (`orderid`),
            KEY `atime_2` (`atime`,`orderid`)
          ) ENGINE=InnoDB AUTO_INCREMENT=200000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='异步通知';","accse");

        $db ->qurey("CREATE TABLE `".$DBprefix."payment_order` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `orderid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '用户订单',
            `noticeid` bigint unsigned DEFAULT '0' COMMENT '异步处理的对应id',
            `notify` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '异步通知',
            `return` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '同步通知',
            `anget` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '浏览器信息',
            `ip` varchar(64) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT 'ip',
            `uid` bigint unsigned DEFAULT '0' COMMENT '用户uid',
            `moeny` decimal(30,2) unsigned DEFAULT '0.00' COMMENT '支付金额',
            `paymoeny` decimal(30,2) unsigned DEFAULT '0.00' COMMENT '支付的金额',
            `codeid` bigint unsigned DEFAULT '0' COMMENT '码库id',
            `productid` bigint unsigned DEFAULT '0' COMMENT '产品id',
            `subject` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '订单标题',
            `off` int unsigned DEFAULT '0' COMMENT '订单状态  0 提交 1 分配二维码  2 成功 3  过期',
            `atime` int unsigned DEFAULT '0' COMMENT '录入时间',
            `ctime` int unsigned DEFAULT '0' COMMENT '处理时间',
            `remarks` varchar(500) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '备注',
            `body` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '产品返回',
            `type` tinyint unsigned DEFAULT '0' COMMENT '支付类型',
            PRIMARY KEY (`id`),
            UNIQUE KEY `orderid` (`orderid`),
            KEY `codeid` (`codeid`),
            KEY `productid` (`productid`),
            KEY `off` (`off`),
            KEY `atime` (`atime`),
            KEY `ctime` (`ctime`),
            KEY `atime_2` (`atime`,`off`),
            KEY `uid` (`uid`),
            KEY `type` (`type`),
            KEY `id` (`id`,`off`),
            KEY `productid_2` (`productid`,`off`)
            ) ENGINE=InnoDB AUTO_INCREMENT=200000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='充值订单';","accse");
        $db ->qurey("CREATE TABLE `".$DBprefix."payment_product` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '产品名字',
            `remarks` varchar(500) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '产品备注',
            `moeny` decimal(30,2) unsigned DEFAULT '0.00' COMMENT '价格金额',
            `notify` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '请求发货',
            `skuid` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '自己的产品id',
            `akey` varchar(64) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '通信密码',
            `type` tinyint unsigned DEFAULT '0' COMMENT '处理方式',
            `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '产品内容',
            `off` tinyint unsigned DEFAULT '0' COMMENT '产品状态',
            `atime` int unsigned DEFAULT '0' COMMENT '处理时间',
            PRIMARY KEY (`id`),
            KEY `name` (`name`)
          ) ENGINE=InnoDB AUTO_INCREMENT=20000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='产品数据';","accse");
        return $fan;
    }

    function HTMLOUT($CANSHU ="",$features =[]){
        ?><!DOCTYPE html><html><head><meta charset="utf-8"><title><?php echo $CANSHU;?></title><meta name="renderer"content="webkit"><meta http-equiv="X-UA-Compatible"content="IE=edge,chrome=1"><meta name="viewport"content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"><style>*{padding:0;margin:0}body{background:#fafbfc;font-size:24px;color:#444;text-align:center}</style></head><body><div style="width:200px;margin:0 auto;padding-top:10%;"><p><?php echo $CANSHU;?></p></div></body></html><?php 
    }

    function NOTICE_POST($CANSHU ="",$features =[]){
        global $ELiConfig,$ELiMem;
        $ID =(int)$CANSHU;
        $MCHID = $features['configure']['订单录入']['0']??"";
        $MCKEY = $features['configure']['订单录入']['1']??"";
        $db = db("payment_order");
        $DATA = $db ->where(['id'=>$ID])->find();
        if(!$DATA){
            return echoapptoken([],-1,'订单数据错误','');
        }
        $fan = false;
        if($DATA['notify'] != "" &&  strstr($DATA['notify'] , '//') !== false ){
            $POST_ = [
                'mchid'=>$MCHID,
                'orderid'=>$DATA['orderid'],
                'id'=>$DATA['id'],
                'moeny'=>$DATA['moeny'],
                'paymoeny'=>$DATA['paymoeny'],
                'remarks'=>$DATA['remarks'],
                'off'=>$DATA['off'],
                'uid'=>$DATA['uid'],
                'productid'=> $DATA['productid'],
                'time'=>time(),
                'token'=>md5(uuid())
            ];
            $POST_['sign'] = md5($POST_['mchid'].$POST_['orderid'].$POST_['id'].$POST_['moeny'].$POST_['paymoeny'].$POST_['remarks'].$POST_['off'].$POST_['uid'].$POST_['productid'].$POST_['time'].$POST_['token'].$MCKEY );
            $fan = ELipost( trim($DATA['notify']), getarray($POST_),[ CURLOPT_TIMEOUT=>10 ] );
        }
        if($DATA['off'] == 2 && $DATA['productid'] > 0 && $DATA['body'] == ""){
            $product =  $db ->setbiao('payment_product')->where(['id'=>$DATA['productid']])->find();
            if($product){
                if($product['off'] == 2){

                    if( $product['type'] == '0'){

                        if($product['body'] != ""){
                            $db ->setbiao('payment_order')->where(['id'=> $DATA['id'] ]) ->update([ 'body'=>$product['body']]);
                        }

                    }else{

                        if($product['notify'] != "" &&  strstr($product['notify'] , '//') !== false ){
                            $POST_ = [
                                'id' => $DATA['id'],
                                'moeny'=>$DATA['moeny'],
                                'paymoeny'=>$DATA['paymoeny'],
                                'subject'=>$DATA['subject'],
                                'remarks'=>$DATA['remarks'],
                                'skuid'=> $product['skuid'],
                                'time'=>time()
                            ];
                            $POST_['sign'] = md5(
                                $POST_['subject'].$POST_['remarks'].$POST_['id'].$POST_['moeny']. $POST_['paymoeny']. $POST_['skuid']
                                .$POST_['time'].($product['akey']!="" ?$product['akey']:$MCKEY));
                            $body = ELipost( trim($product['notify']), getarray($POST_),[ CURLOPT_TIMEOUT=>10 ] );
                            if($body){
                                $db ->setbiao('payment_order')->where(['id'=> $DATA['id'] ]) ->update([ 'body'=> ELixss($body) ]);
                            }
                        }
                    }
                }
            }
        }

        if($fan){
            return ELixss($fan);
        }else{
            return true;
        }
    }

    function getbat($CANSHU=[],$features =[]){
        global $ELiConfig,$ELiMem;
        $hash = "getbat";
        if($ELiMem ->g($hash)){
            return echoapptoken([],-1,'time','');
        }
        $ELiMem ->s($hash,1,1);
        $GTIME = (int)($features['configure']['过期时间']['0']??300);
        $db = db("payment_order");
        $time = time() - $GTIME;
        $db ->where(['off'=>1,'atime <'=>$time]) ->update(['off'=>3,'ctime'=>time()]);
        $db ->where(['off'=>0,'atime <'=>$time-$GTIME]) ->update(['off'=>3,'ctime'=>time()]);
        $db ->setbiao('payment_code')->where(['atime <'=>$time,'atime >'=>0]) ->update(['atime'=>0,'orderid'=>0]);
        return echoapptoken([],1,'ok','');
    } 

    function bat($CANSHU=[],$features =[]){
        // bat 统一处理订单过期
        if(ELiy('Bat_Cli')){
            return echoapptoken([],-1,'Not Bat','');
        }
        $i = 0;
        while(true){
            sleep(2);

            $GTIME = (int)($features['configure']['过期时间']['0']??300);
            $i++;
            if( $i%30 == 0 ){
                $i = 1;
                $features = ELiplus($this -> plugin );
            }
            $db = db("payment_order");
            $time = time() - $GTIME;
            $db ->where(['off'=>1,'atime <'=>$time]) ->update(['off'=>3,'ctime'=>time()]);
            $db ->where(['off'=>0,'atime <'=>$time-$GTIME]) ->update(['off'=>3,'ctime'=>time()]);
            $db ->setbiao('payment_code')->where(['atime <'=>$time,'atime >'=>0]) ->update(['atime'=>0,'orderid'=>0]);
       
        }
    }
    
    function GUOLV( $data = ""){
        $guolv = array('<', '>', '=', '"', "'", 'script');
        $guoHO = array('', '', '', '', '', '');
        return str_ireplace($guolv, $guoHO, trim($data));
    }
    
    function paytest($CANSHU=[],$features =[]){ //测试
        p(json_encode([$_POST,$_GET],JSON_UNESCAPED_UNICODE));
    }

    function pay($CANSHU=[],$features =[]){ //去支付
        global $ELiConfig,$ELiMem;
        $DATA = [
            'orderid'=> $_POST['orderid']??"",
            'type'=>  (int)($_POST['type']??0),
            'subject'=> $this ->GUOLV($_POST['subject']??""),
            'productid'=> (int)($_POST['productid']??0),
            'uid'=> (int)($_POST['uid']??0),
            'notify'=> $this ->GUOLV($_POST['notify']??""),
            'return'=> $this ->GUOLV($_POST['return']??""),
            'moeny'=> number_format( ($_POST['moeny']??1),2,".",""),
            'time'=> (int)($_POST['time']??""),
            'remarks'=> $this ->GUOLV($_POST['remarks']??""),
            'mchid'=> $_POST['mchid']??"",
            'sign'=> $_POST['sign']??""
        ];
        $MCHID = $features['configure']['订单录入']['0']??"";
        $MCKEY = $features['configure']['订单录入']['1']??"";
        //支付方式读取
        $TYPE = $features['configure']['支付方式']??[];
        //过期时间
        $GTIME = (int)($features['configure']['过期时间']['0']??300);
        if(!isset($TYPE[$DATA['type']])){
            return echoapptoken([],-1,'支付方式错误','');
        }
        if(!isset($features['configure']['订单录入']) || $MCHID == "" || $MCKEY == ""){
            return echoapptoken([],-1,'充值关闭','');
        }
        if($DATA['mchid'] != $MCHID){
            return echoapptoken([],-1,'mchid 错误','');
        }
        if( strlen($DATA['orderid']) < 12){
            return echoapptoken([],-1,'orderid 错误 < 12','');
        }
        if($DATA['moeny'] < 0.01){
            return echoapptoken([],-1,'moeny 错误','');
        }
        if($DATA['time'] < time()){
            if( time() - $DATA['time'] >2){
                return echoapptoken([],-1,'time 错误','');
            }
        }else  if($DATA['time'] > time()){
            return echoapptoken([],-1,'time 错误','');
        }
        $sign = md5($DATA['uid'].$DATA['mchid'].$DATA['type'].$DATA['orderid'].$DATA['moeny'] .$DATA['time'] .$DATA['remarks'].$DATA['subject'].$DATA['productid'].$MCKEY);
        if($sign != $DATA['sign']){
            return echoapptoken([],-1,'sign 错误','');
        }
        $back = $_POST['back']??"html";
        $UHASH = 'token/'.$DATA['uid'];
        if($DATA['uid'] > 0 ){
            //读取缓存数据
            $Memdata = $ELiMem ->g($UHASH);
            if($Memdata){
                if($back == "json"){
                    return echoapptoken($Memdata,1,'ok','');
                }else{
                    tiaozhuan($Memdata);
                    return ;
                }
            }
        };
        //读取产品信息
        $productid = [];
        $db = db("payment_order");
        if($DATA['productid'] > 0){
            $productid = $db ->setbiao('payment_product')->where([ 'id' => $DATA['productid']] )->find();
            if(!$productid){
                return echoapptoken([],-1,'productid 错误','');
            }
            if($productid['off'] != 2){
                return echoapptoken([],-1,'productid 关闭','');
            }
            if($productid['moeny'] <= 0){
                return echoapptoken([],-1,'productid  金额错误','');
            }
            //强制给产品金额
            $DATA['moeny'] = $productid['moeny'];

        }else{

            if(strstr($DATA['notify'] , '//') === false ){
                return echoapptoken([],-1,'notify  不能为空','');
            }
        }
        $DATA['off']=0;
        $DATA['atime'] = time();
        $DATA['ip'] = ip();
        $DATA['anget'] = ELisub(platform(ELixss($GLOBALS['header']['user_agent'])),0,200);
        //插入订单
        $orderid = $db ->setbiao('payment_order')->pwhere()->insert($DATA);
        if(!$orderid){
            return echoapptoken([],-1,'订单插入错误','');
        }
        //金额 分类读取 二维码
        $code = $db ->setbiao('payment_code')->where(['type'=> $DATA['type'], 'moeny'=> $DATA['moeny'],'atime'=> 0 ])->find();
        //生成跳转查询url
        $TIAOZHUAN = [
            'orderid' =>$orderid,
            'token'=>md5(orderid()),
            'uuid'=>uuid(),
            'time'=>time()+$GTIME
        ];
        $TIAOZHUAN['sign'] = md5($TIAOZHUAN['time'].$MCHID.$TIAOZHUAN['orderid'].$TIAOZHUAN['token'].$MCKEY).md5($TIAOZHUAN['time'].$TIAOZHUAN['uuid'].$MCKEY);
        //跳转URL 组合
        $tiaourl = ELiLink([ $this -> plugin,'payfind',$TIAOZHUAN['uuid'],$TIAOZHUAN['orderid'],$TIAOZHUAN['token'],$TIAOZHUAN['sign'],$TIAOZHUAN['time']]);
        //查询是否有二维码
        if(!$code){
            //异步获取结果 删除创建好的订单
            if($back == 'json'){
                $db ->setbiao('payment_order') ->where(['id'=>$orderid ])->delete();
                return echoapptoken([],-1,'没有二维码','');
            }
            //写入缓存
            if($DATA['uid'] > 0 ){
                $ELiMem ->s($UHASH,$tiaourl,$GTIME);
            }
            tiaozhuan($tiaourl);
            return ;
        }
        //联合分配二维码 修改订单
        $sql = $db ->setshiwu(1)->setbiao('payment_code')
            ->where(['atime'=> 0,'id'=> $code['id']])
            ->update(['atime'=> time(),'orderid'=> $orderid]);
        $sql .= $db ->setshiwu(1)->setbiao('payment_order')
            ->where(['id'=>$orderid]) 
            ->update(['off'=> 1,'codeid'=> $code['id'],'atime'=> time()]);

        $fan = $db -> qurey($sql,'shiwu');
        if($back == 'json'){
            if($fan){
                $codeimg = $code['codeimg'] != '' ? $code['codeimg']: ($features['configure'][$TYPE[$DATA['type']]]['0']??"");

                if($codeimg == ""){
                    $db ->setshiwu('0')->setbiao('payment_order') ->where(['id'=>$orderid ])->delete();
                    $db ->setbiao('payment_code')->where(['id'=>$code['id']]) ->update(['atime'=>0,'orderid'=>0]);
                    return echoapptoken([],-1,'二维码地址错误','');
                }

                $canshu = [ 
                    'moeny'=>$DATA['moeny'], //订单金额
                    'paymoeny' =>$code['paymoeny'] , //匹配金额
                    'payurl'=> $codeimg,    //二维码图片
                    'id'=>$orderid,    //系统订单
                    'miao'=>$GTIME,    //过期秒数
                    'time'=> time()+$GTIME //过期时间戳
                ];
                if($DATA['uid'] > 0 ){
                    $ELiMem ->s($UHASH,$canshu,$GTIME);
                }

                return echoapptoken($canshu,1,'ok','');
            }else{
                $db ->setshiwu('0')->setbiao('payment_order') ->where(['id'=> $orderid ])->delete();
                return echoapptoken([],-1,'分配错误','');
            }
        }
        if($DATA['uid'] > 0 ){
            $ELiMem ->s($UHASH,$tiaourl,$GTIME);
        }
        tiaozhuan($tiaourl);
        return ;
    }


    function notice($CANSHU=[],$features =[]){ //异步通知
        ///payment/notice/100/1621257456/0/202b2a7a99e514297c497fd4efbd29da/签名/
        global $ELiConfig;
        $MCHID = $features['configure']['APP通知']['0']??"";
        $MCKEY = $features['configure']['APP通知']['1']??"";
        $TYPE = $features['configure']['支付方式']??[];
        if($MCHID == "" || strlen($MCKEY) < 12){
            return echoapptoken([],-1,'异步通信关闭','');
        }
        $MONEY = (int)($CANSHU['0']??0);
        $SIGN  = $CANSHU['4']??"";
        $TOKEN = $CANSHU['3']??"";
        $LEIXIN = (int)($CANSHU['2']??0);
        $TIME  = $CANSHU['1']??"";
        if($MONEY <= 0){
            return echoapptoken([],-1,'金额错误','');
        }
        if(!isset( $TYPE[$LEIXIN] )){
            return echoapptoken([],-1,'分类错误','');
        }
        if(time() - $TIME > 15){
            return echoapptoken([],-1,'超时','');
        }
        if($SIGN != md5($MCHID."#".$MONEY."#".$LEIXIN."#".$TIME."#".$TOKEN."#".$MCKEY) ){
            return echoapptoken([],-1,"签名错误" ,"");
        }
        $MONEY =  number_format($MONEY/100 ,2,".","");
        $security = md5($LEIXIN."#".$MONEY."#".$SIGN);
        $db = db("payment_notice");
        $noticeid = $db ->insert([
            'type'=>$LEIXIN,
            'moeny'=>$MONEY,
            'atime'=>time(),
            'security'=>$security 
        ]);
        if(!$noticeid){
            return echoapptoken([],-1,"录入失败" ,'');
        }else{
            $code = $db ->setbiao("payment_code")->where([ 'type'=>$LEIXIN,'paymoeny'=>$MONEY])->find();
            if(!$code){
                return echoapptoken([],1,"二维码错误" ,'');
            }
            if( $code['orderid'] == 0 ){
                return echoapptoken([],1,"订单错误" ,'');
            }
            $sql = $db ->setshiwu("1")-> setbiao("payment_notice")
                ->where(['id'=> $noticeid ])
                ->update(['codeid'=>$code['id'],'orderid' =>$code['orderid'] ]);
            $sql .= $db ->setshiwu("1")-> setbiao("payment_code")
                ->where(['id'=> $code['id'] ])
                ->update(['orderid'=>0,'atime' => 0 ]);
            $sql .= $db ->setshiwu("1")-> setbiao("payment_order")
                ->where(['id'=> $code['orderid'] ,'off' => 1 ])
                ->update(['off'=>2,'ctime' => time(),'noticeid'=>$noticeid,'paymoeny'=>$MONEY ]);
            $fan = $db -> qurey($sql,'shiwu');
            if(!$fan){
                return echoapptoken([],1,"更改错误" ,'');
            }
            $this -> NOTICE_POST($code['orderid'],$features);
            return echoapptoken([],1,"ok" ,'');
        }
    }

    function find($CANSHU=[],$features =[]){ //查询订单状态
        global $ELiConfig;
        $ORDERID = $CANSHU['0']??"";
        $QMING =($CANSHU['1']??"");
        $SIGN = ($CANSHU['2']??"");
        if($ORDERID == "" || strlen($ORDERID) < 12){
            return echoapptoken([],-1,'订单错误','');
        }
        if(strlen($SIGN) != 64){
            return echoapptoken([],-1,'签名错误','');
        }
        $MCHID = $features['configure']['订单录入']['0']??"";
        $MCKEY = $features['configure']['订单录入']['1']??"";

        if( $QMING !=  md5($MCHID.$SIGN.$ORDERID.$MCKEY) ){
            return echoapptoken([],-1,'签名错误','');
        }
        $db = db("payment_order");
        $DATA = $db ->zhicha("id,type,off,moeny,paymoeny")->where(['orderid' =>$ORDERID])->find();
        if(!$DATA){
            return echoapptoken([],-1,'订单不存在','');
        }
        if($DATA['off'] == '0'){

            $orderid = $DATA['id'];
            $code = $db ->setbiao('payment_code') ->where(['type'=> $DATA['type'], 'moeny'=> $DATA['moeny'],'atime'=> 0 ])->find();
            if($code){
                $sql = $db ->setshiwu(1)->setbiao('payment_code')
                    ->where(['atime'=> 0,'id'=> $code['id']])
                    ->update(['atime'=> time(),'orderid'=> $orderid]);

                $sql .= $db ->setshiwu(1)->setbiao('payment_order')
                    ->where(['id'=>$orderid]) 
                    ->update(['off'=> 1,'codeid'=> $code['id'],'atime'=> time()]);

                $fan = $db -> qurey($sql,'shiwu');
                if($fan){
                    $GTIME = (int)($features['configure']['过期时间']['0']??300);
                    $TYPE = $features['configure']['支付方式']??[];
                    $TIAOZHUAN = [
                        'orderid' =>$orderid,
                        'token'=>md5(orderid()),
                        'uuid'=>uuid(),
                        'time'=>time()+$GTIME
                    ];
                    $TIAOZHUAN['sign'] = md5($TIAOZHUAN['time'].$MCHID.$TIAOZHUAN['orderid'].$TIAOZHUAN['token'].$MCKEY).md5($TIAOZHUAN['time'].$TIAOZHUAN['uuid'].$MCKEY);
                    //跳转URL 组合
                    $DATA['tiaourl'] = ELiLink([ $this -> plugin,'payfind',$TIAOZHUAN['uuid'],$TIAOZHUAN['orderid'],$TIAOZHUAN['token'],$TIAOZHUAN['sign'],$TIAOZHUAN['time']]);
                    $codeimg = $code['codeimg'] != '' ? $code['codeimg']: ($features['configure'][$TYPE[$DATA['type']]]['0']??"");
                    $DATA['paymoeny'] =$code['paymoeny'];
                    $DATA['payurl'] = $codeimg;
                    $DATA['id'] = $orderid;
                    $DATA['miao'] = $GTIME;
                    $DATA['time'] = time()+$GTIME ;
                }
            }
        }
        return echoapptoken($DATA,1,'ok','');
        
    }

    function Construct($CANSHU=[],$features =[]){
        global $ELiConfig,$ELiMem,$SESSIONID,$YHTTP;
        $ClassFunc = $CANSHU['-1'];
        unset($CANSHU['-1']);
        if ( strstr($ClassFunc , 'admin_') !== false ) {
            if( callELi("admin","loginok",[$YHTTP],[],false)){
                return ;
            }
        }
        try{
           return ELitpl($this -> plugin,$ClassFunc,$this);
           //$this ->$ClassFunc($CANSHU,$features);
        } catch (\Throwable $th) {
            return echoapptoken([],-1,$th->getmessage());
        }
    }
}