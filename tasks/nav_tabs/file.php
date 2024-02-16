<?php
                    if($permission==0){?>
                        <li class="nav-item">
                            <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#info" role="tab" aria-controls="tab1" aria-selected="true">Info</a>
                        </li><?php
                    }?>
                    <?php 
                    if($permission==1){?>
                        <li class="nav-item">
                            <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#info" role="tab" aria-controls="tab1" aria-selected="true">Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#audience_data" role="tab" aria-controls="tab2" aria-selected="false">Audience Data</a>
                        </li><?php
                    }else if($permission==2){?>
                        <li class="nav-item">
                            <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#info" role="tab" aria-controls="tab1" aria-selected="true">Info</a>
                        </li>                    
                        <li class="nav-item">
                            <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#audience_data" role="tab" aria-controls="tab2" aria-selected="false">Audience Data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#campaign_data" role="tab" aria-controls="tab2" aria-selected="false">Campaign History</a>
                        </li><?php
                    }else if ($permission==3){?>
                        <li class="nav-item">
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
                        </li>
                        <?php
                        
                    }?>