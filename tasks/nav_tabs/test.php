<?php 
 require_once '../../library/config_dyn.php';
 require_once '../../class/query.class.php';
 require_once '../../class/backlogin.php';
 ini_set('display_errors', 0);
 $commonback = new backenduser();
 
 $table='user_remarks';
 $where='';
 $result=$commonback->QueryFieldMultipleSelect($table,$where,'*');
 echo "<pre>";
 print_r($result);
 echo "<pre>";


?>