<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$shiba = false;
$plusfunction = 'admin_order';
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
$db = db('payment_order');
$where  = [];
if($kongzhi == 'get'){

    $TJNUM = (int)plusconfig('统计天数');
    if($TJNUM < 1){
        $TJNUM = 2;
    }
    if($TJNUM > 31){
        $TJNUM = 31;
    }

    $page = (int)(isset($_GET['page'])?$_GET['page']:1);
    $limitx = (int)(isset($_GET['limit'])?$_GET['limit']:10);
    if($limitx < 10 ){
        $limitx = 10;
    }else if($limitx > 100 ){
        $limitx = 100;
    }
    $limit = Limit($limitx,$page);
    $chushi = true;
    if(isset($_GET['type']) && $_GET['type'] != ""){
        $chushi = false; 
        $where['type'] = (int)$_GET['type'];
    }
    if(isset($_GET['id']) && $_GET['id'] != ""){
        $chushi = false; 
        $where['id'] = (int)$_GET['id'];
    }

    if(isset($_GET['off']) && $_GET['off'] != ""){
        $chushi = false; 
        $where['off'] = (int)$_GET['off'];
    }

    if(isset($_GET['orderid']) && $_GET['orderid'] != ""){
        $chushi = false; 
        $where['orderid'] = $_GET['orderid'];
    }
    if(isset($_GET['noticeid']) && $_GET['noticeid'] != ""){
        $chushi = false; 
        $where['noticeid'] = (int)$_GET['noticeid'];
    }
    if(isset($_GET['productid']) && $_GET['productid'] != ""){
        $chushi = false; 
        $where['productid'] = (int)$_GET['productid'];
    }

    if(isset($_GET['atimestart']) && $_GET['atimestart'] != ""){
        $chushi = false; 
        $where['atime >'] =strtotime($_GET['atimestart']);
    }
    if(isset($_GET['atimeend']) && $_GET['atimeend'] != ""){
        $chushi = false; 
        $where['atime <'] =strtotime($_GET['atimeend']);
    }
    
    $data = $db ->where($where)->limit($limit)->order('id desc')->select();
    $total = $db ->where($where)-> total();
    if(!$data){
        $data = [];
    }
    $kuozhan = [ 'count'=> (int)$total ];

    if($page <=1 && $chushi){
        $kuozhan = ['count'=> (int)$total];
        $kuozhan['type'] = $features['configure']['支付方式'];
        $time = time() - 3600*24*$TJNUM;
        $db ->where(['off'=>3,'atime <'=>$time]) ->delete();
    }

    return echoapptoken($data,0,'',$SESSIONtoken,$kuozhan );

}else if($kongzhi == 'put'){

    $id = (int)(isset($_POST['id'])?$_POST['id']:0);
    $data = $db ->where(['id' => $id])->find();
    if(!$data){
        return echoapptoken([],-1,'id不存在',$SESSIONtoken);
    }

    $fan = $THIS -> NOTICE_POST($data['id'],$features);
    
    return echoapptoken([],1,'异步通知',$SESSIONtoken);


}else if($kongzhi == 'add'){
    
}else if($kongzhi == 'del'){

}

return echoapptoken([],1,'ok',$SESSIONtoken);