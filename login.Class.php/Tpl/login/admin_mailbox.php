<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$shiba = false;
$plusfunction = 'admin_mailbox';
$hash = 'safetoken/'.$_SESSION['adminid'];
$SESSIONtokenx = $SESSIONtoken = $ELiMem ->g($hash);
if( trim($CANSHU['0']) != $SESSIONtokenx){
    $shiba = true;
}
$SESSIONtoken =  (uuid());
$ELiMem ->s($hash,$SESSIONtoken);
if( $shiba ){
    return echoapptoken([],-1,"安全验证失败",$SESSIONtoken);
}

$kongzhi = isset($CANSHU['1'])?$CANSHU['1']:'get';
$db = db('login_mailbox');
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

    if(isset($_GET['uid']) && $_GET['uid'] != ""){
        $chushi = false; 
        $where['uid'] = (int)$_GET['uid'];
    }

    if(isset($_GET['name']) && $_GET['name'] != ""){
        $chushi = false; 
        $where['mailbox'] = $_GET['name'];
    }

    $data = $db ->where($where)->limit($limit)->order('id desc')->select();
    $total = $db ->where($where)-> total();
    if(!$data){
        $data = [];
    }
    $kuozhan = [ 'count'=> (int)$total ];

    if($page <=1 && $chushi){
        $kuozhan = ['count'=> (int)$total];
    }

    return echoapptoken($data,0,'',$SESSIONtoken,$kuozhan );

}else if($kongzhi == 'put'){

    $id = (int)(isset($_POST['id'])?$_POST['id']:0);
    $data = $db ->where(['id' => $id])->find();
    if(!$data){
        return echoapptoken([],-1,'id不存在',$SESSIONtoken);
    }

    if(isset( $_POST['mailbox'])){
        unset( $_POST['mailbox'] );
    }
    
    unset($_POST['id']);
  
    if($_POST['password'] != ""){
        $_POST['password'] = ELimm($_POST['password'] );
    }else{
        unset($_POST['password']);
    }
  
    $_POST['xtime'] = time();
    $_POST['adminid'] = $_SESSION['adminid'];
  
    $fan = $db ->where(['id' => $id])->update($_POST);
    if($fan){
    
        ELilog('adminlog',$_SESSION['adminid'],3,[ 'yuan'=>$data,'data'=> $_POST],$plusfunction);
        return echoapptoken($_POST,1,'修改成功',$SESSIONtoken);
    }else{
        return echoapptoken([],-1,'修改失败',$SESSIONtoken);
    }

}else if($kongzhi == 'add'){


    if(strlen($_POST['mailbox']) < 4){
        return echoapptoken([],-1,'邮箱格式错误',$SESSIONtoken);
    }

    if($_POST['uid'] < 1){
        return echoapptoken([],-1,'用户UID错误',$SESSIONtoken);
    }

    $fan = $db ->where(['mailbox' => $_POST['mailbox'] ])->find();
    if($fan){
        return echoapptoken([],-1,'邮箱已存在',$SESSIONtoken);
    }

  

    if($_POST['password'] != ""){
        $_POST['password'] = ELimm($_POST['password'] );
    }else{
        unset($_POST['password']);
    }
    
    
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