<?php

$permission=3;
$user_name=2;
require_once '../../library/config_dyn.php';
require_once '../../class/query.class.php';
require_once '../../class/backlogin.php';
$commonback = new backenduser();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class='container text-center ' style='margin-top:5%'>
    <table class="table table-striped">
    <thead>
        <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        
        <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <th scope="row">1</th>
        <td>Mark</td>
        
        <td>
            <button type="button" class="show-btn btn btn-primary" value='1'>Show</button>
            <button type="button" class="call btn btn-primary" value='1'>Call</button>
            <button type="button" class="remark btn btn-primary" value='1'>Remark</button>
        </td>
        </tr>
        <tr>
        <th scope="row">2</th>
        <td>Jacob</td>
        
        <td>
            <button type="button" class="show-btn btn btn-primary" value='2'>Show</button>
            <button type="button" class="call btn btn-primary" value='2'>Call</button>
            <button type="button" class="remark btn btn-primary" value='2'>Remark</button>
        </td>
        </tr>
        <tr>
        <th scope="row">3</th>
        <td>Larry</td>
        <td>
            <button type="button" class="show-btn btn btn-primary" value='3'>Show</button>
            <button type="button" class="call btn btn-primary" value='3'>Call</button>
            <button type="button" class="remark     btn btn-primary" value='3'>Remark</button>
        </td>
        </tr>
        <tr>
        <th scope="row">4</th>
        <td>Angel</td>
        <td>
            <button type="button" class="show-btn btn btn-primary" value='4'>Show</button>
            <button type="button" class="call btn btn-primary" value='4'>Call</button>
            <button type="button" class="remark btn btn-primary" value='4'>Remark</button>
        </td>
        </tr>
        <tr>
        <th scope="row">5</th>
        <td>Anit</td>
        <td>
            <button type="button" class="show-btn btn btn-primary" value='5'>Show</button>
            <button type="button" class="call btn btn-primary" value='5'>Call</button>
            <button type="button" class="remark btn btn-primary" value='5'>Remark</button>
        </td>
        </tr>
    </tbody>
    </table>
    </div>
    




<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <!-- Add the 'modal-lg' class above to make the modal wider -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
 

                    <!-- Add more tabs as needed -->
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <?php
                if($permission==0){?>
                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="tab1-tab">
                        <div class="d-flex align-items-start justify-content-center mt-4">
                            <div class="mr-3">
                                <img src="https://images.pexels.com/photos/434346/pexels-photo-434346.jpeg?cs=srgb&dl=pexels-pixabay-434346.jpg&fm=jpg" alt="" height="200px" width="200px">
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
                                        <div class="social-icons mt-2">
                                            <a href="#" class="mr-2"><i class="fab fa-instagram"></i></a>
                                            <a href="#" class="mr-2"><i class="fab fa-facebook"></i></a>
                                            <a href="#" class="mr-2"><i class="fab fa-tiktok"></i></a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        
                    </div><?php
                }    
                else if($permission==1){?>
                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="tab1-tab">
                    
                                 <!-- Content of Tab 1 -->
                            <div style="display: flex;" class='mt-4'>
                                    <div style='float:left'>
                                        <img src="https://images.pexels.com/photos/434346/pexels-photo-434346.jpeg?cs=srgb&dl=pexels-pixabay-434346.jpg&fm=jpg" alt="" height='100px' width='100px'>
                                    </div>
                                    <div style="margin-left: 10px;">
                                        <p>Name: Yash</p>
                                        <p>Gender: Male </p>
                                        <p>Age: 29</p>
                                        <p>Platform : Instagram</p>
                                        <p>followers : 1.2M</p>
                                        <p>Content of Tab 1 goes here</p>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="audience_data" role="tabpanel" aria-labelledby="tab2-tab">
                        <!-- Content of Tab 2 -->
                        <p>Audience data</p>
                    </div>
                    <?php
                }else if($permission==2){?>
                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="tab1-tab">
                        <div style="display: flex;" class='mt-4'>
                                        <div style='float:left'>
                                            <img src="https://images.pexels.com/photos/434346/pexels-photo-434346.jpeg?cs=srgb&dl=pexels-pixabay-434346.jpg&fm=jpg" alt="" height='100px' width='100px'>
                                        </div>
                                        <div style="margin-left: 10px;">
                                            <p><strong style='color:black'>Name</strong>: Yash</p>
                                            <p>Gender: Male </p>
                                            <p>Age: 29</p>
                                            <p>Platform : Instagram</p>
                                            <p>followers : 1.2M</p>
                                            <p>Content of Tab 1 goes here</p>
                                        </div>
                                </div>
                        </div>
                    <div class="tab-pane fade" id="audience_data" role="tabpanel" aria-labelledby="tab2-tab">
                        <!-- Content of Tab 2 -->
                        <p>Content of Tab 2 goes here.</p>
                    </div>
                    <div class="tab-pane fade" id="campaign_data" role="tabpanel" aria-labelledby="tab2-tab">
                        <!-- Content of Tab 2 -->
                        <p>Content of Tab 2 goes here.</p>
                    </div><?php
                }else if($permission==3){?>   
                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="tab1-tab">

                    </div>


                    <div class="tab-pane fade" id="audience_data" role="tabpanel" aria-labelledby="tab2-tab">
                        <!-- Content of Tab 2 -->
                        
                        
                            <div class='d-flex justify-content-center  container w-50 '>
                                <div class='col-6 mt-2 mb-2'>
                                   <button type="button" id='age_wise' class="content-wise btn btn-success d-flex justify-content-center">Age wise</button>
                                </div>
                                <div class='col-6 mt-2 mb-2'>
                                   <button type="button" id='state_wise' class="content-wise btn btn-success d-flex justify-content-center">State wise</button>
                                </div>
                            
                            </div>
                            <div style="max-height: 300px; overflow-y: auto; " id='show_table'>
                        
                            </div>  
                    </div>
                <div class="tab-pane fade" id="campaign_data" role="tabpanel" aria-labelledby="tab2-tab" >
                        <!-- Content of Tab 2 -->
 
                </div>
                <div class="tab-pane fade" id="payment_data" role="tabpanel" aria-labelledby="tab2-tab" >
                        <!-- Content of Tab 2 -->

                </div>
                <?php    
                }?>                         
                    <!-- Add more tab panes as needed -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="remark_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <!-- Add the 'modal-lg' class above to make the modal wider -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remark</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3 style='display:inline'>Add remarks</h3>
                <form action="">
                <textarea name="" id="remark_area" cols="89" rows="5" style='resize: none;' required></textarea><br>
                <div class="form-group d-flex">
                <select name="" id="status_select" class="custom-select" style="width: 30%; border-color: black;">
                    <option value="0">select status</option>
                    <option value="1">DND</option>
                    <option value="2">DND1</option>
                    <option value="3">DND2</option>
                </select>
                <button type="button" class="btn btn-primary mx-2" id="post_remark">Post</button>
                </div>

                </form>

                <div class='mt-4' id='all_remarks' style='max-height: 300px;overflow-y: auto;'>
                    

                </div>
            </div>
            </div>
        </div>
    </div>
