<?php if( !defined( 'ELikj')){ exit( 'Error ELikj'); }
$shiba = false;
$plusfunction = 'plist';
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
$db = db('cms_type');
$where  = [];
if($kongzhi == 'get'){
    $ID = (int)($_POST['id']??0);

    if($ID > 0){ //单个查询
        $data = $db ->where(['id' => $ID])->find();
        if(!$data){
            return echoapptoken([],-1,'id不存在',$SESSIONtoken);
        }

        $data['ALLTYPE'] = $THIS-> ADMIN_TYPELIST(0);

        array_unshift($data['ALLTYPE'],[
            'title' =>"顶级分类",
            'id'=>0,
        ]);
     
        return echoapptoken($data,1,'ok',$SESSIONtoken);

    }else if($ID == -1){
        $data = [];
        foreach($db ->tablejg['1'] as $k =>$v){
            $ttt = explode('_',$v);
            $data[$k] =  end($ttt); 
        }
       
        $data['ALLTYPE'] = $THIS-> ADMIN_TYPELIST(0);

        array_unshift($data['ALLTYPE'],[
            'title' =>"顶级分类",
            'id'=>0,
        ]);
        return echoapptoken($data,1,'ok',$SESSIONtoken);


    }else{

        $data = [
            'ALLTYPE' => ''
        ];
        $fan =  $THIS-> ADMIN_TYPELIST(0);
        if($fan){
            $data['ALLTYPE'] = $fan;
            array_unshift($data['ALLTYPE'],[
                'title' =>"创建分类",
                'id'=>-1,
            ]);
        

        }else{
            $data['ALLTYPE'] = [[
                'title' =>"创建分类",
                'id'=>-1,
            ]];
        }
        $data['列表模版'] = [
            ''=>'默认列表list'
        ];
        if( isset($features['configure']['列表模版'])){

            foreach($features['configure']['列表模版'] as $vv){
                $data['列表模版'][$vv] = $vv;

            }
        }

        $data['内容模版'] = [
            ''=>'默认内容neirog'
        ];

        if( isset($features['configure']['内容模版'])){
            foreach($features['configure']['内容模版'] as $vv){
                $data['内容模版'][$vv] = $vv;

            }
        }
      
        return echoapptoken($data,1,'ok',$SESSIONtoken);

    }




}else if($kongzhi == 'put'){

    $_POST['name'] = ELixss($_POST['name']??"");
    if($_POST['name'] == ""){
        return echoapptoken([],-1,'请输入分类名称',$SESSIONtoken);
    }
    $id = (int)(isset($_POST['id'])?$_POST['id']:0);
    $data = $db ->where(['id' => $id])->find();
    if(!$data){
        return echoapptoken([],-1,'id不存在',$SESSIONtoken);
    }
    $_POST['xtime'] = time();

    if($_POST['url'] != $data['url'] || $_POST['url'] == ""){

        if($data['url'] != ""){
            $db ->where(['id' => $id]) ->update(['url' =>'']);
        }
        $fanx = $THIS -> ADMIN_FINDURL($_POST['url']);
        if(!$fanx){
            $_POST['url'] = ($features['configure']['默认前缀']['0']?? "" ).$id;
            
            $fanx = $THIS -> ADMIN_FINDURL( $_POST['url'] );
            if(!$fanx){
                $_POST['url']  = 'type_'.$id;
            }
        }
    }

    if( $_POST['subclass'] != $data['subclass'] ){
        $fanhui =  $THIS ->ADMIN_FINDSUBCLASS($_POST['subclass'],$id);
        if(!$fanhui){
            $_POST['subclass'] = $data['subclass'];
        }
    }

    if($_POST['subclass'] == $id ){
        $_POST['subclass'] = 0;
    }

    if($_SESSION['groups'] > 0){
        $_POST['adminuid'] = $_SESSION['adminid'];
    }

    $_POST['keywords']= implode(',',$_POST['keywords']);
    $_POST['Expand']= implode(',',$_POST['Expand']??[]);
    
    $POSTexpansion = $_POST['expansionform']??[];
    $_POST['expansionform']= implode(',',$POSTexpansion);

    if($POSTexpansion){
        
        $_POSTexpansionform = [];
            foreach($POSTexpansion as $zzz){
                $hx =  explode('-',$zzz);
                $key = reset($hx);
                if(isset($_POST['expansion_'.$key])){
                    if(is_array($_POST['expansion_'.$key])){
                        $zicihuo = [];
                        foreach($_POST['expansion_'.$key] as $vvv){
                            if(is_array($vvv)){
                                $zicihuo[] =  implode('$',  $vvv );
                            }else{
                                $zicihuo[] = $vvv;
                            }
                        }
                        $_POSTexpansionform[] =implode(',',  $zicihuo );
                    }else{
                        $_POSTexpansionform[] = $_POST['expansion_'.$key];
                    }
                }
            }
        $_POST['expansion'] = implode('(_$$_)',$_POSTexpansionform);
    }
 
    $fanhui = $db ->where(['id' => $id]) ->update($_POST);
    if($fanhui){
        ELilog('adminlog',$_SESSION['adminid'],3,[ 'yuan'=>$data,'data'=> $_POST],'admin_type');
    }
    
}else if($kongzhi == 'add'){
    $_POST['atime'] = $_POST['xtime'] = time();
    $_POST['name'] = ELixss($_POST['name']??"");
    if($_POST['name'] == ""){
        return echoapptoken([],-1,'请输入分类名称',$SESSIONtoken);
    }
    $_POST['keywords']= implode(',',$_POST['keywords']);
    $_POST['Expand'] = implode(',',$_POST['Expand']??[]);
    
    $_POST['url'] = ELiSecurity($_POST['url']);
    $gengurl = false;
    if($_POST['url'] == "" ){
        $gengurl = true;
    }else {

        $fanx = $THIS -> ADMIN_FINDURL($_POST['url']);
        if(!$fanx){
            $gengurl = true;
        }
    }

    if($_SESSION['groups'] > 0){
        $_POST['adminuid'] = $_SESSION['adminid'];
    }
    $POSTexpansion = $_POST['expansionform']??[];
    $_POST['expansionform']= implode(',',$POSTexpansion);

    if($POSTexpansion){
        $_POSTexpansionform = [];
        $_POSTexpansionform = [];
            foreach($POSTexpansion as $zzz){
                $hx =  explode('-',$zzz);
                $key = reset($hx);
                if(isset($_POST['expansion_'.$key])){
                  
                    if(is_array($_POST['expansion_'.$key])){
                        $zicihuo = [];
                        foreach($_POST['expansion_'.$key] as $vvv){
                            if(is_array($vvv)){
                                $zicihuo[] =  implode('$',  $vvv );
                            }else{
                                $zicihuo[] = $vvv;

                            }
                        }

                        $_POSTexpansionform[] =implode(',',  $zicihuo );
                    }else{

                        $_POSTexpansionform[] = $_POST['expansion_'.$key];
                    }
                   
                }
            
            }
        $_POST['expansion'] = implode('(_$$_)',$_POSTexpansionform);
    }
   
    
    
    $fanjie = $db ->insert($_POST);
    if(  $fanjie  && $gengurl ){
        $_POST['url'] = ($features['configure']['默认前缀']['0']?? "" ).$fanjie;
        $fanx = $THIS -> ADMIN_FINDURL( $_POST['url'] );
        if(!$fanx){
            $_POST['url']  = 'type_'.$fanjie;
        }
        $db ->where(['id'=>$fanjie ])->update( ['url' => $_POST['url'] ]);
        ELilog('adminlog',$_SESSION['adminid'],4,$_POST,'admin_type');

    }


}else if($kongzhi == 'del'){





    $id = (int)(isset($_POST['id'])?$_POST['id']:0);

    if($id < 0){
        $ELiMem  ->  f("html/");
        return echoapptoken([],1,'ok',$SESSIONtoken);  
    }


    $data = $db ->where(['id' => $id])->find();
    if(!$data){
        return echoapptoken([],-1,'id不存在',$SESSIONtoken);
    }

    $fan = $db ->where(['id' => $id])->delete();
    if($fan){

        $THIS -> ADMIN_TYPEDEL( $id );
        ELilog('adminlog',$_SESSION['adminid'],5,$data,'admin_type');
        return echoapptoken([],1,'删除成功',$SESSIONtoken);
    }else{
        return echoapptoken([],-1,'删除失败',$SESSIONtoken);
    }

}

return echoapptoken([],1,'ok',$SESSIONtoken);