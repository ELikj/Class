<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$shiba = false;
$plusfunction = 'admin_weixin';
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
$db = db('login_weixin');
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

    if(isset($_GET['unionid']) && $_GET['unionid'] != ""){
        $chushi = false; 
        $where['unionid'] = $_GET['unionid'];
    }
    if(isset($_GET['openid']) && $_GET['openid'] != ""){
        $chushi = false; 
        $where['openid'] = $_GET['openid'];
    }

    if(isset($_GET['openidx']) && $_GET['openidx'] != ""){
        $chushi = false; 
        $where['openidx'] = $_GET['openidx'];
    }

    if(isset($_GET['openidg']) && $_GET['openidg'] != ""){
        $chushi = false; 
        $where['openidg'] = $_GET['openidg'];
    }

    if(isset($_GET['openido']) && $_GET['openido'] != ""){
        $chushi = false; 
        $where['openido'] = $_GET['openido'];
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

    $POST = [];
    if( isset($_POST['uid']) && $data['uid'] != $_POST['uid']){
        $POST['uid'] = $_POST['uid'];
    }

    if( isset($_POST['off']) && $data['off'] != $_POST['off']){
        $POST['off'] = $_POST['off'];
    }


    if(!$POST){
        return echoapptoken([],1,'ok',$SESSIONtoken);
    }

  
    $fan = $db ->where(['id' => $id])->update($POST);
    if($fan){
    
        ELilog('adminlog',$_SESSION['adminid'],3,[ 'yuan'=>$data,'data'=> $POST],$plusfunction);
        return echoapptoken($POST,1,'修改成功',$SESSIONtoken);
    }else{
        return echoapptoken([],-1,'修改失败',$SESSIONtoken);
    }

}else if($kongzhi == 'add'){


    return echoapptoken([],-1,'禁止新增',$SESSIONtoken);

    if($_POST['uid'] < 1){
        return echoapptoken([],-1,'用户UID错误',$SESSIONtoken);
    }

    $fan = $db ->where(['weixin' => $_POST['weixin'] ])->find();
    if($fan){
        return echoapptoken([],-1,'手机已存在',$SESSIONtoken);
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