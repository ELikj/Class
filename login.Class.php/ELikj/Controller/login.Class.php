<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
/* 
 * 系统名称：以厘php框架
 * 官方网址：https://eLiphp.com
 * 版权所有：2009-2021 以厘科技 (https://eLikj.com) 并保留所有权利。 
 * 代码协议：开源代码协议 Apache License 2.0 详见 http://www.apache.org/licenses/
 */
class ELikj_login{

    public  $plugin = "login";
    public  $securitytime = 60; //缓存时间
    public  $securitynum = 3;  //最多数量

    function INSTALL($DBprefix = DBprefix,$features){
        $db = db('features');
        $fan = $db  ->where(['pluginid' => $this->plugin])->find();
        if($fan){
            return "已经安装".$this->plugin;
        }
        $fan = $db ->qurey("INSERT INTO `".$DBprefix."features` VALUES (NULL, 'login', '', 'layui-icon-user', '登陆模块', '登陆模块', 'U', 'http://www.elikj.com', '1', 1, '', 1612424720, '', '', '{\"有效ip数\":[\"100\"],\"金额赠送\":[\"0\",\"0\",\"0\"],\"积分赠送\":[\"0\",\"0\",\"0\"],\"货币赠送\":[\"0\",\"0\",\"0\"],\"赠送说明\":[\"自己\",\"1级\",\"2级\",\"superior配置级数以此类推\"],\"account验证码\":[\"0\"],\"注册模版\":[\"欢迎注册,验证码CODE\"],\"找回模版\":[\"找回,验证码CODE\"],\"登陆模版\":[\"登陆模版,验证码CODE\"],\"快捷注册\":[\"1\"],\"phone验证码\":[\"1\"],\"公众号\":[\"wxbf73b9a2f1b3fa66\",\"\"],\"小游戏\":[\"\",\"\"],\"开放平台\":[\"\",\"\"],\"小程序\":[\"\",\"\"],\"qq\":[\"101389895\",\"\"],\"支付宝网页\":[\"2019022263313226\",\"\",\"\"]}', 0, '', '{\"admin_account\":{\"name\":\"账号登陆\",\"typeico\":\"layui-icon-password\",\"link\":\"\"},\"admin_mailbox\":{\"name\":\"邮箱登陆\",\"typeico\":\"layui-icon-email\",\"link\":\"\"},\"admin_phone\":{\"name\":\"手机登陆\",\"typeico\":\"layui-icon-cellphone\",\"link\":\"\"},\"admin_weixin\":{\"name\":\"微信登陆\",\"typeico\":\"layui-icon-login-wechat\",\"link\":\"\"},\"admin_qq\":{\"name\":\"QQ登陆\",\"typeico\":\"layui-icon-login-qq\",\"link\":\"\"},\"admin_alipay\":{\"name\":\"支付宝登陆\",\"typeico\":\"layui-icon-auz\",\"link\":\"\"}};', 0);","accse");

        $db ->qurey("CREATE TABLE `".$DBprefix."login_account` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `uid` bigint unsigned DEFAULT '0',
            `account` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '账号',
            `findback` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '找回密码',
            `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '密码',
            `off` tinyint DEFAULT '0' COMMENT '独立标识',
            PRIMARY KEY (`id`),
            UNIQUE KEY `account` (`account`) USING BTREE,
            KEY `uid` (`uid`)
        ) ENGINE=InnoDB AUTO_INCREMENT= 10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='账号登陆';","accse");

        $db ->qurey("CREATE TABLE `".$DBprefix."login_mailbox` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `uid` bigint unsigned DEFAULT '0',
            `mailbox` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '邮箱',
            `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '密码',
            `off` tinyint DEFAULT '0' COMMENT '独立标识',
            PRIMARY KEY (`id`),
            UNIQUE KEY `mailbox` (`mailbox`) USING BTREE,
            KEY `uid` (`uid`)
          ) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='邮箱登陆';","accse");

        $db ->qurey("CREATE TABLE `".$DBprefix."login_phone` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `uid` bigint unsigned DEFAULT '0',
            `phone` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '手机号码',
            `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '密码',
            `off` tinyint DEFAULT '0' COMMENT '独立标识',
            PRIMARY KEY (`id`),
            UNIQUE KEY `phone` (`phone`) USING BTREE,
            KEY `uid` (`uid`)
          ) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='手机登陆';","accse");

        $db ->qurey("CREATE TABLE `".$DBprefix."login_weixin` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `uid` bigint unsigned DEFAULT '0',
            `openid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '公众号',
            `openidx` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '小程序',
            `openidg` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '小游戏',
            `openido` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '开放平台',
            `unionid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'UnionID机制',
            `off` tinyint DEFAULT '0' COMMENT '独立标识',
            PRIMARY KEY (`id`),
            UNIQUE KEY `unionid` (`unionid`) USING BTREE,
            KEY `uid` (`uid`),
            KEY `openid` (`openid`),
            KEY `openidx` (`openidx`),
            KEY `openidg` (`openidg`),
            KEY `openido` (`openido`)
          ) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='微信登陆';","accse");

        $db ->qurey("CREATE TABLE `".$DBprefix."login_qq` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `uid` bigint unsigned DEFAULT '0',
            `openid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '用户id',
            `unionid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '统一的',
            `off` tinyint DEFAULT '0' COMMENT '独立标识',
            PRIMARY KEY (`id`),
            UNIQUE KEY `unionid` (`unionid`) USING BTREE,
            KEY `uid` (`uid`)
          ) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='qq登陆';","accse");

        $db ->qurey("CREATE TABLE `".$DBprefix."login_alipay` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `uid` bigint unsigned DEFAULT '0',
            `userid` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '支付宝userid',
            `off` tinyint DEFAULT '0' COMMENT '独立标识',
            PRIMARY KEY (`id`),
            UNIQUE KEY `userid` (`userid`) USING BTREE,
            KEY `uid` (`uid`)
        ) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='支付宝登陆';","accse");

        return  $fan;
    }


    function Lang($name,$lang = 'cn'){
        global $ELiConfig;
        $YUYAN = [];
        $YUYAN['cn'] = [
            'phone_no'=>'手机不存在',
            'phone_ok'=>'手机已存在',
            'phone_error'=>'手机格式错误',
            'mailbox_no'=>'邮箱不存在',
            'mailbox_ok'=>'邮箱已存在',
            'mailbox_error'=>'邮箱格式错误',
            'fasong_ok'=>'验证码已发送请注意查收',
            'fasong_true'=>'发送成功',
            'fasong_false'=>'发送失败',
            'fasong_code'=>'请重新点击发送验证码',
            'account_error'=>'账号格式错误[2-32]',
            'password_error'=>"密码格式错误[6-64]",
            'password_ok'=>"和原密码一样,无需修改",
            'findback_error'=>'安全密码错误',
            'findback_password'=>'安全密码不能和密码相同',
            'findback_kong'=>'安全密码为空,联系管理员',
            'code_error'=>"验证码错误",
            'vcode_error'=>'图形验证码错误',
            'account_true'=>"账号存在",
            'account_no'=>'账号不存在',
            'password_no'=>'密码错误',
            'account_false'=>"已经绑定无法重复绑定",
            'uuid_error'=>"创建uid失败",
            'accountoff'=>'账号关闭',
            'login_true'=>'登陆成功',
            'password_upno'=>'更改失败',
            'password_upok' =>'更改成功',
            'reg_true'=>'注册成功',
            'reg_false'=>'注册失败',

        ];
        
        if(!isset($YUYAN[$lang])){
            $lang = 'cn';
        }

        if(!isset($YUYAN[$lang][$name])){
            return 'No '.$name;
        }
        return $YUYAN[$lang][$name];
    }

    function DelCode($name){ //删除验证码
        global $ELiMem;
        $HASH = "facode/".$name;
        return $ELiMem ->d($HASH);
    }

    function GetCode($name){ //获取验证码
        global $ELiMem;
        $HASH = "facode/".$name;
        return $ELiMem ->g($HASH);

    }

    function SetCode($name,$NUM = 60000){ //设置验证码
        global $ELiMem;
        $HASH = "facode/".$name;
        return $ELiMem ->s($HASH,"".$NUM,120);
    }

    function SHOUJI($hao,$neirong){ //手机发送消息
        $features = ELiplus($this -> plugin);
        ELibug($neirong,'fasong/'.$hao);
        return true;
    }

    function MAILBOX($hao,$neirong){ //邮箱发送消息
        $features = ELiplus($this -> plugin);
        ELibug($neirong,'fasong/'.$hao);
        return true;
    }

    function Fasong($CANSHU,$features =[]){ //发送数据
        $neirong = $features['configure'][$CANSHU['leixing']]['0'];
        $num  =  rand(100000,999999);
        $CANSHU["CODE"] = $num;
        foreach($CANSHU as $k=>$v){
            $neirong = str_replace($k,$v,$neirong);
        }
        if($CANSHU['type'] == 1){
            $fan = $this -> MAILBOX($CANSHU['zhanghao'],$neirong);
        }else{
            $fan = $this -> SHOUJI($CANSHU['zhanghao'],$neirong);
        }
        if($fan){
            $this ->SetCode($CANSHU['zhanghao'],$num);
            return true;
        }else{
            return false;
        }
    }

    /* 注册用户 */
    function REG($CANSHU,$features = []){
        global $ELiConfig,$ELiMem;
        $features = ELiplus($this -> plugin);
        $files = "";
        $isdownffile = false;
        if( isset($CANSHU['tou']) ){
            if( strstr( $CANSHU['tou'],"://")!== false){
                $qianzui = 'attachment/touxiang/'.date('Ym').'/'.md5($CANSHU['tou']).'.png';
                $files =  $ELiConfig['dir'].$qianzui;
                $WJIAN =  ELikj.'../'.ltrim( $qianzui  ,'/');
                ELiCreate($WJIAN);
                $isdownffile = true;
            }
        }
        $shuju = [
            'nickname'=> isset($CANSHU['name']) ? ELiSecurity( $CANSHU['name'] ) : uuid(),
            'sex'=> ( (int)$CANSHU['sex']."") ?? -1,
            'avatar'=>  $files,
            'regip'=> $CANSHU['ip']??ip(),
            'regtime'=>time(),
            'accountoff'=>1,
        ];
        $shuju['security'] = md5(implode('_$_',$shuju ));
        $tuid = (int)($CANSHU['tuid']??0);
        if($tuid > 0){
            $USER =  uid($tuid);
            if( $USER ){
                for($i =0; $i < $ELiConfig['superior'] ; $i++){
                    if($i == 0){
                        $shuju['superior'.$i] = $tuid;
                    }else{
                        $shuju['superior'.$i] = $USER['superior'.($i-1)];
                    }
                }
            }
        }
        $db = db("user");
        $uid = $db -> insert($shuju);
        if($uid){
           
            $HASH = date("Y-m-d")."/ipyan/".md5($shuju['regip']);
            $cishu = $ELiMem ->ja($HASH,1);
            if($cishu < $features['configure']['有效ip数']['0'] ){
                jiaqian( $uid , 0 , 
                $features['configure']['金额赠送']['0']??0 ,
                $features['configure']['积分赠送']['0']??0 ,
                $features['configure']['货币赠送']['0']??0, "reg" , $shuju['regip'] , "login");

                for($i =0; $i < $ELiConfig['superior'] ; $i++){
                    if(isset($shuju['superior'.$i])){
                        if($shuju['superior'.$i] > 0){
                            jiaqian( $shuju['superior'.$i] , 1 , 
                            $features['configure']['金额赠送'][($i+1)]??0 ,
                            $features['configure']['积分赠送'][($i+1)]??0 ,
                            $features['configure']['货币赠送'][($i+1)]??0,'reg_'.$uid, $shuju['regip'] , "login");
                        }
                    }
                }
            }
            if($isdownffile){
                if(!is_file($WJIAN) ){
                    $wejian =  ELiget($CANSHU['tou']);
                    if($wejian){
                        file_put_contents( $WJIAN, $wejian);
                    }
                }
            }
            return $uid;
        }
        return false;
    }

    function getuser($CANSHU,$features = []){
        $uuid = (int)ELihhGet('uid');
        if($uuid > 0){
            if(isset($_GET['state'])){
                global $ELiMem;
                $BACKCAN = $ELiMem ->g('opentoken/'.$_GET['state']);
                if($BACKCAN ){
                    $ELiMem ->d('opentoken/'.$_GET['state']);
                    return  echoapptoken($BACKCAN,1,"");
                }
            }
            return  echoapptoken([],1,"");
        }else{
            return  echoapptoken([],-1,"");
        }
    }

    function Start($BACKCAN = []){
        if($BACKCAN){

            if(isset( $BACKCAN['tiaozhuan'] )){
                tiaozhuan($BACKCAN['tiaozhuan']);
            }else{
                return  echoapptoken($BACKCAN,1,"登陆成功"); 
            }
        }else{
            return  echoapptoken([],1,"登陆成功");
        }
    }

    function Isemail($data){/*验证邮箱*/
        return preg_match('/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/',$data);
    }
     
    function Isshouji($data){/*验证手机*/
        return preg_match('/^1\d{10}$/',$data);
    }

    function code($CANSHU,$features){
        ELivcode();
        return ;
    }

    function tuiguang($CANSHU,$features){
        $TUID = 0;
        if(isset($CANSHU['0'])){
            $TUID  = (int)($CANSHU['0']??0); 
        }else if(isset($_GET['tuid'])){
            $TUID  = (int)$_GET['tuid'];
        }
        if($TUID  > 0){
            ELihhSet(['tuid'=>$TUID ]);
        }
        tiaozhuan(WZHOST);
        return ;
    }

    function Construct($CANSHU,$features){
        global $ELiConfig,$ELiMem,$SESSIONID,$YHTTP;
        $ClassFunc = $CANSHU['-1'];
        unset($CANSHU['-1']);
        if(isset($_GET['tuid'])){
            $tuid = (int)$_GET['tuid'];
            if($tuid > 0){
                ELihhSet(['tuid'=>$tuid]);
            }
        }

        if ( strstr($ClassFunc , 'admin_') !== false ) {
            if( callELi("admin","loginok",[$YHTTP],[],false)){
                return "";
            }
        }

        try { 
            return ELitpl($this -> plugin,$ClassFunc,$this);
        } catch (\Throwable $th) {
            return echoapptoken([],-1,$th->getmessage());
        }
    }
}