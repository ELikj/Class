<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$shiba = false;
$plusfunction = 'admin_notice';
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
    
    if(isset($_GET['orderid']) && $_GET['orderid'] != ""){
        $chushi = false; 
        $where['orderid'] = (int)$_GET['orderid'];
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
        
    }

    return echoapptoken($data,0,'',$SESSIONtoken,$kuozhan );

}else if($kongzhi == 'put'){

    $id = (int)(isset($_POST['id'])?$_POST['id']:0);
    $data = $db ->where(['id' => $id])->find();
    if(!$data){
        return echoapptoken([],-1,'id不存在',$SESSIONtoken);
    }
    
    $POST = ['orderid' => 0];
    $POST['adminid'] = $_SESSION['adminid'];
    if($data['orderid'] == '0' &&  $_POST['orderid'] > 0){

        $DB = db("payment_order");
        $order = $DB ->where([ 'id' => (int)$_POST['orderid'] ])->find();
        if(!$order){
            return echoapptoken([],-1,'订单不存在',$SESSIONtoken);
        }
        if( $order['off'] < 1 || $order['off'] == 2){
            return echoapptoken([],-1,'订单状态错误',$SESSIONtoken);
        }
        $xiangcha = $data['moeny'] - $order['moeny'];
        if($xiangcha < -1 || $xiangcha > 1){
            return echoapptoken([],-1,'金额相差较大'.$xiangcha,$SESSIONtoken);
        }
        $pan = $DB ->where(['id' => $order['id']]) ->update(['off'=>2,'ctime'=>time(),'paymoeny'=>$data['moeny'],'noticeid'=>$data['id']]);
        if(!$pan){
            return echoapptoken([],-1,'订单变更失败',$SESSIONtoken);
        }
        $POST['orderid'] =  $order['id'];
        $POST['remarks'] = $_POST['remarks'];
    }

    if($POST['orderid'] < 1){
        return echoapptoken([],-1,'修改失败',$SESSIONtoken);
    }
    
    $fan = $db ->where(['id' => $id])->update($POST);
    if($fan){

        ELilog('adminlog',$_SESSION['adminid'],3,[ 'yuan'=>$data,'data'=> $POST],$plusfunction);
        if($POST['orderid'] > 0){
            $THIS -> NOTICE_POST($POST['orderid'],$features);
        }
        return echoapptoken($_POST,1,'修改成功',$SESSIONtoken);
    }else{
        return echoapptoken([],-1,'修改失败',$SESSIONtoken);
    }

}else if($kongzhi == 'add'){
    return echoapptoken([],-1,'禁止新增',$SESSIONtoken);
    $_POST['atime'] = time();
    $_POST['adminid'] = $_SESSION['adminid'];
    $fan = $db ->insert($_POST);
    if($fan){

        ELilog('adminlog',$_SESSION['adminid'],4,$_POST,$plusfunction);
        return echoapptoken([],1,'新增成功',$SESSIONtoken);
    }else{
        return echoapptoken([],-1,'新增失败',$SESSIONtoken);
    }



}else if($kongzhi == 'del'){
    return echoapptoken([],-1,'禁止删除',$SESSIONtoken);
    $id = (int)(isset($_POST['id'])?$_POST['id']:0);
    $data = $db ->where(['id' => $id])->find();
    if(!$data){
        return echoapptoken([],-1,'id不存在',$SESSIONtoken);
    }
  
    $fan = $db ->where(['id' => $id])->delete();
    if($fan){
        
        ELilog('adminlog',$_SESSION['adminid'],5,$data,$plusfunction);
        return echoapptoken([],1,'删除成功',$SESSIONtoken);
    }else{
        return echoapptoken([],-1,'删除失败',$SESSIONtoken);
    }

}
return echoapptoken([],1,'ok',$SESSIONtoken);