</div>


<!-- Your content goes here -->

</body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            var user_id;
            
            // $('#status_select').on('change',function(){
            //     console.log('changed statsu');
            //     var changed_status=$(this).val();
            //     $.ajax({
            //             url:'http://localhost/viRAL_PITCH_TASK/tasks/nav_tabs/ajax.php',
            //             type:'POST',
            //             data:{update:2,user_id:user_id,status:changed_status},
            //             success:function(response){
                            
                            // var res=JSON.parse(response);
                            // $('#status_select').val(res.calling_status);
                            // console.log(res.calling_status);
                            // var remarks=res.remark;
                            // remarks.forEach(function(res1){
                            //     var date = new Date(res1.timestamp * 1000);
                            //     var formattedDate = date.toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
                            //     $('#all_remarks').append('<div class="review-remark mt-3 shadow"><div class="card"><div class="card-header">'+res1.vp_user+' &nbsp&nbsp'+formattedDate+'</div><div class="card-body">'+res1.remark+'</div></div></div>');
                            // });
            //             }
            //         });
            // });
            function get_data(user_id){
                $('#all_remarks').html('');
                $.ajax({
                    url:'http://localhost/viRAL_PITCH_TASK/tasks/nav_tabs/ajax.php',
                    type:'POST',
                    data:{get:1,user_id:user_id},
                    success:function(response){
                        
                        var res=JSON.parse(response);
                        $('#status_select').val(res.calling_status);
                        console.log(res.calling_status);
                        var remarks=res.remark;
                        remarks.forEach(function(res1){
                            var date = new Date(res1.timestamp * 1000);
                            var formattedDate = date.toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
                            $('#all_remarks').append('<div class="review-remark mt-3 shadow"><div class="card"><div class="card-header">'+res1.vp_user+' &nbsp&nbsp'+formattedDate+'</div><div class="card-body">'+res1.remark+'</div></div></div>');
                        });
                    }
                });
            }
            $('.show-btn').click(function () {
                var id = $(this).val();
                var permission='<?php echo $permission;?>';
                $.ajax({
                        url:'ajax.php',
                        method:'POST',
                        data:{permission:permission},
                        success:function(response){
                            var obj = jQuery.parseJSON(response);
                           // console.log(obj.nav_item);
                               $('#myTabs').html(obj.nav_items);
                           $('#info').html(obj.info);
                           $('#campaign_data').html(obj.campaign_data);
                           $('#payment_data').html(obj.payment_data);
                            
                            
                        }
                    });
                $('#myModal').modal('show'); // Trigger Bootstrap modal show event
                $('.content-wise').click(function(){
                    var content=$(this).attr('id');
                    
                    $('#show_table').html('');  
                    
                    $.ajax({
                        url:'ajax.php',
                        method:'POST',
                        data:{content:content,permission:permission},
                        success:function(response){
                            console.log(response);
                            $('#show_table').append(response);
                        }
                    });
                });

            });
            $('.remark').click(function(){
                user_id = $(this).val();
                $('#remark_Modal').modal('show');
                get_data(user_id);

                
            });
            $('#post_remark').click(function(e){
                
                var changed_status=$('#status_select').val();
                var remark=$('#remark_area').val();
                if(remark!=''){
                    $('#remark_area').val('');
                var vp_user='<?php echo $user_name;?>';
                var timestamp = $.now();
                var timestampInSeconds = Math.floor(timestamp / 1000);
                var date = new Date(timestampInSeconds * 1000); // Multiply by 1000 to convert seconds to milliseconds
                // Format the date as "DD Mon YYYY"
                var formattedDate = date.toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
                console.log(formattedDate);
                $('#all_remarks').append('<div class="review-remark mt-3 shadow"><div class="card"><div class="card-header">'+vp_user+' &nbsp&nbsp'+formattedDate+'</div><div class="card-body">'+remark+'</div></div></div>');
                $.ajax({
                    url:'http://localhost/viRAL_PITCH_TASK/tasks/nav_tabs/ajax.php',
                    type:'POST',
                    data:{vp_user:vp_user,remark:remark,timestamp:timestampInSeconds,update:1,user_id:user_id,status:changed_status},
                    success:function(response){
                        get_data(user_id);
                        //console.log();
                    }
                });


                }
                


            });
            
        });
    </script>



</html>
