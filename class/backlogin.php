<?php

require_once 'query.class.php';

class backenduser
        extends commonUsequery {
            function updatepassword($userName,$password){

                $password = md5($password);

                $usertypeval = $this->Queryupdate(TABLE_PREFIX."user_master ", " admin_password='".$password."'" ,
                'Catogry Not Update', " where admin_emailid='" . $userName . "' and admin_status=1 and  (admin_password='' OR admin_password IS NULL )");
            }
            function passwordCheck($userName) {
               
                 $admin_Nav = $this->QueryFieldSelect(TABLE_PREFIX."user_master ",
                " where admin_emailid='" . $userName . "' and admin_status=1 and  ( admin_password='' OR admin_password IS NULL )",
                ' admin_id, admin_password');

    
              if (count($admin_Nav) > 1) {
                
                $n=1;
              }else{
                 $n=2;
              }
              return $n;
            }
     function doLogincheck($userName) {
        $admin_Nav = $this->QueryFieldSelect(TABLE_PREFIX."user_master ",
                " where admin_emailid='" . $userName . "' and admin_status=1 ",
                ' admin_id, admin_password');
        if (count($admin_Nav) > 1) {

          if($admin_Nav['admin_password']==''){

            $n=12;

          }else{
             $n=1;
          }    
          
        } else {
            $n = 4;
        }

        return $n;
    }
    function randomnum(){
        $digits_needed=4;

    $random_number=''; // set up a blank string

    $count=0;

    while ($count < $digits_needed ) {
        $random_digit = mt_rand(0, 9);
        
        $random_number .= $random_digit;
        $count++;
     }
     return $random_number;
     }
   
    function doLogincheckotp($userName,$checkno,$otpsend) {
        $admin_Nav = $this->QueryFieldSelect(TABLE_PREFIX."location ",
                " where location_mobile='" . $userName . "'  ",
                ' location_id, location_mobile,location_type');
        if (count($admin_Nav) > 1) {
            if($checkno==1){
                $otpnum = $this->randomnum();

                $n = $admin_Nav['location_mobile'];
                $_SESSION['OTP_ADMIN'] =$otpnum.$n;

                otpsend($n,$otpnum);
                $n = 1;
            }else{
                //echo $otpsend.$userName . '---'.$_SESSION['OTP_ADMIN'];
                if($otpsend.$userName==$_SESSION['OTP_ADMIN']){
                
                        $_SESSION['plaincart_user_id'] = $admin_Nav['location_id'];
                        $_SESSION['dealer_user_id'] = $admin_Nav['location_id'];
                        $_SESSION['user_type_other'] = $admin_Nav['location_mobile'];
                        $_SESSION['previewval'] = 0;
                        $_SESSION['dealer'] = 1;
                        $_SESSION['admin_type'] = $admin_Nav['location_type'];
                        if($_SESSION['appuser']==1){
                        $admin_app = $this->QueryFieldSelect(TABLE_PREFIX."contact ",
                        " where contact_support_status=0 ",
                        ' * ');                    
                        $_SESSION['contact_mobile']= $admin_app['contact_mobile'];
                        }

                        $n = 12;                
                }else {

                       $n = 4;
                    }
            }

        } else {
            $n = 4;
        }

        return $n;
    }     

    function doLogin($userName, $password) {
        $admin_Nav = $this->QueryFieldSelect(TABLE_PREFIX."user_master ",
                " where admin_emailid='" . $userName . "' and admin_status=1 ",
                ' admin_id, admin_password');
        if (count($admin_Nav) > 1) {

            if ($admin_Nav['admin_password'] == md5($password)) {
                $_SESSION['plaincart_user_id'] = $admin_Nav['admin_id'];
                $_SESSION['user_type_other'] = $admin_Nav['admin_user_type'];
                $_SESSION['previewval'] = 0;
                $n = 11;
            } else {

                $n = 4;
            }
        } else {
            $n = 4;
        }

        return $n;
    }

    public function checkUser($logout=0) {
        if (!isset($_SESSION['plaincart_user_id']) || !is_numeric($_SESSION['plaincart_user_id'])) {
            header('Location: ' . DOMINOS_PATH_NAME );
            exit;
        }

        if ($logout===1) {
            $this->doLogout();
        }
    }
    public function checkUserInfu($logout=0) {
        if (!isset($_SESSION['influencer_user']) || !is_numeric($_SESSION['influencer_user'])) {
            header('Location: ' . INFLUNCER_PATH . 'login.php');
            exit;
        }else{
            if (empty($_SESSION['influencer_varified']) || $_SESSION['influencer_varified'] ==1) {
                $adfgh ='';
                if($_SESSION['infu_type']==2){
                $adfgh = 'influencer/';
                }
                header('Location: ' . INFLUNCER_PATH . $adfgh.'verify.php');
            exit;
            }

        }

        if ($logout===1) {
            $this->doLogout();
        }
    }
 

    public function usernavigation($navid = 0) {
        $naigatiosite = 1;
        if (is_numeric($navid)) {
            $valSelect = 'admin_access_nav';
            $selwhere = " WHERE admin_id = '" . $_SESSION['plaincart_user_id'] . "'";

            $selectlist = $this->QueryFieldSelect('deal_user_master', $selwhere,
                    $valSelect);
            $selectlist['admin_access_nav'];
            $main_nav = explode(",", $selectlist['admin_access_nav']);
            if ($navid > 0) {
                if ($selectlist['admin_access_nav'] != '' && (in_array($navid,
                                $main_nav))) {
                    $naigatiosite = $this->validation_check($selectlist['admin_access_nav'],
                            $_SESSION['plaincart_user_id']);
                } else {
                    $naigatiosite = 1;
                }
            } else {
                if ($selectlist['admin_access_nav'] != '') {
                    $naigatiosite = $this->validation_check($selectlist['admin_access_nav'],
                            $_SESSION['plaincart_user_id']);
                } else {
                    $naigatiosite = 1;
                }
            }
        }
        return $naigatiosite;
    }

    private function validation_check($admin_inavlist11, $admin_id) {
        $admin_inavlist = explode(",", $admin_inavlist11);
        $navicountval = count($admin_inavlist);
        $resval = $this->navigationList11($admin_inavlist);
        return $resval;
    }

    private function navigationList11($admin_inavlist) {
        $navigationList11val = $this->QueryFieldMultipleSelect("deal_navigation ",
                " where nav_parent=0 and nav_status=1 ORDER BY nav_order , nav_name",
                "nav_id,nav_name, nav_parent, nav_url, nav_status");

        $productsearchcount = count($navigationList11val);

        for ($m = 0; $m < $productsearchcount; $m++) {
            if (in_array($navigationList11val[$m]['nav_id'], $admin_inavlist) && ($navigationList11val[$m]['nav_id']
                    != 80 && $navigationList11val[$m]['nav_id'] != 79)) {
                $navmess = '';
                $navigationList11 = $this->QueryFieldMultipleSelect("deal_navigation ",
                        " where nav_status=1 and nav_parent=" . $navigationList11val[$m]['nav_id'] . " ORDER BY nav_order,nav_name",
                        'nav_id,nav_name,nav_url');
                $navigationsearchcount = count($navigationList11);
                if ($navigationsearchcount > 0) {
                    $navarrow = 'class="has-sub"';
                }

                $navmess .= '<li ' . $navarrow . '><a href="' . SUPER_DOMINOS_PATH_NAME . $navigationList11val[$m]['nav_url'] . '?navid=' . $navigationList11val[$m]['nav_id'] . '" ><span>' . $navigationList11val[$m]['nav_name'] . '</span></a>';
                $varible = "i" . $navigationList11val[$m]['nav_id'];
                $message .= $this->navigationName11($navigationList11val[$m]['nav_id'],
                        $navmess, $varible, $admin_inavlist, $navigationList11);
            }
        }
        return $message;
    }

    private function navigationName11($nav_id, $navmessage, $var,
            $admin_inavlist, $navigationList11, $countval) {

        $navigationusercount = count($navigationList11);

        if ($navigationusercount > 0) {
            $message = $navmessage . '<ul>';
            $m = 0;
            $mmkt = 0;
            for ($var = 0; $var < $navigationusercount; $var++) {

                if (in_array($navigationList11[$var]['nav_id'], $admin_inavlist)) {
                    $navigationList11val = $this->QueryFieldMultipleSelect("deal_navigation ",
                            " where nav_status=1 and nav_parent=" . $navigationList11[$var]['nav_id'] . " ORDER BY   nav_order ,nav_name ",
                            'nav_id,nav_parent,nav_name,nav_url');
                    $navigationsearchvalcount = count($navigationList11val);

                    if ($navigationsearchvalcount > 0) {
                        $navarrowsub = 'class="has-sub-sub"';
                    }

                    $message .= '<li ' . $navarrowsub . '"><a href="' . SUPER_DOMINOS_PATH_NAME . $navigationList11[$var]['nav_url'] . '=' . $navigationList11[$var]['nav_id'] . '" ><span>' . $navigationList11[$var]['nav_name'] . '</span></a>';
                    $varible = "i" . $navigationList11[$var]['nav_id'];
                    if ($navigationsearchvalcount > 0) {
                        $message = $this->navigationName11($navigationList11[$var]['nav_id'],
                                $message, $varible, $admin_inavlist,
                                $navigationList11val, $navigationsearchvalcount);
                    }
                    $mmkt++;
                }
                if ($navigationList11[$var]['nav_parent'] == 10) {
                    if ($countval == ($mmkt)) {
                        $message .= '<li ' . $navarrowsub . '"><a href="' . SUPER_DOMINOS_PATH_NAME . $navigationList11[$var]['nav_url'] . '=' . $navigationList11[$var]['nav_parent'] . '&allval=12" ><span>All</span></a></li>';
                    }
                }
            }
            $message .= '</ul></li>';
        } else {
            $message = $navmessage . '</li>';
        }
        return $message;
    }

    private function doLogout() {
        if (isset($_SESSION['plaincart_user_id'])) {
            unset($_SESSION['plaincart_user_id']);
            if ($_SESSION['previewval']) {
                $_SESSION['userId'] = 0;
            }
            unset($_SESSION['previewval']);
            unset($_SESSION['previewval']);
            unset($_SESSION['dealer_user_id']);
            unset($_SESSION['OTP_ADMIN']);
            unset($_SESSION['admin_type']);
        }
        
        header('Location: ' . DOMINOS_PATH_NAME );
        exit;
    }

    public function validation_check_nav_val($nav_id, $admin_id) {

        $valSelect = 'acces_useraction';
        $selwhere = " where acces_user=" . $admin_id . " and acces_navgation=" . $nav_id;
        $selectlist = $this->QueryMultipleSelect('deal_user_acces', $selwhere,
                $valSelect);

        return $selectlist;
    }

    function StopSqlInjection($str) {
         return $str;
    }

    public function ImageDelete($uploaddir, $tablename, $columnname, $wherecond,
            $imagesize) {

        $selectlist = $this->QueryNameSelect($tablename, $wherecond, $columnname);

        for ($i = 0; $i < count($imagesize); $i++) {

            $deleteDir = $uploaddir . $imagesize[$i] . "/" . $selectlist;
            $deleted = @unlink($deleteDir);
        }
        $varquertCAT = $columnname . "= ' '";

        $usertypeval = $this->Queryupdate($tablename, $varquertCAT,
                'Catogry Not Update', $wherecond);
        try {
            if ($deleted != 1) {
                throw new Exception('Images Not Delete');
            }
            return true;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    public function MultiImageDelete($categoryimagesize, $wherecond) {
        for ($i = 0; $i < count($categoryimagesize); $i++) {
            $result1 = '';
            $uploadDir = WEB_IMAGES_PATH_FRONT . 'productcolor/' . $categoryimagesize[$i] . "/";
            $deleted = @unlink($uploaddir . $wherecond);
        }
    }

    public function oredrtype($hidpaymentid, $final_amount_point = 0,
            $final_amount_credit = 0, $final_amount) {
        $coupons = '';
        $credits = '';
        $points = '';
        $crepipe = '';
        $poipipe = '';
        $discipe = '';
        if ($final_amount_point != 0 || $final_amount_credit != 0 || $final_amount
                != 0) {

            if ($final_amount != 0) {
                $coupons = 'Coupon';
            }
            if ($final_amount_credit != 0) {
                $credits = 'Credits';
            }
            if ($final_amount_point != 0) {
                $points = 'Points';
            }
            if ($final_amount != 0 && $final_amount_credit != 0 && $final_amount_point
                    == 0) {
                $crepipe = '|';
                $points = '';
            }
            if ($final_amount_credit != 0 && $final_amount_point != 0 && $final_amount
                    == 0) {
                $poipipe = '|';
                $coupons = '';
            }
            if ($final_amount != 0 && $final_amount_point != 0 && $final_amount_credit
                    == 0) {
                $discipe = '|';
                $credits = '';
            }
            if ($final_amount != 0 && $final_amount_point != 0 && $final_amount_credit
                    != 0) {
                $crepipe = '|';
                $poipipe = '|';
                $discipe = '';
            }
            $paytypemode1 = $coupons . $crepipe . $credits . $poipipe . $discipe . $points;
        }


        if ($hidpaymentid == 'CPA') {
            if ($paytypemode1 != '') {
                $paytypemode12 = '|' . $paytypemode1;
            }
            $paytypemode = 'Cheque Payment/Bank Transfer ' . $paytypemode12;
            $paytypemodeto = 'Cheque Payment/Bank Transfer ';
        }
        if ($hidpaymentid == 'COD') {
            if ($paytypemode1 != '') {
                $paytypemode12 = '|' . $paytypemode1;
            }
            $paytypemode = 'Cash On Delivery ' . $paytypemode12;
            $paytypemodeto = 'Cash On Delivery ';
        }
        if ($hidpaymentid == 'Pay Online') {
            if ($paytypemode1 != '') {
                $paytypemode12 = '|' . $paytypemode1;
            }
            $paytypemode = 'Online Payment ' . $paytypemode12;
            $paytypemodeto = 'Online Payment ';
        }
        if ($hidpaymentid == 'Pay Online1') {
            if ($paytypemode1 != '') {
                $paytypemode12 = '|' . $paytypemode1;
            }
            $paytypemode = 'Online Payment ' . $paytypemode12;
            $paytypemodeto = 'Online Payment ';
        }
        if ($hidpaymentid == 'Zero') {
            $paytypemode = $paytypemode1;
        }
        return $paytypemode;
    }
    
    public function classfrontcmsUser($cat_url,$type=0)
    {
       $collectionlist=array();
       if($cat_url)
       {
        $cmsval=" where bot_user_key='".$cat_url."' ";
            if($type==1)
            {
               $cmsval.= " and bot_user_assign=0";
            }
      
        $collectionlist= $this->QueryFieldSelect(TABLE_PREFIX.'bot_user',$cmsval,' * ');
            return $collectionlist;
             }
    }
        
     public function classfrontcmsUrl($cat_url=0,$listurl=0)
    {
       if($cat_url)
       {
       $cmsval=" where bot_url_parent='".$cat_url."' ";
       }
           if($listurl)
       {
       $cmsval=" where bot_url_id='".$listurl."' ";
       }
        $collectionlist= $this->QueryFieldMultipleSelect(TABLE_PREFIX.'bot_url',$cmsval,' * ');
            return $collectionlist;
    }  
        
        public function classfrontcmsBounce($cat_url)
    {
            $collectionlist=array();
       if($cat_url)
       {
       $cmsval=" where bounce_user_parent='".$cat_url."' ";
       
        $collectionlist= $this->QueryFieldMultipleSelect(TABLE_PREFIX.'bounce_user',$cmsval,' * ');
            }
            return $collectionlist;
    }
        
        public function classCampaigns($cat_url, $WithArray = 0)
    {
            $campaignSelect='category_id,category_name';
            $campaigntable=TABLE_PREFIX."category ";
            $campaignwhere=" where category_status=1 ORDER BY category_name Asc";
            $campaignlist= $this->QueryFieldMultipleSelect($campaigntable, $campaignwhere,$campaignSelect);
            $returnData='';
            $listArray=array();
            foreach($campaignlist as $rowList) {
                $dselect='';
                if($rowList['category_id']===$cat_url){ $dselect="selected='selected'";}
             $returnData.='<option value="'.$rowList['category_id'].'" '.$dselect.'>'.$rowList['category_name'].'</option>';
             $listArray[$rowList['category_id']] = $rowList['category_name'];
             $selectArray[] = array($rowList['category_id'],$rowList['category_name']);
             
            }
            if ($WithArray == 1) {
            $returnData = array("select" => $returnData, 'list' => $listArray);
            }
            if ($WithArray == 2) {
            $returnData = array("select" => $returnData, 'list' => $selectArray);
            }
              
                        
            return $returnData;
    }
        
    public function classStore($cat_url, $WithArray = 0) {
        $listArray = array();
        $campaignSelect = 'source_id,source_name';
        $campaigntable = TABLE_PREFIX . "source ";
        $campaignwhere = " where source_status=1 ORDER BY source_name ASC";
        $campaignlist = $this->QueryFieldMultipleSelect($campaigntable,
                $campaignwhere, $campaignSelect);
        $returnData = '';
        $listArray=array();
        foreach ($campaignlist as $rowList) {
            $dselect = '';
            if ($rowList['source_id'] === $cat_url) {
                $dselect = "selected='selected'";
            }
            $returnData .= '<option value="' . $rowList['source_id'] . '" ' . $dselect . '>' . $rowList['source_name'] . '</option>';
            $listArray[$rowList['source_id']] = $rowList['source_name'];
        }
        if ($WithArray == 1) {
            $returnData = array("select" => $returnData, 'list' => $listArray);
        }

        return $returnData;
    }

    public function categoryName(){


        $catSelect='category_id,category_name';
        $cattable=TABLE_PREFIX."category ";
        $catwhere=" where category_status=1 and category_parent=0 ORDER BY category_id DESC";
        $catlist= $this->QueryFieldMultipleSelect($cattable, $catwhere,$catSelect);
        $catArray = array();
        foreach($catlist as $rowct){
            $catArray[$rowct['category_id']] = $rowct['category_name'];
        }
        return $catArray;
    }

    public function locationName(){    
        $cntrySelect='location_id,location_name';
        $cntrytable=TABLE_PREFIX."location ";
        $cntrywhere="  where location_status=1 and location_parent= 0   ORDER BY location_id DESC";
        $cntrylist= $this->QueryFieldMultipleSelect($cntrytable, $cntrywhere,$cntrySelect);
        $cntryArray = array();
        foreach($cntrylist as $rowcnt){

          $cntryArray[$rowcnt['location_id']] = $rowcnt['location_name'];

        }
        return $cntryArray;
    }

        public function send_email($htmlBody, $subject, $name = null, $mail) {

        $url = 'https://api.sendgrid.com/';
        $user = 'gkttyagi';
        $pass = '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';
        //$mail = 'cvishal587@gmail.com';
        //$mail = 'gktphp@gmail.com';
        //$mail = 'amanslathia19@gmail.com';
    
        $params = array(
            'to' => $mail,
            'subject' => $subject,
            'html' => $htmlBody,
            'text' => '',
            'from' => 'contact@viralpitch.co',
            'fromname'  => "Viral pitch ",
        );

        $headr = array();
        // set authorization header
        $headr[] = 'Authorization: Bearer '.$pass;



        $request = $url . 'api/mail.send.json';

        // Generate curl request
        $session = curl_init($request);

        // Tell curl to use HTTP POST
        curl_setopt($session, CURLOPT_POST, true);

        // Tell curl that this is the body of the POST
        curl_setopt($session, CURLOPT_POSTFIELDS, $params);

        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        // Tell PHP not to use SSLv3 (instead opting for TLS)
        curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_HTTPHEADER,$headr);

        // obtain response
        $response = curl_exec($session);
        curl_close($session);
        #print_r($response);

        return 1;
        }

 public function forgotBrand($usercode,$name,$brandimg=0){
    
$htmlrt = '<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:v="urn:schemas-microsoft-com:vml">

<head>

    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width" name="viewport" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <title></title>

</head>

<body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: transparent;">
    <table bgcolor="transparent" cellpadding="0" cellspacing="0" class="nl-container" role="presentation"
        style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: radial-gradient(#fff, #fff); width: 640px;"
        valign="top">
        <tbody>
            <tr style="vertical-align: top;" valign="top">
                <td style="word-break: break-word; vertical-align: top;" valign="top">
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                    <div style="width:100% !important;">
                                        <div
                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                            <div align="center" class="img-container center autowidth"
                                                style="padding-right: 0px;padding-left: 0px;"><img align="center"
                                                    alt="Reset Password" border="0" class="center autowidth"
                                                    src="https://viralpitch.co/mailer/image4.jpg"
                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 60%; display: block;"
                                                    title="Reset Password" />
                                            </div>
                                            
                                            <div
                                                style="color:#555555;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:15px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                    <p
                                                        style="font-size: 30px; line-height: 1; text-align: center; word-break: break-word; mso-line-height-alt: 55px; margin: 0;">
                                                        <span style="font-size: 30px; color: #003188;"><strong>Forgot Password</strong></span></p>
                                                </div>
                                            </div>
                                            <div
                                                style="color:#555555;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.5; font-size: 12px; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; mso-line-height-alt: NaNpx; margin: 0;">
                                                        <span style="background-color: transparent;"><span
                                                                style="color: #6d89bc;"><span
                                                                    style="font-size: 16px;font-weight:700;">Hey '.$name.',</span></span></span></p>
                                                                    <p style="font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 21px; margin: 0;"> </p>
                                                    
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                        <span style="font-size: 16px; color: #6d89bc;">You recently requested to reset your password for your Viral Pitch account. Click the button below to reset it.</span></p>
                                                        <p style="font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 21px; margin: 0;"> </p>
                                                    
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; mso-line-height-alt: NaNpx; margin: 0;">
                                                        <span style="color: #6d89bc;"><span
                                                                style="font-size: 16px;"><span
                                                                    style="color: #6d89bc;">If you did not request a password reset, you can safely ignore this mail or reply to let us know. This request is only valid for the next 30 minutes. </span></span></span></p>
                                                    
                                                    
                                                    
                                                </div>
                                            </div>
                                            <div align="center" class="button-container"
                                                style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                <a href="'.$usercode.'"
                                                    style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#ec4c5c;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;width:auto; width:auto;border:1px solid #ec4c5c;padding-top:5px;padding-bottom:5px;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span
                                                        style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;"><span
                                                            style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Reset Password</span></span></a>
                                            </div>
                                            
                                            <div
                                                style="color:#555555;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.5; font-size: 12px; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                        <span
                                                            style="font-size: 16px; color: #6d89bc;font-weight:700;">Cheers,</span>
                                                    </p>
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                        <span
                                                            style="font-size: 16px; color: #6d89bc;font-weight:700;">Team
                                                            Viral Pitch</span></p>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                    <div style="width:100% !important;">
                                        <div
                                            style="border-top:1px solid #E5EAF3; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                            
                                            <div align="center" class="img-container center fixedwidth"
                                                style="padding-right: 20px;padding-left: 20px;">
                                                <div style="font-size:1px;line-height:20px"> </div><img align="center"
                                                    alt="Image" border="0" class="center fixedwidth"
                                                    src="https://viralpitch.co/mailer/image_1.png"
                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 192px; display: block;"
                                                    title="Image" width="192" />
                                                <div style="font-size:1px;line-height:15px"> </div>
                                            </div>
                                            <table cellpadding="0" cellspacing="0" class="social_icons"
                                                role="presentation"
                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                valign="top" width="100%">
                                                <tbody>
                                                    <tr style="vertical-align: top;" valign="top">
                                                        <td style="word-break: break-word; vertical-align: top; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;"
                                                            valign="top">
                                                            <table align="center" cellpadding="0" cellspacing="0"
                                                                class="social_table" role="presentation"
                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;"
                                                                valign="top">
                                                                <tbody>
                                                                    <tr align="center"
                                                                        style="vertical-align: top; display: inline-block; text-align: center;"
                                                                        valign="top">
                                                                        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 4px;"
                                                                            valign="top"><a
                                                                                href="https://www.facebook.com/Viral-Pitch-102810068030743/"
                                                                                target="_blank"><img alt="Facebook"
                                                                                    height="32"
                                                                                    src="https://viralpitch.co/mailer/facebook2x.png"
                                                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"
                                                                                    title="Facebook" width="32" /></a>
                                                                        </td>
                                                                        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 4px;"
                                                                            valign="top"><a
                                                                                href="https://www.instagram.com/viral.pitch/"
                                                                                target="_blank"><img alt="Instagram"
                                                                                    height="32"
                                                                                    src="https://viralpitch.co/mailer/instagram2x.png"
                                                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"
                                                                                    title="Instagram" width="32" /></a>
                                                                        </td>
                                                                        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 4px;"
                                                                            valign="top"><a
                                                                                href="https://www.linkedin.com/company/38158941"
                                                                                target="_blank"><img alt="LinkedIn"
                                                                                    height="32"
                                                                                    src="https://viralpitch.co/mailer/linkedin2x.png"
                                                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"
                                                                                    title="LinkedIn" width="32" /></a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                    <div style="width:100% !important;">
                                        <div
                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                            <div class="mobile_hide">
                                                <table border="0" cellpadding="0" cellspacing="0" class="divider"
                                                    role="presentation"
                                                    style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                    valign="top" width="100%">
                                                    <tbody>
                                                        <tr style="vertical-align: top;" valign="top">
                                                            <td class="divider_inner"
                                                                style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 30px; padding-right: 10px; padding-bottom: 0px; padding-left: 10px;"
                                                                valign="top">
                                                                <table align="center" border="0" cellpadding="0"
                                                                    cellspacing="0" class="divider_content"
                                                                    role="presentation"
                                                                    style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;"
                                                                    valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                                valign="top"><span></span></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>';
return $htmlrt;

 }       


 public function registerHtmlBrand($usercode,$name,$brandimg=0){
           
            $html = '<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:v="urn:schemas-microsoft-com:vml">

<head>

    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width" name="viewport" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <title></title>
    
</head>

<body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: transparent;">
    <table bgcolor="transparent" cellpadding="0" cellspacing="0" class="nl-container" role="presentation"
        style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: radial-gradient(#fff, #fff); width: 640px;"
        valign="top">
        <tbody>
            <tr style="vertical-align: top;" valign="top">
                <td style="word-break: break-word; vertical-align: top;" valign="top">
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                    <div style="width:100% !important;">
                                        <div
                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                            <div align="center" class="img-container center autowidth"
                                                style="padding-right: 0px;padding-left: 0px;"><img align="center"
                                                    alt="I&#39;m an image" border="0" class="center autowidth"
                                                    src="https://viralpitch.co/mailer/image3.png"
                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; display: block;"
                                                    title="I&#39;m an image" />
                                            </div>
                                            
                                            <div
                                                style="color:#555555;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:15px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                    <p
                                                        style="font-size: 30px; line-height: 1; text-align: center; word-break: break-word; mso-line-height-alt: 55px; margin: 0;">
                                                        <span style="font-size: 30px; color: #003188;"><strong>We&#39;re
                                                                happy to see you here.</strong></span></p>
                                                </div>
                                            </div>
                                            <div
                                                style="color:#555555;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.5; font-size: 12px; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; mso-line-height-alt: NaNpx; margin: 0;">
                                                        <span style="background-color: transparent;"><span
                                                                style="color: #6d89bc;"><span
                                                                    style="font-size: 16px;font-weight:700;">Hey
                                                                    '.$name.',</span></span></span></p>
                                                                    <p></p>
                                                                    <p></p>
                                                    
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                       <span style="font-size: 16px; color: #6d89bc;">We&#39;re glad to see
                                                            you become a part of the Viral Pitch family, home to over
                                                            300k+ spirited influencers and brands. We&#39;re at your service
                                                            to provide you with tons of resources, support
                                                            and everlasting love. :)</span></p>
                                                    <p></p>
                                                    <p></p>
                                                    
                                                    <p style="line-height: 1.5; word-break: break-word; font-family: inherit; mso-line-height-alt: NaNpx; margin: 0;">
                                                        <span style="color: #6d89bc;"><span
                                                                style="font-size: 16px;"><span
                                                                    style="color: #6d89bc;">Before we move on to the
                                                                    next step of bringing in the best influencers
                                                                    for your campaigns, we&#39;re going to need a small favor
                                                                    and click here </span><span
                                                                    style="color: #6d89bc;">to complete
                                                                    your </span></span></span><span
                                                            style="background-color: transparent; font-size: 16px;"><span
                                                                style="color: #6d89bc;">registration. </span></span></p>
                                                    <p></p>
                                                    
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                        <span
                                                            style="background-color: transparent; font-size: 16px;"><span
                                                                style="color: #6d89bc;">If you didn&#39;t register with
                                                                Viral Pitch, you can safely ignore this
                                                                email.</span></span></p>
                                                </div>
                                            </div>
                                            <div align="center" class="button-container"
                                                style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                <a href="'.$usercode.'"
                                                    style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#ec4c5c;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;width:auto; width:auto;border:1px solid #ec4c5c;padding-top:5px;padding-bottom:5px;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span
                                                        style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;"><span
                                                            style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Verify
                                                            My Email Address</span></span></a>
                                            </div>
                                            
                                            <div
                                                style="color:#555555;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.5; font-size: 12px; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                        <span
                                                            style="font-size: 16px; color: #6d89bc;font-weight:700;">Cheers,</span>
                                                    </p>
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                        <span
                                                            style="font-size: 16px; color: #6d89bc;font-weight:700;">Team
                                                            Viral Pitch</span></p>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                    <div style="width:100% !important;">
                                        <div
                                            style="border-top:1px solid #E5EAF3; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                            
                                            <div align="center" class="img-container center fixedwidth"
                                                style="padding-right: 20px;padding-left: 20px;">
                                                <div style="font-size:1px;line-height:20px"> </div><img align="center"
                                                    alt="Image" border="0" class="center fixedwidth"
                                                    src="https://viralpitch.co/mailer/image_1.png"
                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 192px; display: block;"
                                                    title="Image" width="192" />
                                                <div style="font-size:1px;line-height:15px"> </div>
                                            </div>
                                            <table cellpadding="0" cellspacing="0" class="social_icons"
                                                role="presentation"
                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                valign="top" width="100%">
                                                <tbody>
                                                    <tr style="vertical-align: top;" valign="top">
                                                        <td style="word-break: break-word; vertical-align: top; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;"
                                                            valign="top">
                                                            <table align="center" cellpadding="0" cellspacing="0"
                                                                class="social_table" role="presentation"
                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;"
                                                                valign="top">
                                                                <tbody>
                                                                    <tr align="center"
                                                                        style="vertical-align: top; display: inline-block; text-align: center;"
                                                                        valign="top">
                                                                        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 4px;"
                                                                            valign="top"><a
                                                                                href="https://www.facebook.com/Viral-Pitch-102810068030743/"
                                                                                target="_blank"><img alt="Facebook"
                                                                                    height="32"
                                                                                    src="https://viralpitch.co/mailer/facebook2x.png"
                                                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"
                                                                                    title="Facebook" width="32" /></a>
                                                                        </td>
                                                                        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 4px;"
                                                                            valign="top"><a
                                                                                href="https://www.instagram.com/viral.pitch/"
                                                                                target="_blank"><img alt="Instagram"
                                                                                    height="32"
                                                                                    src="https://viralpitch.co/mailer/instagram2x.png"
                                                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"
                                                                                    title="Instagram" width="32" /></a>
                                                                        </td>
                                                                        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 4px;"
                                                                            valign="top"><a
                                                                                href="https://www.linkedin.com/company/38158941"
                                                                                target="_blank"><img alt="LinkedIn"
                                                                                    height="32"
                                                                                    src="https://viralpitch.co/mailer/linkedin2x.png"
                                                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"
                                                                                    title="LinkedIn" width="32" /></a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                    <div style="width:100% !important;">
                                        <div
                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                            <div style="min-height: 0px;max-height: 0px;max-width: 0px;display: none;overflow: hidden;font-size: 0px;">
                                                <table border="0" cellpadding="0" cellspacing="0" class="divider"
                                                    role="presentation"
                                                    style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                    valign="top" width="100%">
                                                    <tbody>
                                                        <tr style="vertical-align: top;" valign="top">
                                                            <td class="divider_inner"
                                                                style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 30px; padding-right: 10px; padding-bottom: 0px; padding-left: 10px;"
                                                                valign="top">
                                                                <table align="center" border="0" cellpadding="0"
                                                                    cellspacing="0" class="divider_content"
                                                                    role="presentation"
                                                                    style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;"
                                                                    valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                                valign="top"><span></span></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>';
return $html;

        }

        public function registerHtml($usercode,$name,$brandimg=0){
            $image ='image4.png';
            $content1 ='>We&#39;re glad to see you become a part of the Viral Pitch family, home to over 300k+ spirited influencers and brands. We&#39;re at your service to provide you with tons of resources, support and everlasting love. :)';
            if($brandimg==1){
             $image ='image3.png';
             $content1 = '';
            }
            $html = '<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:v="urn:schemas-microsoft-com:vml">

<head>

    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width" name="viewport" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <title></title>
    
</head>

<body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: transparent;">
    <table bgcolor="transparent" cellpadding="0" cellspacing="0" class="nl-container" role="presentation"
        style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: radial-gradient(#fff, #fff); width: 640px;"
        valign="top">
        <tbody>
            <tr style="vertical-align: top;" valign="top">
                <td style="word-break: break-word; vertical-align: top;" valign="top">
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                    <div style="width:100% !important;">
                                        <div
                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                            <div align="center" class="img-container center autowidth"
                                                style="padding-right: 0px;padding-left: 0px;"><img align="center"
                                                    alt="I&#39;m an image" border="0" class="center autowidth"
                                                    src="https://viralpitch.co/mailer/image4.png"
                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; display: block;"
                                                    title="I&#39;m an image" />
                                            </div>
                                            
                                            <div
                                                style="color:#555555;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:15px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                    <p
                                                        style="font-size: 30px; line-height: 1; text-align: center; word-break: break-word; mso-line-height-alt: 55px; margin: 0;">
                                                        <span style="font-size: 30px; color: #003188;"><strong>We&#39;re
                                                                happy to see you here.</strong></span></p>
                                                </div>
                                            </div>
                                            <div
                                                style="color:#555555;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.5; font-size: 12px; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; mso-line-height-alt: NaNpx; margin: 0;">
                                                        <span style="background-color: transparent;"><span
                                                                style="color: #6d89bc;"><span
                                                                    style="font-size: 16px;font-weight:700;">Hey
                                                                    '.$name.',</span></span></span></p>
                                                                    <p></p>
                                                                    <p></p>
                                                    
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                        <span style="font-size: 16px; color: #6d89bc;">We&#39;re glad to see
                                                            you become a part of the Viral Pitch family, home to over
                                                            300k+ spirited influencers and brands. We&#39;re at your service
                                                            to provide you with tons of resources, support
                                                            and everlasting love. :)</span></p>
                                                    
                                                    <p></p>
                                                    <p></p>
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; mso-line-height-alt: NaNpx; margin: 0;">
                                                        <span style="color: #6d89bc;"><span
                                                                style="font-size: 16px;"><span
                                                                    style="color: #6d89bc;">Before we move on to the
                                                                    next step of bringing in the best influencer
                                                                    campaigns for you, we&#39;re going to need a small favor
                                                                    and click here </span><span
                                                                    style="color: #6d89bc;">to complete
                                                                    your </span></span></span><span
                                                            style="background-color: transparent; font-size: 16px;"><span
                                                                style="color: #6d89bc;">registration. </span></span></p>
                                                    
                                                    <p></p>
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                        <span
                                                            style="background-color: transparent; font-size: 16px;"><span
                                                                style="color: #6d89bc;">If you didn&#39;t register with
                                                                Viral Pitch, you can safely ignore this
                                                                email.</span></span></p>
                                                                <p></p>
                                                </div>
                                            </div>
                                            <div align="center" class="button-container"
                                                style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                <a href="'.$usercode.'"
                                                    style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#ec4c5c;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;width:auto; width:auto;border:1px solid #ec4c5c;padding-top:5px;padding-bottom:5px;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span
                                                        style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;"><span
                                                            style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Verify
                                                            My Email Address</span></span></a>
                                            </div>
                                            
                                            <div
                                                style="color:#555555;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                <div
                                                    style="line-height: 1.5; font-size: 12px; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                        <span
                                                            style="font-size: 16px; color: #6d89bc;font-weight:700;">Cheers,</span>
                                                    </p>
                                                    <p
                                                        style="line-height: 1.5; word-break: break-word; font-family: inherit; font-size: 16px; mso-line-height-alt: 24px; margin: 0;">
                                                        <span
                                                            style="font-size: 16px; color: #6d89bc;font-weight:700;">Team
                                                            Viral Pitch</span></p>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                    <div style="width:100% !important;">
                                        <div
                                            style="border-top:1px solid #E5EAF3; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                            
                                            <div align="center" class="img-container center fixedwidth"
                                                style="padding-right: 20px;padding-left: 20px;">
                                                <div style="font-size:1px;line-height:20px"> </div><img align="center"
                                                    alt="Image" border="0" class="center fixedwidth"
                                                    src="https://viralpitch.co/mailer/image_1.png"
                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 192px; display: block;"
                                                    title="Image" width="192" />
                                                <div style="font-size:1px;line-height:15px"> </div>
                                            </div>
                                            <table cellpadding="0" cellspacing="0" class="social_icons"
                                                role="presentation"
                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                valign="top" width="100%">
                                                <tbody>
                                                    <tr style="vertical-align: top;" valign="top">
                                                        <td style="word-break: break-word; vertical-align: top; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;"
                                                            valign="top">
                                                            <table align="center" cellpadding="0" cellspacing="0"
                                                                class="social_table" role="presentation"
                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;"
                                                                valign="top">
                                                                <tbody>
                                                                    <tr align="center"
                                                                        style="vertical-align: top; display: inline-block; text-align: center;"
                                                                        valign="top">
                                                                        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 4px;"
                                                                            valign="top"><a
                                                                                href="https://www.facebook.com/Viral-Pitch-102810068030743/"
                                                                                target="_blank"><img alt="Facebook"
                                                                                    height="32"
                                                                                    src="https://viralpitch.co/mailer/facebook2x.png"
                                                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"
                                                                                    title="Facebook" width="32" /></a>
                                                                        </td>
                                                                        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 4px;"
                                                                            valign="top"><a
                                                                                href="https://www.instagram.com/viral.pitch/"
                                                                                target="_blank"><img alt="Instagram"
                                                                                    height="32"
                                                                                    src="https://viralpitch.co/mailer/instagram2x.png"
                                                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"
                                                                                    title="Instagram" width="32" /></a>
                                                                        </td>
                                                                        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 4px;"
                                                                            valign="top"><a
                                                                                href="https://www.linkedin.com/company/38158941"
                                                                                target="_blank"><img alt="LinkedIn"
                                                                                    height="32"
                                                                                    src="https://viralpitch.co/mailer/linkedin2x.png"
                                                                                    style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"
                                                                                    title="LinkedIn" width="32" /></a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="background-color:transparent;">
                        <div class="block-grid"
                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                            <div
                                style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                <div class="col num12"
                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                    <div style="width:100% !important;">
                                        <div
                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                            <div style="min-height: 0px;max-height: 0px;max-width: 0px;display: none;overflow: hidden;font-size: 0px;">
                                                <table border="0" cellpadding="0" cellspacing="0" class="divider"
                                                    role="presentation"
                                                    style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                    valign="top" width="100%">
                                                    <tbody>
                                                        <tr style="vertical-align: top;" valign="top">
                                                            <td class="divider_inner"
                                                                style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 30px; padding-right: 10px; padding-bottom: 0px; padding-left: 10px;"
                                                                valign="top">
                                                                <table align="center" border="0" cellpadding="0"
                                                                    cellspacing="0" class="divider_content"
                                                                    role="presentation"
                                                                    style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;"
                                                                    valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                                valign="top"><span></span></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>';
return $html;

        }

        public function enctp($token){


  $cipher_method = 'aes-128-ctr';
  $enc_key = openssl_digest(php_uname(), 'SHA256', TRUE);
  $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
  $crypted_token = openssl_encrypt($token, $cipher_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
  unset($token, $cipher_method, $enc_key, $enc_iv);
  $crypted_token = str_replace("=::","_",$crypted_token);
  $crypted_token = str_replace("=","-",$crypted_token);
  return  $crypted_token;
}
public function dectp($crypted_token){

    $crypted_token = str_replace("-","=",$crypted_token);
    $crypted_token = str_replace("_","=::",$crypted_token);
    list($crypted_token, $enc_iv) = explode("::", $crypted_token);;
    $cipher_method = 'aes-128-ctr';
    $enc_key = openssl_digest(php_uname(), 'SHA256', TRUE);
    $token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
    unset($crypted_token, $cipher_method, $enc_key, $enc_iv);
    return $token;
}
}
    

?>
