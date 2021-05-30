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

$db = db('cms_content'); //cms_type
$where  = [];
if($kongzhi == 'get'){
  
    $leixing =  (int)(isset($CANSHU['2'])?$CANSHU['2']:'0');
    if($leixing > 0){
        $where['subclass IN'] =   $THIS ->TYPE_BAOHAN($db ,$leixing) ;
        $db  ->setbiao('cms_content');
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
        $where['off'] = (int)$_GET['type'];
    }
    if(isset($_GET['uid']) && $_GET['uid'] != ""){
        $chushi = false; 
        $where['uid'] = (int)$_GET['uid'];
    }

    if(isset($_GET['name']) && $_GET['name'] != ""){
        $chushi = false; 
        $where['('] = "and";
        $where['name LIKE'] = "%".$_GET['name']."%";
        $where['keywords OLK'] = "%".$_GET['name']."%";
        $where['describes OLK'] = "%".$_GET['name']."%";
        $where['remarks OLK'] = "%".$_GET['name']."%";
        $where[')'] = "";
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
        $kuozhan['ALLTYPE'] = $THIS-> ADMIN_TYPELIST(0);
        array_unshift($kuozhan['ALLTYPE'],[
            'title' =>"全部分类",
            'id'=>0,
        ]);

        $kuozhan['内容模版'] = [
            ''=>'默认内容neirog'
        ];

        if( isset($features['configure']['内容模版'])){
            foreach($features['configure']['内容模版'] as $vv){
                $kuozhan['内容模版'][$vv] = $vv;

            }
        }



    }

    return echoapptoken($data,0,'',$SESSIONtoken,$kuozhan );



}else if($kongzhi == 'put'){

    $_POST['name'] = ELixss($_POST['name']??"");
    if($_POST['name'] == ""){
        return echoapptoken([],-1,'请输入内容名称',$SESSIONtoken);
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
            $_POST['url'] = ($features['configure']['默认前缀']['1']?? "" ).$id;
            
            $fanx = $THIS -> ADMIN_FINDURL( $_POST['url'] );
            if(!$fanx){
                $_POST['url']  = 'content_'.$id;
            }
        }
    }
    if($_SESSION['groups'] > 0){
        $_POST['adminuid'] = $_SESSION['adminid'];
    }

    $_POST['keywords']= implode(',',$_POST['keywords']);
    if($_POST['subclass'] > 0){
        $kuozan = $THIS->ADMIN_expansionform( (int)$_POST['subclass']);
        if($kuozan != ""){
            $POSTexpansion = explode(',',$kuozan);
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

        }else{
            $_POST['expansion'] ="";
        }
    }



    $fanhui = $db ->where(['id' => $id]) ->update($_POST);
    if($fanhui){
        ELilog('adminlog',$_SESSION['adminid'],3,[ 'yuan'=>$data,'data'=> $_POST],'admin_content');
    }
    $fenlei = (int)$_POST['subclass'];
    if($fenlei > 0){
        $KUOFENLEI = $THIS-> TYPE_find($db,['id'=>$fenlei]);
        
        if($KUOFENLEI){
            if( $KUOFENLEI['Expand'] && $KUOFENLEI['Expand'] != ""){
                $chanfende = explode(',',$KUOFENLEI['Expand']);
                if($chanfende){
                    $uuuid = uuid();
                    ELihhSet("Expand",$uuuid);
                    foreach($chanfende as $expand){
                        $expand = trim($expand);
                        if($expand != ""){
                            $expand_ = explode('_',$expand);
                            if( count( $expand_ ) > 1){
                                $class_ = $expand_['0'];
                                unset($expand_['0']);
                                $expandPOST = [];
                                foreach($_POST as $kkk => $vvv){
                                    $jieguo = explode($class_.'_',$kkk);
                                    if (count($jieguo) > 1) {
                                        unset($jieguo['0']);
                                        $expandPOST[implode('_',$jieguo)] = $vvv;
                                    }
                                }
                                $expandPOST['id'] =$id;
                                $expandPOST['safetoken'] = $uuuid;
                                callELi($class_,implode('_',$expand_).'_PUT',$expandPOST,[],false);
                            }
                        }
                    }
                }
            }
        }
    }
    

}else if($kongzhi == 'add'){

    $_POST['atime'] = $_POST['xtime'] = time();
    $_POST['name'] = ELixss($_POST['name']??"");
    if($_POST['name'] == ""){
        return echoapptoken([],-1,'请输入分类名称',$SESSIONtoken);
    }
    $_POST['keywords']= implode(',',$_POST['keywords']);

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

    if($_POST['subclass'] > 0){
        $kuozan = $THIS->ADMIN_expansionform( (int)$_POST['subclass']);
        if($kuozan != ""){
            $POSTexpansion = explode(',',$kuozan);
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

        }else{
            $_POST['expansion'] ="";
        }
    }
   
    
    
    
    $id = $fanjie = $db ->insert($_POST);
    if(  $fanjie  && $gengurl ){

        $_POST['url'] = ($features['configure']['默认前缀']['1']?? "" ).$fanjie;
        $fanx = $THIS -> ADMIN_FINDURL( $_POST['url'] );
        if(!$fanx){
            $_POST['url']  = 'content_'.$fanjie;
        }
        $db ->where(['id'=>$fanjie ])->update( ['url' => $_POST['url'] ]);
        ELilog('adminlog',$_SESSION['adminid'],4,$_POST,'admin_content');

    }

    $fenlei = (int)$_POST['subclass'];
    if($fenlei > 0 && $id){
        $KUOFENLEI = $THIS-> TYPE_find($db,['id'=>$fenlei]);
        
        if($KUOFENLEI){
            if( $KUOFENLEI['Expand'] && $KUOFENLEI['Expand'] != ""){
                $chanfende = explode(',',$KUOFENLEI['Expand']);
                if($chanfende){
                    $uuuid = uuid();
                    ELihhSet("Expand",$uuuid);
                    foreach($chanfende as $expand){
                        $expand = trim($expand);
                        if($expand != ""){
                            $expand_ = explode('_',$expand);
                            if( count( $expand_ ) > 1){
                                $class_ = $expand_['0'];
                                unset($expand_['0']);
                                $expandPOST = [];
                                foreach($_POST as $kkk => $vvv){
                                    $jieguo = explode($class_.'_',$kkk);
                                    if (count($jieguo) > 1) {
                                        unset($jieguo['0']);
                                        $expandPOST[implode('_',$jieguo)] = $vvv;
                                    }
                                }
                                $expandPOST['id'] = $id;
                                $expandPOST['safetoken'] = $uuuid;
                                callELi($class_,implode('_',$expand_).'_ADD',$expandPOST,[],false);
                            }
                        }
                    }
                }
            }
        }
    }


}else if($kongzhi == 'del'){


    if(isset($_POST['expansion'])){

        $FANHUI = [];

        $fenlei  = (int)$_POST['expansion'];
        $FANHUI['expansion'] = $THIS->ADMIN_expansionform( $fenlei  );

        $id = (int)$_POST['Expandid'];
        $FANHUI['Expand'] = [];
        if($fenlei > 0 ){
            $KUOFENLEI = $THIS-> TYPE_find($db,['id'=>$fenlei]);
            if($KUOFENLEI){
                if( $KUOFENLEI['Expand'] && $KUOFENLEI['Expand'] != ""){
                    $chanfende = explode(',',$KUOFENLEI['Expand']);
                    if($chanfende){
                        $uuuid = uuid();
                        ELihhSet("Expand",$uuuid);
                        foreach($chanfende as $expand){
                            $expand = trim($expand);
                            if($expand != ""){
                                $expand_ = explode('_',$expand);
                                if( count( $expand_ ) > 1){
                                    $class_ = $expand_['0'];
                                    unset($expand_['0']);
                                    $expandPOST = [];
                                    $expandPOST['id'] = $id;
                                    $expandPOST['safetoken'] = $uuuid;
                                    $FANHUI['Expand'][$expand] = callELi($class_,implode('_',$expand_).'_GET',$expandPOST,[],false)??[];
                                    if(!is_array($FANHUI['Expand'][$expand])){
                                        $FANHUI['Expand'][$expand] = [];  
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        ob_clean();
        $GLOBALS['isend'] = false;
        return echoapptoken($FANHUI,1,'ok',$SESSIONtoken);
    }

    $id = (int)(isset($_POST['id'])?$_POST['id']:0);
    $data = $db ->where(['id' => $id])->find();
    if(!$data){
        return echoapptoken([],-1,'id不存在',$SESSIONtoken);
    }

    $fan = $db ->where(['id' => $id])->delete();
    if($fan){
        $fenlei = $data['subclass'];
        if($fenlei > 0 && $id){
            $KUOFENLEI = $THIS-> TYPE_find($db,['id'=>$fenlei]);
            
            if($KUOFENLEI){
                if( $KUOFENLEI['Expand'] && $KUOFENLEI['Expand'] != ""){
                    $chanfende = explode(',',$KUOFENLEI['Expand']);
                    if($chanfende){
                        $uuuid = uuid();
                        ELihhSet("Expand",$uuuid);
                        foreach($chanfende as $expand){
                            $expand = trim($expand);
                            if($expand != ""){
                                $expand_ = explode('_',$expand);
                                if( count( $expand_ ) > 1){
                                    $class_ = $expand_['0'];
                                    unset($expand_['0']);
                                    $expandPOST = [];
                                    $expandPOST['id'] = $id;
                                    $expandPOST['safetoken'] = $uuuid;
                                    callELi($class_,implode('_',$expand_).'_DEL',$expandPOST,[],false);
                                }
                            }
                        }
                      
                    }
                }
            }
        }

        ob_clean();
        $GLOBALS['isend'] = false;
        ELilog('adminlog',$_SESSION['adminid'],5,$data,'admin_content');
        return echoapptoken([],1,'删除成功',$SESSIONtoken);
    }else{
        return echoapptoken([],-1,'删除失败',$SESSIONtoken);
    }
   
}

ob_clean();
$GLOBALS['isend'] = false;
return echoapptoken([],1,'ok',$SESSIONtoken);