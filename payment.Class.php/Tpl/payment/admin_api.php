<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$shiba = false;
$plusfunction = 'admin_api';
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
$db = db('payment_code');
$where  = [];

if($kongzhi == 'get'){


    $MCHID = $features['configure']['APP通知']['0']??"";
    $MCKEY = $features['configure']['APP通知']['1']??"";

    $_TONGXIN = "个人免签:通信密码复制到APP中ELiKJ".WZHOST."ELiKJ".$MCHID."ELiKJ".$MCKEY."ELiKJ".time();

    return echoapptoken($features['configure']['支付方式'],1,$_TONGXIN,$SESSIONtoken);
}else if($kongzhi == 'put'){
    $iid = (int)($_POST['id']??0);
    $fan= $THIS ->NOTICE_POST($iid ,$features );
    return echoapptoken([],1,$fan,$SESSIONtoken);
}else if($kongzhi == 'add'){
    $DATA = [
        'orderid'=>orderid(),
        'type'=>$_POST['type']??0,
        'uid'=> (int)($_POST['uid']??0),
        'subject'=>'pay',
        'notify'=>$_POST['notify']??"",
        'return'=>$_POST['return']??"",
        'productid'=>$_POST['productid']??0,
        'moeny'=> number_format( ($_POST['moeny']??1),2,".",""),
        'time'=>time(),
        'remarks'=>'',
        'back'=>'html',
        'mchid'=> $features['configure']['订单录入']['0']
    ];

    $DATA['sign'] = md5( $DATA['uid'].$DATA['mchid'].$DATA['type'].$DATA['orderid'].$DATA['moeny'] .$DATA['time'] .$DATA['remarks'].$DATA['subject'].$DATA['productid'].$features['configure']['订单录入']['1'] );
    return echoapptoken($DATA,1,'ok',$SESSIONtoken);

}else if($kongzhi == 'del'){

}
return echoapptoken([],1,'ok',$SESSIONtoken);