<?php 
//cumulative income $c
//new tranction $t
function tax_bracket($check,$npwp=1){
    if($check==1){
        if($npwp){
            $return=5;
        }else{
            $return=6;
        }
    }else if($check==2){
        if($npwp){
            $return=15;
        }else{
            $return=18;
        }

    }else if($check==3){
        if($npwp){
            $return=25;
        }else{
            $return=30;
        }

    }else if($check==4){
        if($npwp){
            $return=30;
        }else{
            $return=36;
        }
    
    }
    return $return;
}

function check_bracket($check,$npwp=1){
    $return=array();
    if($check>=0&&$check<=60000000){
        if($npwp==0){
            $return['bracket']=1;
            $return['percent']=6;
        }else if($npwp==1){
            $return['bracket']=1;
            $return['percent']=5;
        }    
    }else if($check>60000000&&$check<= 250000000){
        if($npwp==0){
            $return['bracket']=2;
            $return['percent']=18;
        }else if($npwp==1){
            $return['bracket']=2;
            $return['percent']=15;  
        }
    }else if($check>250000000&&$check<= 500000000){
        if($npwp==0){
            $return['bracket']=3;
            $return['percent']=30;
        }else if($npwp==1){
            $return['bracket']=3;
            $return['percent']=25;
        }
    }else if($check>50000000){
        if($npwp==0){
            $return['bracket']=4;
            $return['percent']=36;
        }else if($npwp==1){
            $return['bracket']=4;
            $return['percent']=30;
        }
    }else {
        $return['error']=0;
        $return['msg']='please enter details of a specific range';
    }
    return $return;
}
$arr=check_bracket(60000001);

function pph21($C,$T,$npwp){
    $return=array();
    $initial_tax=check_bracket($C*0.5,$npwp);
    $next_tax=check_bracket(($C+$T)*0.5,$npwp);
    if($initial_tax['bracket']==$next_tax['bracket']){
        
        $gross_up=ceil($T/(1-($initial_tax['percent'])/100*0.5));
        $dpp=$gross_up*0.5;
        echo $tax=ceil($dpp*($initial_tax['percent']/100));
        $return['invoice_groupm']=$gross_up;
        $return['kol_payment']=$gross_up-$tax;
        $return['tax']=$tax;
        $return['dpp']=$dpp;
        return $return;
    }else{
        
        if($next_tax['bracket']==2){ 
            // echo (120000000-$C)*0.5*tax_bracket(1,$npwp)/100;die;
            // echo (120000000-$C)*0.5*tax_bracket(1,$npwp)/100;die;
           echo $cal1=($T+(120000000-$C)*0.5*tax_bracket(1,$npwp)/100
            -(120000000*0.5*tax_bracket(2,$npwp)/100)+$C*0.5*tax_bracket(2,$npwp)/100);
            echo '<br>';
          echo  $cal2=(1-(tax_bracket(2,$npwp)/100*0.5));
          echo '<br>';

          echo ceil($cal1/$cal2);
            die();
        echo  $gross_up=ceil(
            ($T+(120000000-$C)*0.5*tax_bracket(1,$npwp)/100
            -(120000000*0.5*tax_bracket(2,$npwp)/100)+$C*0.5*tax_bracket(2,$npwp)/100)
            /(1-(tax_bracket(2,$npwp)/100*0.5))
        );

           $dpp=$gross_up*0.5;
           $tax=ceil((120000000-$C)*0.5*tax_bracket(1,$npwp)/100+($C+$gross_up-120000000)*0.5*tax_bracket(2,$npwp)/100);
           $return['invoice_groupm']=$gross_up;
           $return['kol_payment']=$gross_up-$tax;
           $return['tax']=$tax;
           $return['dpp']=$dpp;
           return $return;
        }else if($next_tax['bracket']==3){
            $gross_up=ceil(($T+(500000000-$C)*0.5*tax_bracket(2,$npwp)/100
            -(500000000*0.5*tax_bracket(3,$npwp)/100)+$C*0.5*tax_bracket(3,$npwp)/100)
            /(1-(tax_bracket(3,$npwp)/100*0.5)));
           $dpp=$gross_up*0.5;
           $tax=ceil((500000000-$C)*0.5*tax_bracket(2,$npwp)/100+($C+$gross_up-500000000)*0.5*tax_bracket(3,$npwp)/100);
           $return['invoice_groupm']=$gross_up;
           $return['kol_payment']=$gross_up-$tax;
           $return['tax']=$tax;
           $return['dpp']=$dpp;
           return $return;

        }else if($next_tax['bracket']==4){
            $gross_up=ceil(($T+(5000000000-$C)*0.5*tax_bracket(3,$npwp)/100
            -(5000000000*0.5*tax_bracket(4,$npwp)/100)+$C*0.5*tax_bracket(4,$npwp)/100)
            /(1-(tax_bracket(4,$npwp)/100*0.5)));
           $dpp=$gross_up*0.5;
           $tax=ceil((5000000000-$C)*0.5*tax_bracket(3,$npwp)/100+($C+$gross_up-5000000000)*0.5*tax_bracket(4,$npwp)/100);
           $return['invoice_groupm']=$gross_up;
           $return['kol_payment']=$gross_up-$tax;
           $return['tax']=$tax;
           $return['dpp']=$dpp;
           return $return;

        }
    }
    

}
// $return = pph21(118000000,118000000,1);
// echo "invoice to group m".$return['invoice_groupm'].'<br>';
// echo "kol payment".$return['kol_payment']."<br>";
// echo "tax".$return['tax']."<br>";
// echo "dpp".$return['dpp']."<br>";
function pph23($T){
    $gross_up=$T/(1-0.02);
    $dpp=$gross_up;
    $tax=$dpp*0.02;   
    $gross_up-$tax;
    
    return $gross_up-$tax;
}

function ppn($T){
    $gross_up=ceil($T/(1-0.02));
    $dpp=$gross_up;
    $ppn=ceil($gross_up*0.11);
    return $gross_up+$ppn;
}

// $a=pph21('0','118000000',1);
// echo "<pre>";
// print_r($a);
// echo "</pre>";
// echo ppn(60000000);
//$pt=1 
//$pt=2
function pph23_ppn($pt,$T){
    $return=array();
if($pt==1){
$return['pph23']=pph23($T);
$return['ppn']=ppn($T);
}else if($pt==2){
    $return['ppn']=ppn($T);
}
return $return;
}
// $arr=pph23_ppn(2,60000000);
// echo  "<pre>";
// print_r($arr);
// echo "</pre>";
function total_payment(){

}
?>