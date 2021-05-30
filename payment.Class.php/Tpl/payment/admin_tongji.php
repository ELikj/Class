<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$shiba = false;
$plusfunction = 'admin_tongji';
$hash = 'safetoken/'.$_SESSION['adminid'];
$SESSIONtokenx = $SESSIONtoken = $ELiMem ->g($hash);
if( trim($CANSHU['0']) != $SESSIONtokenx){
    $shiba = true;
}
$SESSIONtoken =  (uuid());
$ELiMem ->s($hash,$SESSIONtoken);
if( $shiba ){
    //return echoapptoken([],-1,"安全验证失败",$SESSIONtoken);
}
$kongzhi = isset($CANSHU['1'])?$CANSHU['1']:'get';
$db = db('payment_notice');
$where  = [];

if($kongzhi == 'get'){

    $TJNUM = (int)plusconfig('统计天数');
    if($TJNUM < 1){
        $TJNUM = 2;
    }
    if($TJNUM > 31){
        $TJNUM = 31;
    }

    $DATA = [
        "总统计" => [
            'APP通知'=>[],
            '匹配码库'=>[],
            '充值订单'=>[],
            '产品库'=>[]
        ],
        "产品销量" => [

        ],
        "订单统计" => [

        ],
        "APP通知" => [

        ]
    ];

    $DATA['总统计']['APP通知']['总数'] = "".$db ->setbiao('payment_notice')->total();
    $fan = $db -> qurey(" SELECT sum(moeny) as num from ".$db->biao());
    $daozhang = ((float)$fan['num']);
    $DATA['总统计']['APP通知']['到账'] = "<span style='color:red;'>".$daozhang."</span>";
    $fan = $db -> qurey(" SELECT sum(moeny) as num from ".$db->biao()." ".$db->wherezuhe([ 'orderid >'=> 0]));
    $DATA['总统计']['APP通知']['完成'] = "".(float)$fan['num'];
    $DATA['总统计']['APP通知']['待用'] = "".($daozhang- $DATA['总统计']['APP通知']['完成']);
 
    $DATA['总统计']['匹配码库']['总数'] = $db ->setbiao('payment_code')->total();
    $DATA['总统计']['匹配码库']['待用'] = $db ->where(['orderid'=>0])->total();
    $DATA['总统计']['匹配码库']['已用'] = "".($DATA['总统计']['匹配码库']['总数']- $DATA['总统计']['匹配码库']['待用']);

    $DATA['总统计']['充值订单']['总数']  = "".$db ->setbiao('payment_order')->total();
    $DATA['总统计']['充值订单']['处理']  = "".$db ->where(['off IN'=>'0,1'])->total();
    $DATA['总统计']['充值订单']['成功'] = "".$db ->where(['off'=>'2'])->total();
    $fan =  $db -> qurey(" SELECT sum(moeny) as moeny,sum(paymoeny) as paymoeny from ".$db->biao()." ".$db->wherezuhe([ 'off'=>'2']));
    $DATA['总统计']['充值订单']['金额'] =((float)$fan['moeny']).' ^ <span style="color:Red;">'.((float)$fan['paymoeny']).'</span>';
   
    $DATA['总统计']['产品仓库']['总数']  = "".$db ->setbiao('payment_product')->total();
    $DATA['总统计']['产品仓库']['正常']  = "".$db ->where(['off'=>2])->total();

    $DATA['总统计']['产品仓库']['销量']  = "".$db ->setbiao('payment_order')->where(['productid >'=>0,'off'=>2])->total();
    $fan =  $db -> qurey(" SELECT sum(moeny) as moeny,sum(paymoeny) as paymoeny from ".$db->biao()." ".$db->wherezuhe(['productid >'=>0,'off'=>2]));
    $DATA['总统计']['产品仓库']['销量金额']  = ((float)$fan['moeny']).' ^ <span style="color:Red;">'.((float)$fan['paymoeny']).'</span>';
 


    $db ->setbiao('payment_order');
    for($i = 0;$i< $TJNUM;$i++){
        
        $zuidi =   strtotime(date("Y-m-d", strtotime('-'.$i.' days')));
        $dqngqi =  strtotime(date("Y-m-d", $zuidi+3600*24));
        $today = date("Y-m-d",$zuidi);
        $DATA['订单统计'][$today] = [];
        $DATA['订单统计'][$today]['总数']  = "".$db ->where(['atime >'=>$zuidi,'atime <='=>$dqngqi])->total();
        $DATA['订单统计'][$today]['处理中']  = "".$db ->where(['off IN'=>'0,1','atime >'=>$zuidi,'atime <='=>$dqngqi])->total();
        $DATA['订单统计'][$today]['成功'] = "".$db ->where(['off'=>'2','atime >'=>$zuidi,'atime <='=>$dqngqi])->total();
        $fan =  $db -> qurey(" SELECT sum(moeny) as moeny,sum(paymoeny) as paymoeny from ".$db->biao()." ".$db->wherezuhe([ 'off'=>'2','atime >'=>$zuidi,'atime <='=>$dqngqi]));
        $DATA['订单统计'][$today]['订单金额'] ="".(float)$fan['moeny'];
        $DATA['订单统计'][$today]['支付金额'] ="".(float)$fan['paymoeny'];
    }

    $db ->setbiao('payment_notice');
    for($i = 0;$i< $TJNUM;$i++){
        
        $zuidi =   strtotime(date("Y-m-d", strtotime('-'.$i.' days')));
        $dqngqi =  strtotime(date("Y-m-d", $zuidi+3600*24));
        $today = date("Y-m-d",$zuidi);
        $DATA['APP通知'][$today] = [];
        $DATA['APP通知'][$today]['总数'] = "".$db ->where(['atime >'=>$zuidi,'atime <='=>$dqngqi])->total();
        $fan = $db -> qurey(" SELECT sum(moeny) as num from ".$db->biao()." ".$db->wherezuhe([ 'atime >'=>$zuidi,'atime <='=>$dqngqi ]));
        $DATA['APP通知'][$today]['到账金额'] = "".(float)$fan['num'];
        $fan = $db -> qurey(" SELECT sum(moeny) as num from ".$db->biao()." ".$db->wherezuhe([ 'orderid >'=> 0,'atime >'=>$zuidi,'atime <='=>$dqngqi ]));
        $DATA['APP通知'][$today]['处理金额'] = "".(float)$fan['num'];
        $DATA['APP通知'][$today]['未处理金额'] ="".($DATA['APP通知'][$today]['到账金额']- $DATA['APP通知'][$today]['处理金额']);

    }
    $chanpin = $db ->setbiao('payment_product')->zhicha("id,name,off")->select();
    $db ->setbiao('payment_order');
    if($chanpin){
        foreach($chanpin as $eli){
          
            $id = $eli['id'];
            $DATA['产品销量'][$eli['name']]['销量']  = $db->where(['productid'=>$id ,'off'=>2])->total();
            $DATA['产品销量'][$eli['name']]['状态']  = $eli['off'] =='2'?'<span style="color:green;">正常</span>':'<span style="color:red;">关闭</span>';

            $fan =  $db -> qurey(" SELECT sum(moeny) as moeny,sum(paymoeny) as paymoeny from ".$db->biao()." ".$db->wherezuhe(['productid'=>$id,'off'=>2]));
            $DATA['产品销量'][$eli['name']]['销量金额']  = "".((float)$fan['moeny']);
            $DATA['产品销量'][$eli['name']]['可得金额']  = "".((float)$fan['paymoeny']);

        }

    }






    return echoapptoken($DATA,1,'ok',$SESSIONtoken);

}else if($kongzhi == 'put'){
   
}else if($kongzhi == 'add'){

}else if($kongzhi == 'del'){

}
return echoapptoken([],1,'ok',$SESSIONtoken);