<?php
require_once 'query.class.php';
class frontuser extends commonUsequery
{
public function ranusernumber()
{
	$digits_needed=8;

	$random_number=''; // set up a blank string

	$count=0;

	while ( $count < $digits_needed ) {
	$random_digit = mt_rand(0, 9);

	$random_number .= $random_digit;
	$count++;
	}
	 $ccnumber= $this->QueryRowFieldSelect('dri_users_temp', " where user_id='$random_number'",' user_id ');
	 if($ccnumber>0)
	 {
	 $this->ranusernumber();
	 }
	 else
	 {
	 return $random_number;
	 }
}
public function StopSqlInjection($str)
{	
		$messval=strip_tags($str);
		$messval=str_replace('/','-',$messval);	
		$messval=preg_replace('/[^a-zA-Z0-9._,@ d&-]/s', '', $messval);
		$messval=str_replace('\t','',$messval);	
		$messval=str_replace('alert','',$messval);		
		$messval=str_replace(array('&amp;','&lt;','&gt;','&'), array('&amp;amp;','&amp;lt;','&amp;gt;',''), $messval);
		$messval=str_replace('"','',$messval);		
		$messval=htmlspecialchars(mysql_real_escape_string($messval));	
		return $messval;
}

 
   public function classfrontcmsUser($cat_url,$type=0)
	{
       $collectionlist=array();
	   if($cat_url)
	   {
	    $cmsval=" where bot_campaigns_id='".$cat_url."' and bot_campaigns_status=1 and bot_campaigns_expire>='".DB_DATE_FORMAT."' and bot_campaigns_assign_date<='".DB_DATE_FORMAT."' ";
           
	    $collectionlist= $this->QueryFieldSelect(TABLE_PREFIX.'bot_campaigns',$cmsval,' * ');
            return $collectionlist;
             }
	}
        
     public function classfrontcmsUrl($cat_url)
	{
	   if($cat_url)
	   {
	   $cmsval=" where bot_url_parent='".$cat_url."' and bot_url_status=1 ";
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
	
}
	
?>