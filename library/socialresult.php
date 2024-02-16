<?php
    function instaprofile($instaprofile,$selectedrangearr='',$arrayuser=array(),$topfirst=array(),$topsecond=array(),$catid=''){
    

    foreach($instaprofile as $result) {
        $dddm = 0;

        if(in_array(3000,$selectedrangearr)){
        if($result['followers_count']>1000000){
        $dddm = 1;
        }
        }
        if(in_array(2000,$selectedrangearr)){
        if($result['followers_count']<=1000000 && $result['followers_count']>10000){
        $dddm = 1;
        }
        }
        if(in_array(1000,$selectedrangearr)){
        if($result['followers_count']<=10000){
        $dddm = 1;
        }
        }
        
    if($dddm==1 || empty($selectedrangearr)){ 

        

        if($result['full_name'] || $result['insta_user_name'] ){
             $categoryv = empty($catid)? $result['insta_category']: $catid.','.$result['insta_category'];
        $livedta = array();
        $livedta =array("sc"=>'instagram',
        "id"=>$result['_id']
        ,"name"=>empty($result['full_name']) ? $result['insta_user_name'] : $result['full_name'] ,
        "desc"=> substr($result['biography'],0,100),
        "followers_count"=>empty($result['followers_count']) ? 0 : $result['followers_count'],
        "post"=>$result['posts_count'],
        "following"=>$result['following_count'],
        "image"=>$result['etc1'],
        "verified"=>$result['is_verified'],
        "category"=>$categoryv,
        "eng"=>$result['insta_eng'],
        "gender"=>$result['gender'],

        );
        $hastv = explode(" ",$hast);
        $second = 0;
        if(count($hastv)>1){
        if (strpos($result['full_name'], $hastv[0]) !== false && strpos($result['full_name'], $hastv[1]) !== false) {
        $second = 1;
        }}
        if($second==0){
        if (startsWith(strtolower($result['full_name']), strtolower($hastv[0]))) {
        $second = 1;
        }
        }

        if(strtolower($hast)==strtolower($result['full_name']) && $result['followers_count']>0){
        $topfirst[] = $livedta;
        }else if($second ==1 && $result['followers_count']>0){
        $topsecond[] = $livedta;
        }else{
        $arrayuser[] = $livedta;
        }
        }
    }
    
}
return array($topfirst,$topsecond,$arrayuser);
}

function twiterprofile($twtprofile,$selectedrangearr='',$arrayuser=array(),$topfirst=array(),$topsecond=array(),$catid=''){

    foreach($twtprofile as $result) {

           $dddm = 0;

                if(in_array(3000,$selectedrangearr)){
                if($result['followers_count']>1000000){
                    $dddm = 1;
                }
                }
                if(in_array(2000,$selectedrangearr)){
                if($result['followers_count']<=1000000 && $result['followers_count']>10000){
                    $dddm = 1;
                }
                }
                if(in_array(1000,$selectedrangearr)){
                if($result['followers_count']<=10000){
                    $dddm = 1;
                }
                }
               
               if($dddm==1 || empty($selectedrangearr)){
               $categoryv = empty($catid)? $result['twt_category']: $catid.','.$result['twt_category'];
            $livedta =array("sc"=>'twitter'
            ,"id"=>$result['_id'],"name"=>$result['name'],
            "desc"=> substr($result['description'],0,100),
            "followers_count"=>empty($result['followers_count']) ? 0 :$result['followers_count']  ,
            "post"=>$result['statuses_count'],
            "following"=>$result['friends_count'],
            "image"=>$result['profile_image_url'],
            "category"=>$categoryv,
            "eng"=>$result['twt_eng'],


            );


            $hastv = explode(" ",$hast);
                $second = 0;
                if(count($hastv)>1){
                if (strpos($result['name'], $hastv[0]) !== false && strpos($result['name'], $hastv[1]) !== false) {
                    $second = 1;
                }}
                if($second==0){
                    if (startsWith(strtolower($result['name']), strtolower($hastv[0]))) {
                    $second = 1;
                }
                }

                if(strtolower($hast)==strtolower($result['name']) && $result['followers_count']>0){
                     $topfirst[] = $livedta;
                    }else if($second ==1 && $result['followers_count']>0){
                     $topsecond[] = $livedta;
                    }else{
                        $arrayuser[] = $livedta;
                    }
                }


        }
        return array($topfirst,$topsecond,$arrayuser);
}
function youtubeprofile($ytbprofile,$selectedrangearr='',$arrayuser=array(),$topfirst=array(),$topsecond=array(),$catid=''){
    foreach($ytbprofile as $result) {
            $dddm = 0;

                if(in_array(3000,$selectedrangearr)){
                if($result['subscriberCount']>1000000){
                    $dddm = 1;
                }
                }
                if(in_array(2000,$selectedrangearr)){
                if($result['subscriberCount']<=1000000 && $result['subscriberCount']>10000){
                    $dddm = 1;
                }
                }
                if(in_array(1000,$selectedrangearr)){
                if($result['subscriberCount']<=10000){
                    $dddm = 1;
                }
                }
               
               if($dddm==1 || empty($selectedrangearr)){
                $categoryv = empty($catid)? $result['ytb_category']: $catid.','.$result['ytb_category'];

            $livedta =array("sc"=>'youtube'
            ,"id"=>$result['_id'],"name"=>$result['name'],
            "desc"=> substr($result['description'],0,100),
            "followers_count"=>empty($result['subscriberCount']) ? 0 :$result['subscriberCount'],
            "post"=>$result['videoCount'],
            "following"=>$result['viewCount'],
            "image"=>$result['thumbnails'],
            "category"=>$categoryv,
            "eng"=>$result['ytb_eng'],

            );

            $hastv = explode(" ",$hast);
                $second = 0;
                if(count($hastv)>1){
                if (strpos($result['name'], $hastv[0]) !== false && strpos($result['name'], $hastv[1]) !== false) {
                    $second = 1;
                }}
                if($second==0){
                    if (startsWith(strtolower($result['name']), strtolower($hastv[0]))) {
                    $second = 1;
                }
                }

                if(strtolower($hast)==strtolower($result['name']) && $result['subscriberCount']>0){
                     $topfirst[] = $livedta;
                    }else if($second ==1 && $result['subscriberCount']>0){
                     $topsecond[] = $livedta;
                    }else{
                        $arrayuser[] = $livedta;
                    }
                }
        }
        return array($topfirst,$topsecond,$arrayuser);
}


