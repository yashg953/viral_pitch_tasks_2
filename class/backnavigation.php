<?php 
class commonUsequery
{

	public function Queryinsert($tablename,$tableset,$message)
	{
	   $sql   = "INSERT INTO ".$tablename. " set ". $tableset;
		$result = dbQuery($sql);
		 $id=dbInsertId();
			try
			{
				if($result!=1)
				{
				  throw new Exception($message);
				}
				return $id;
				}
			
			catch(Exception $e)
			{
			 echo 'Message: ' .$e->getMessage();
			}
			
	}
	
	public function Queryupdate($tablename,$tableset,$message,$wherecond)
	{
    $sql   = "update ".$tablename. " set ". $tableset.$wherecond;
		$result = dbQuery($sql);
		
			try
			{
				if($result!=1)
				{
				  throw new Exception($message);
				}
				return true;
				}
			
			catch(Exception $e)
			{
			 echo 'Message: ' .$e->getMessage();
			}
			
	}
	
	public function QueryDelete($tablename,$message,$wherecond)
	{
	   $sql   = "Delete from ".$tablename. $wherecond;
	  
		$result = dbQuery($sql);
		
			try
			{
				if($result!=1)
				{
				  throw new Exception($message);
				}
				return true;
				}
			
			catch(Exception $e)
			{
			 echo 'Message: ' .$e->getMessage();
			}
			
	}
	public function QueryNameSelect($tablename,$wherecond,$nameselect)
	{
	  $sql   = "select ".$nameselect." from ".$tablename.$wherecond;
		$result = dbQuery($sql);
		$row = dbFetchAssoc($result);
		 $Name=$row[$nameselect];
			try
			{
				if($Name=='')
				{
				  throw new Exception('');
				}
				return $Name;
				}
			
			catch(Exception $e)
			{
			 echo  $e->getMessage();
			}
			
	}
	
public function QueryUrlSelect($tablename,$nameselect,$url)
	{
	  $sql   = "select ".$nameselect." from ".$tablename." where ".$nameselect."='".$url."'";
		$result = dbQuery($sql);
		
			
				if(dbNumRows($result)>0 || (!is_numeric($url)))
				{
				 $correcturl=$this->urlCheck($tablename,$nameselect);
				}
				else
				{
				 $correcturl=$url;
				}
				return $correcturl;
	}
public function urlCheck($tablename,$columnname)
	{
		$sql2vendurl = "SELECT max(".$columnname.") As Murl
				FROM ".$tablename;
		$result2vendurl = dbQuery($sql2vendurl) or die('Cannot get Product. ' . mysql_error());
		$rowurl=dbFetchAssoc($result2vendurl);
		$urlnewval=$rowurl[Murl]+1;
		return $urlnewval;
	}
	
public function QueryUrlproductIndex($Brandid,$columnvalue)
	{
	  $brandcheck=0;
	 $startdate=$this->stratdate();
	 
	  $sql   = "select ".$columnvalue." from ".TABLE_PRI."brand where brand_active=1 and  brand_id=".$Brandid;
		$result = dbQuery($sql);
		
		
		$row=dbFetchAssoc($result);
		extract($row);
		
			$sqlend="SELECt ec_days FROM ".TABLE_PRI."ending WHERE ec_id=1";
			$resultupend=dbQuery($sqlend);
			$rowend=dbFetchAssoc($resultupend);
			$upenddd=$rowend['ec_days'];
	
			$sqlupcomin="SELECt uc_days FROM ".TABLE_PRI."upcoming WHERE uc_id=1";
			$resultupcoming=dbQuery($sqlupcomin);
			$rowupcoming=dbFetchAssoc($resultupcoming);
			$upcomindd=$rowupcoming['uc_days'];
			$upcomingdateval=$this->upcomingdate($upcomindd);
		
			if($brand_start_date<=$startdate && $brand_end_date>=$startdate)
			{ 
				if($brand_end_date<=$upenddd)
				{
				  $brandcheck=1;
				}else
				{
				  $brandcheck=1;
				}
			}else if($brand_start_date>$startdate && $brand_start_date <=$upcomingdateval)
			{
			  $brandcheck=1;
			}
	return $brandcheck;
	
	}

public function stratdate(){
	
		 $datev=DB_DATE_FORMAT;
		$datenew=explode(" ",$datev);
		
		$dday=$datev['mday'];
		
		$mday=$datev['mon'];
		
		if(strlen($mday)==1){
		
		$mday="0".$mday;
		
		}
		
		if(strlen($dday)==1){
		
		$dday="0".$dday;
		
		}
		$startdate=$datenew[0];
        return $startdate;
 }
		
private function upcomingdate($updaye){
	$dddatefor=date("Y-m-d");
	$date = strtotime(date("Y-m-d", strtotime($dddatefor)) . " +".$updaye." day");
	$startdate=date('Y-m-d', $date);
	return $startdate;

}

	
public function QueryFieldSelect($tablename,$wherecond,$nameselect)
	{
     $sql   = "select ".$nameselect." from ".$tablename.$wherecond;
		$result = dbQuery($sql);
		$row = dbFetchAssoc($result);
		return 	$row ;
	}	
	
public function QueryProductImage($tablename,$wherecond)
	{
  $sql   = "select co_id, co_qty, co_image_1, co_image_2, co_image_3, co_image_4, co_image_5 from ".$tablename.$wherecond;
		$result = dbQuery($sql);
		$row = dbFetchAssoc($result);
		
		 if($row['co_image_1']!='')
				{
				$pdimage=$row['co_image_1'];
				}else if($row['co_image_2']!='')
				{
				$pdimage=$row['co_image_2'];
				}else if($row['co_image_3']!='')
				{
				$pdimage=$row['co_image_3'];
				}else if($row['co_image_4']!='')
				{
				$pdimage=$row['co_image_4'];
				}else if($row['co_image_5']!='')
				{
				$pdimage=$row['co_image_5'];
				}else
				{
				$pdimage='';
				}
				
				$proimage[] = array('co_id'   =>$row['co_id'],
						'co_qty'=>$row['co_qty'],
		               'pdimage'  => $pdimage
					);
					
		return 	$proimage ;
	}	
	
	
public function QueryFieldMultipleSelect($tablename,$wherecond,$nameselect)
	{
	$rt_row=array();
    $sql   = "select ".$nameselect." from ".$tablename.$wherecond;
		$result = dbQuery($sql);
		while($row = dbFetchAssoc($result))
		{
		$rt_row[]=$row;
		}
		return 	$rt_row ;
	}	
	

}


?>