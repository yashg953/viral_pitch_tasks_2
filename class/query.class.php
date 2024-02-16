<?php 
abstract class commonUsequery
{
	
	var $host = 'localhost';
    var $user = 'root';
    var $pass = '';
    var $db = 'viral_pitch';
    var $myconn='';
	function connect() {	
        $con = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
        if (!$con) {
            #die('Could not connect to database!');
        } else {
            $this->myconn = $con;
            #echo 'Connection established!';
        }
        return $this->myconn;
    }

public function QueryinsertNew($tablename,$tableset,$message)
	{
	     $sql   = "INSERT INTO ".$tablename. " set ". $tableset;
	   $connectT = $this->connect();
		$result = $this->dbQuery($sql,$connectT);
		   $id=$this->dbInsertId($connectT);
	
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
			 #echo 'Message: ' .$e->getMessage();
			}
			
	}
		public function Queryinsert($tablename,$tableset,$message)
	{
	    $sql   = "INSERT INTO ".$tablename. " set ". $tableset;
		
	   $connectT = $this->connect();
		return $id = $this->dbQuery($sql,$connectT,1);
		   //$id=$this->dbInsertId($connectT);
	
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
			 #echo 'Message: ' .$e->getMessage();
			}
			
	}
		public function Queryupdatewww($tablename,$tableset,$message,$wherecond)
	{
      echo $sql   = "update ".$tablename. " set ". $tableset.$wherecond;
		$result = $this->dbQuery($sql,'');

		
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
	public function Queryupdate($tablename,$tableset,$message,$wherecond)
	{
      $sql   = "update ".$tablename. " set ". $tableset.$wherecond;
	//   echo $sql."<br>";

		$result = $this->dbQuery($sql,'');

		
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
	  
		$result = $this->dbQuery($sql,'');
		
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
		$result = $this->dbQuery($sql,'');
		$row = $this->dbFetchAssoc($result);
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
public function QuerysumNameSelect($tablename,$wherecond,$nameselect)
	{
	   $sql   = "select ".$nameselect." from ".$tablename.$wherecond;
		$result = $this->dbQuery($sql,'');
		$row = $this->dbFetchAssoc($result);
		$clname=explode("as", $nameselect);
		$clnamesel=$clname[1];
		 $Name=$row[$clnamesel];
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
		$result = $this->dbQuery($sql,'');
		
			
				if($this->dbNumRows($result)>0 || (!is_numeric($url)))
				{
				 $correcturl=$this->urlCheck($tablename,$nameselect);
				}
				else
				{
				 $correcturl=$url;
				}
				return $correcturl;
	}
public function urlCheck($tablename,$columnname,$wherecon)
	{
	   $urlnewval=1;
		$sql2vendurl = "SELECT ".$columnname."
				FROM ".$tablename.$wherecon;
		$result2vendurl = $this->dbQuery($sql2vendurl,'') or die('Cannot get Product. ' . mysqli_error());
		if($this->dbNumRows($result2vendurl)>0)
		{
		$urlnewval=0;
		}
		
		return $urlnewval;
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
		$result = $this->dbQuery($sql,'');
		$row = $this->dbFetchAssoc($result);
		return 	$row ;
	}

public function QueryRowFieldSelect($tablename,$wherecond,$nameselect)
	{
     $sql   = "select ".$nameselect." from ".$tablename.$wherecond;
		$result = $this->dbQuery($sql,'');
		$row = $this->dbNumRows($result);
		return 	$row ;
	}	
	
	
public function QueryProductImage($tablename,$wherecond)
	{
  $sql   = "select co_id, co_qty, co_image_1, co_image_2, co_image_3, co_image_4, co_image_5 from ".$tablename.$wherecond;
		$result = $this->dbQuery($sql,'');
		$row = $this->dbFetchAssoc($result);
		
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
	public function QueryFieldMultipleSelect12($tablename,$wherecond,$nameselect)
	{
	$rt_row=array();
     $sql   = "select ".$nameselect." from ".$tablename.$wherecond;

		$result = $this->dbQuery($sql,'');
		while($row = $this->dbFetchAssoc($result))
		{
		$rt_row[]=$row;
		}
		return 	$rt_row ;
	}
	
public function QueryFieldMultipleSelect($tablename,$wherecond,$nameselect)
	{
	$rt_row=array();
    $sql   = "select ".$nameselect." from ".$tablename.$wherecond;
			$result = $this->dbQuery($sql,'');
		while($row = $this->dbFetchAssoc($result))
		{
		$rt_row[]=$row;
		}
		return 	$rt_row ;
	}
public function QueryMultipleSelect($tablename,$wherecond,$nameselect)
	{
	$rt_row=array();
    $sql   = "select ".$nameselect." from ".$tablename.$wherecond; 
	
		$result = $this->dbQuery($sql,'');
		while($row = $this->dbFetchAssoc($result))
		{
		$rt_row[]=$row[$nameselect];
		}
		return 	$rt_row ;
	}
	
/*public function dbQuery($sql,$connectT)
{
	if(empty($connectT)){
		$connectT = $this->connect();
	}
	$result = mysqli_query($connectT,$sql);
	mysqli_close($connectT); 
	
	return $result;
}*/
public function dbQuery($sql,$connectT,$lsatInsertid =0)
{
	if(empty($connectT)){
		$connectT = $this->connect();
	}
	$result = mysqli_query($connectT,$sql);
	if($lsatInsertid==1){
		$result=$this->dbInsertId($connectT);
	}
	mysqli_close($connectT); 
	
	return $result;
}
public function dbAffectedRows()
{
	global $dbConn;	
	return mysqli_affected_rows($dbConn);
}

private function dbFetchArray($result, $resultType = MYSQL_NUM) {
	return mysqli_fetch_array($result, $resultType);
}

public function dbFetchAssoc($result)
{
	return mysqli_fetch_assoc($result);
}

private function dbFetchRow($result) 
{
	return mysqli_fetch_row($result);
}

private function dbFreeResult($result)
{
	return mysqli_free_result($result);
}

public function dbNumRows($result)
{
	return mysqli_num_rows($result);
}

private function dbSelect($dbName)
{
	return mysqli_select_db($dbName);
}

public function dbInsertId($connectT)
{
	
	return mysqli_insert_id($connectT);
}
}


?>