function location_parent($commonback,$mem,$memkey=0){

    $parent_location = $mem->get("parent_location");
    if ($parent_location && $memkey==0) {
        $locationlist=$parent_location;
    }else{
    $locationSelect='location_id,location_name,location_currency,location_url';
    $locationtable=TABLE_PREFIX."location ";
    $locationwhere=" where location_parent=0 and location_currency!='' and location_status=1 order by location_order DESC";
    $locationlist= $commonback->QueryFieldMultipleSelect($locationtable, $locationwhere,$locationSelect);
    $mem->set("parent_location",$locationlist);
    }
    return $locationlist;
}
function catgeory_parent($commonback,$mem,$cacheclrae=0){
    $parent_category = $mem->get("parent_categoryNKL");

if ($parent_category && $cacheclrae==0) {
    $productlist=$parent_category;
    
}else{
$productSelect='category_id,category_parent,category_name, category_description, category_url, category_image, category_thumbnail, category_status, category_create_date, category_update_date, category_keyword, category_meta, category_meta_description';
$producttable=TABLE_PREFIX."category ";
$productwhere=" where category_status=1 and category_parent=0 ORDER BY category_id DESC";
$productlist= $commonback->QueryFieldMultipleSelect($producttable, $productwhere,$productSelect);
$mem->set("parent_categoryNKL",$productlist);
}
return $parent_category;
}

function catgeory_parentAll($commonback,$mem){
    $parent_category = $mem->get("parent_categoryall");
    $parent_categoryaa = '';

if ($parent_categoryaa) {
    $productlist=$parent_category;
    
}else{
$productSelect='category_id,category_parent,category_name, category_description, category_url, category_image, category_thumbnail, category_status, category_create_date, category_update_date, category_keyword, category_meta, category_meta_description';
$producttable=TABLE_PREFIX."category ";
$productwhere=" where category_parent=0 ORDER BY category_id DESC";
$productlist= $commonback->QueryFieldMultipleSelect($producttable, $productwhere,$productSelect);
$finalcat = array();
foreach($productlist as $totallIst){
  $finalcat[$totallIst['category_id']] = $totallIst;
}
$mem->set("parent_categoryall",$finalcat);
}
return $parent_category;
}

function rangefilter($rangearrtop){
$rangestr = 0;
$rangend = 0;
if(in_array(1000,$rangearrtop)){
    $rangestr = 1;
    $rangend = 10000;
}
if(in_array(2000,$rangearrtop)){
    $rangestr = ($rangestr==0)? 10000 : $rangestr;
    $rangend = 100000;
}
if(in_array(3000,$rangearrtop)){
    $rangestr = ($rangestr==0)? 100000 : $rangestr;
    $rangend = 500000;
}
if(in_array(4000,$rangearrtop)){
    $rangestr = ($rangestr==0)? 500000 : $rangestr;
    $rangend = 1000000;
}
if(in_array(5000,$rangearrtop)){
    $rangestr = ($rangestr==0)? 1000000 : $rangestr;
    $rangend = 5000000;
}
if(in_array(6000,$rangearrtop)){
    $rangestr = ($rangestr==0)? 5000000 : $rangestr;
    $rangend = 10000000;
}
if(in_array(7000,$rangearrtop)){
    $rangestr = ($rangestr==0)? 10000000 : $rangestr;
    $rangend = 10000000000000000;
}
return array($rangestr,$rangend);
}

?>