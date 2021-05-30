<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$shiba = false;
$plusfunction = 'admin_code';
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

    if(isset($_GET['moeny']) && $_GET['moeny'] != ""){
        $chushi = false; 
        $where['moeny'] = (double)$_GET['moeny'];
    }
    if(isset($_GET['orderid']) && $_GET['orderid'] != ""){
        $chushi = false; 
        $where['orderid'] = (int)$_GET['orderid'];
    }
    if(isset($_GET['off']) && $_GET['off'] != ""){
        $chushi = false; 
        if($_GET['off'] > 0){
            $where['orderid >'] = 0;
        }else{
            $where['orderid'] = 0;
        }
    }

    $data =  $db ->where($where)->limit($limit)->order('id desc')->select();
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
    if($data['orderid'] > 0){
        return echoapptoken([],-1,'已匹配无法修改',$SESSIONtoken);
    }
    unset($_POST['id']);
    unset($_POST['atime']);
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

    if(isset($_POST['pldel']) && $_POST['pldel']!= ""){

        $type = (int)($_POST['type']??0);
        $codeimg = $_POST['codeimg']??"";
        $remarks = $_POST['remarks']??"";
        $num = (int)($_POST['num']??10);
        $moeny = $_POST['moeny']??"";

        if($moeny == ""){
            return echoapptoken([],-1,'金额范围错误',$SESSIONtoken);
        }
        //每个金额的数量
        if($num < 1 || $num >= 100){
            return echoapptoken([],-1,'金额码数格式[1-100]',$SESSIONtoken);
        }
        //每次增加
        $paymoeny = (double)($_POST['paymoeny']??0.01);

        if (strstr($moeny , '-') !== false ) {
            list($d,$v) = explode("-",$moeny );
            $d= (double) $d;
            $v= (double) $v;
            $x = $d;
            $y = $v;
            if($y < $x){
                $x = $v;
                $y = $d; 
            }
            $jiange = (int)($_POST['jiange']??1);
            if($jiange < 1){
                return echoapptoken([],-1,'金额间隔小于1',$SESSIONtoken);
            }

            if($y/$jiange > 100){
                return echoapptoken([],-1,'生成规则过多',$SESSIONtoken);
            }
            for($i = $x;$i<=$y; $i++ ){
                if($i%$jiange == 0 || $i == $x ){
                    for($j=0;$j< $num;$j++){

                        $shuju =[
                            'type'=>$type,
                            'codeimg'=>$codeimg,
                            'remarks'=>$remarks,
                            'moeny'=>$i,
                            'paymoeny'=>$i+$j*$paymoeny
                        ];
                        $db -> insert($shuju);
                    }
                }
            }

        }else{

            $jine = explode(",",$moeny);
            foreach($jine  as $jine_){
                $jine_ = (double)$jine_;
                for($j=0;$j< $num;$j++){
                    $shuju =[
                        'type'=>$type,
                        'codeimg'=>$codeimg,
                        'remarks'=>$remarks,
                        'moeny'=>$jine_,
                        'paymoeny'=>$jine_+$j*$paymoeny
                    ];
                    $db -> insert($shuju);
                }
            }

        }
        
        //moeny
        //paymoeny

        ELilog('adminlog',$_SESSION['adminid'],4,$_POST,$plusfunction);

        return echoapptoken([],1,'批量生成',$SESSIONtoken);
    }

   
    $_POST['atime'] = 0;
    $_POST['adminid'] = $_SESSION['adminid'];
    $fan = $db ->insert($_POST);
    if($fan){
        ELilog('adminlog',$_SESSION['adminid'],4,$_POST,$plusfunction);
        return echoapptoken([],1,'新增成功',$SESSIONtoken);
    }else{
        return echoapptoken([],-1,'新增失败,存在已有匹配金额',$SESSIONtoken);
    }



}else if($kongzhi == 'del'){

    
    $id = (int)(isset($_POST['id'])?$_POST['id']:0);
    $data = $db ->where(['id' => $id])->find();
    if(!$data){
        return echoapptoken([],-1,'id不存在',$SESSIONtoken);
    }
    if($data['orderid'] > 0){
        return echoapptoken([],-1,'已匹配无法删除',$SESSIONtoken);
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