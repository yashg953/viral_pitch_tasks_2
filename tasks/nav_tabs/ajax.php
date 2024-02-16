<?php
 require_once '../../library/config_dyn.php';
 require_once '../../class/query.class.php';
 require_once '../../class/backlogin.php';
 $commonback = new backenduser();
 ini_set('display_errors', 0);
if(isset($_POST)&&!empty($_POST)){


    if(isset($_POST['content'])&&$_POST['content']=='age_wise'){
        echo '<table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Age group</th>
            <th scope="col">Audience</th>
            </tr>
        </thead>
        <tbody>
            
            <tr>
            <th scope="row">1</th>
            <td>15-20</td>
            <td>300k</td>
            
            
            
            </tr>
            <tr>
            <th scope="row">2</th>
            <td>20-25</td>
            <td>500k</td>
            
            
            </tr>
            <tr>
            <th scope="row">3</th>
            <td>25-30</td>
            <td>100k</td>
            
            
            </tr>
            
        </tbody>
    </table>';
    }else if(isset($_POST['content'])&&$_POST['content']=='state_wise'){
        echo '<table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">State</th>
            <th scope="col">Audience</th>
            </tr>
        </thead>
        <tbody>
            
            <tr>
            <th scope="row">1</th>
            <td>Maharastra</td>
            <td>1M</td>
            
            
            </tr>
            <tr>
            <th scope="row">2</th>
            <td>Uttar Pradesh</td>
            <td>900K</td>
            
            
            </tr>
            <tr>
            <th scope="row">3</th>
            <td>Punjab</td>
            <td>800K</td>
            
            
            </tr>
            
        </tbody>
    </table>';
    }else if(isset($_POST['permission'])){
        if($_POST['permission']==1){

        }elseif($_POST['permission']==2){

        }else if($_POST['permission']==3){
            $nav_item='<li class="nav-item">
                <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#info" role="tab" aria-controls="tab1" aria-selected="true">Info</a>
                </li>                    
                <li class="nav-item">
                    <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#audience_data" role="tab" aria-controls="tab2" aria-selected="false">Audience Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#campaign_data" role="tab" aria-controls="tab2" aria-selected="false">Campaign History</a>
                </li>    
                <li class="nav-item">
                    <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#payment_data" role="tab" aria-controls="tab2" aria-selected="false">Payment History</a>
                </li>';
            $info='<div class="d-flex align-items-start justify-content-center mt-4">
                    <div class="mr-3">
                        <img src="https://images.pexels.com/photos/434346/pexels-photo-434346.jpeg?cs=srgb&dl=pexels-pixabay-434346.jpg&fm=jpg" alt="" height="200px" width="200px">
                        <div class="social-icons mt-2 d-flex justify-content-center">
                            
                            <div class="mr-4">
                                        <a class="d-flex justify-content-center" href="#" class="mr-2"><img src="icon/instagram.png" alt=""></a>   
                                        <p >1.2K</p> 
                            </div>
                            <div class="mr-4">
                                        <a class="d-flex justify-content-center" href="#" class="mr-2"><img src="icon/facebook.png" alt=""></a>
                                        <p>20K</p>
                            </div>
                            <div class="mr-4">
                                        <a class="d-flex justify-content-center" href="#" class="mr-2"><img src="icon/tiktok.png" alt=""></a>
                                        <p>40K</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="row">
                            <div class="col text-right">
                                <p class="mb-1"><strong>Name:</strong></p>
                                <p class="mb-1"><strong>Gender:</strong></p>
                                <p class="mb-0"><strong>Age:</strong></p>
                                <p class="mb-0"><strong>Kyc:</strong></p>
                                <p class="mb-0"><strong>Date joined:</strong></p>

                            </div>
                            <div class="col">
                                <p class="mb-1">Yash</p>
                                <p class="mb-1">Male</p>
                                <p class="mb-0">29</p>
                                <p class="mb-0">Done</p>
                                <p class="mb-0">29 Feb 2022</p>

                                <div class="row mt-1">
                                <button type="button" class="btn btn-success">call</button>

                                <button type="button" class="btn btn-success ml-1">Invite</button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="d-flex justify-content-center">Documents</p>
                    <!-- <div class="d-flex justify-content-center">
                        <a  href="#" class="mr-2"><img src="icon/drive.png" alt=""></a>
                    
                        <a  href="#" class="mr-2"><img src="icon/drive.png" alt=""></a>
                        <a href="#" class="mr-2"><img src="icon/drive.png" alt=""></a>
                    </div>
                    <div class="d-flex just ify-content-center">
                        <p>sdf</p>
                    </div> -->
                    <div class="container w-50">
                        <div class="row ">
                            <div class="col-sm text-center">
                            <a  href="#" class=""><img src="icon/drive.png" alt=""></a> 
                            <p>pan</p>
                            </div>
                            <div class="col-sm text-center">
                            <a  href="#" class=""><img src="icon/drive.png" alt=""></a>
                            <p>aadhar</p>
                            </div>
                            <div class="col-sm text-center">
                            <a  href="#" class=""><img src="icon/drive.png" alt=""></a>
                            <p>document</p>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>';
                    $campaign_data='<div style="max-height: 300px; overflow-y: auto;">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Campaign Name</th>
                                            <th scope="col">Campaign Type</th>
                                            <th scope="col">Applied</th>
                                            <th scope="col">Order</th>
                                            <th scope="col">Review</th>
                                            <th scope="col">Review Proff</th>
                                            <th scope="col">Payout</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <tr>
                                            <th scope="row">1</th>
                                            <td><div class="container"><img src="https://images.pexels.com/photos/434346/pexels-photo-434346.jpeg?cs=srgb&dl=pexels-pixabay-434346.jpg&fm=jpg" alt="" height="40px" width="40px"></div><br>Campaign 1</td>
                                            <td>Barter</td>
                                            <td><span class="badge badge-success">Approved</span><br>22 nov 2023</td>
                                            <td><span class="badge badge-success">Approved</span><br>23 nov 2023</td>
                                            <td><span class="badge badge-success">Approved</span><br>24 nov 2023</td>
                                            <td><span class="badge badge-success">Approved</span><br>25 nov 2023</td>
                                            <td>156</td>
                                            
                                            </tr>
                                            <tr>
                                            <th scope="row">2</th>
                                            <td>Campaign 2</td>
                                            <td>Social</td>
                                            <td>Applied</td>
                                            <td><span class="badge badge-success">Approved</span></td>
                                            <td>189</td>
                                            
                                            
                                            </tr>
                                            <tr>
                                            <th scope="row">3</th>
                                            <td>Campaign 3</td>
                                            <td>Social</td>
                                            <td>Applied</td>
                                            <td><span class="badge badge-danger">Rejected</span></td>
                                            <td>190</td>
                                            
                                            
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div> ';
            
                        $payment_data='<div style="max-height: 300px; overflow-y: auto;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Campaign Name</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Payment Type</th>
                                        <th scope="col">Payout</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <th scope="row">1</th>
                                        <td><div class="container"><img src="https://images.pexels.com/photos/434346/pexels-photo-434346.jpeg?cs=srgb&dl=pexels-pixabay-434346.jpg&fm=jpg" alt="" height="40px" width="40px"></div><br>Campaign 1</td>
                                        <td>20 sept 2023</td>
                                        <td><span class="badge badge-danger">Cr</span></td>
                                        <td>400</td>
                                        
                                        
                                        </tr>
                                        <tr>
                                        <th scope="row">2</th>
                                        <td>Campaign 2</td>
                                        <td>23 oct 2023</td>
                                        <td><span class="badge badge-success">Dr</span></td>
                                        <td>900</td>
                                        
                                        
                                        </tr>
                                        <tr>
                                        <th scope="row">3</th>
                                        <td>Campaign 3</td>
                                        <td>22 nov 2023</td>
                                        <td><span class="badge badge-danger">Cr</span></td>
                                        <td>790</td>
                                        
                                        
                                        </tr>
                                        
                                    </tbody>
                                </table>
                                
                            </div>  ';
                    echo json_encode(array("nav_items"=>$nav_item,"info"=>$info,"campaign_data"=>$campaign_data,"payment_data"=>$payment_data));
        
        
        }
    }
    else if($_POST['update']==1){
        
        $table='user_remarks';
        $insert="vp_user='".$_POST['vp_user']."',timestamp='".$_POST['timestamp']."',remark='".$_POST['remark']."',user=".$_POST['user_id']."";
        $commonback->Queryinsert($table,$insert,'sdf');
        $table='deals_calling_creator';
        $query="calling_status='".$_POST['status']."'";
        $where=" where id='".$_POST['user_id']."'";
        $commonback->Queryupdate($table, $query, 'Category Not Update',$where);
        $table='deals_calling_creator';
        $query="calling_status='".$_POST['status']."'";
        $where=" where id='".$_POST['user_id']."'";
        $commonback->Queryupdate($table, $query, 'Category Not Update',$where);
    }
    else if($_POST['update']==2){
        $table='deals_calling_creator';
        $query="calling_status='".$_POST['status']."'";
        $where=" where id='".$_POST['user_id']."'";
        $commonback->Queryupdate($table, $query, 'Category Not Update',$where);

    }
    else if($_POST['get']==1){
        $select='v.name as vp_user,u.remark as remark,u.timestamp as timestamp ';
        $table='user_remarks as u';
        $where=" LEFT JOIN vp_user as v ON u.vp_user = v.id where user='".$_POST['user_id']."' ORDER BY u.id DESC ";
        $result['remark']=$commonback->QueryFieldMultipleSelect($table,$where,$select);
        $select='calling_status';
        $table='deals_calling_creator';
        $where=" where id='".$_POST['user_id']."'";
        $calling_status=$commonback->QueryFieldMultipleSelect($table,$where,$select);
        $result['calling_status']=$calling_status=$calling_status[0]['calling_status'];
        echo json_encode($result);
    }    
}

?>