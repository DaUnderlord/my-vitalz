            <div class="container-xxl flex-grow-1 container-p-y">
             <h4>Appointments</h4>
                <div class="row mt-4">
                <div class="col-xl-6">
                    <div class="card mb-4">
                    <h5 class="card-header">Pending appointments</h5>
                    <div class="card-body">
                          <?php
                        if(!empty($pending_appointments)){
                            for($i=count($pending_appointments)-1; $i>=0; $i--){
                        
                        ?>
                  <div class="card shadow-none bg-transparent border border-primary mb-3">
                    <div class="card-body">
                      <h5 class="card-title"><?php echo $appointment_user[$i]->first_name." ".$appointment_user[$i]->last_name; ?></h5>
                      <small class="card-text"><?php echo date("D, d-M-Y", $pending_appointments[$i]->start_time); ?> <br> <?php echo date("h:i a", $pending_appointments[$i]->start_time); ?> - <?php echo date("h:i a", $pending_appointments[$i]->end_time); ?></small><br>
                        
                         <small class="card-text">
                            <b class="text-primary">Status:</b> <?php if($pending_appointments[$i]->doc_accept=="1"){echo "Doctor Approved Meeting";}else if($pending_appointments[$i]->doc_accept=="2"){echo "Doctor Re-sheduled Meeting";}else if($pending_appointments[$i]->doc_accept=="3"){echo "Doctor Rejected Meeting";}else if($pending_appointments[$i]->doc_accept==NULL){echo "Awaiting Doctor's Approval";}
                            ?></small><br>
                        <?php if($pending_appointments[$i]->address!=NULL){
                            ?>
                        <small class="card-text">
                            <b class="text-primary">Location:</b> <?php echo $pending_appointments[$i]->address; ?></small><br>
                        <?php
                            }
                        ?>
                        
                        <p class="txt-color-green mt-3 bg-color-green-light col-sm-6 text-center p-1" style="border-radius:10px;"><small><?php echo $pending_appointments[$i]->channel; ?></small></p>
                        
                        <a class="btn btn-primary btn-sm" href="?pg=appointment_details&aptid=<?php echo $pending_appointments[$i]->id; ?>">Appointment Details</a>
                    </div>
                  </div>
                        
                        <?php
                            }
                        }else{
                                ?>
                         
                  <div class="card shadow-none bg-transparent border border-primary mb-3">
                    <div class="card-body">
                        <p class=" mt-3 col-sm-6 text-center p-1" ><small>No pending appointments</small></p>
                    </div>
                  </div>
                        <?php
                        }
                            ?>
                </div>
                    </div>
                </div>

                <div class="col-xl-6">
                  <div class="card mb-4">
                    <h5 class="card-header">My Appointment Schedule</h5>
                    <div class="card-body">
                      <div>
                          <h6>Mondays: </h6>
                          <?php  if(!empty($appointment_schedule[0]->monday_start)){echo date("h:i a", strtotime($appointment_schedule[0]->monday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule[0]->monday_end));}else{echo "-- : --";} ?>
                          <hr>
                   
                          <h6>Tuesdays:</h6>
                          <?php  if(!empty($appointment_schedule[0]->tuesday_start)){echo date("h:i a", strtotime($appointment_schedule[0]->tuesday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule[0]->tuesday_end));}else{echo "-- : --";} ?>
                          <hr>
                   
                          <h6>Wednesdays:</h6>
                          <?php  if(!empty($appointment_schedule[0]->wednesday_start)){echo date("h:i a", strtotime($appointment_schedule[0]->wednesday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule[0]->wednesday_end));}else{echo "-- : --";} ?>
                          <hr>
                   
                          <h6>Thursdays:</h6>
                          <?php  if(!empty($appointment_schedule[0]->thursday_start)){echo date("h:i a", strtotime($appointment_schedule[0]->thursday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule[0]->thursday_end));}else{echo "-- : --";} ?>
                          <hr>
                   
                          <h6>Fridays:</h6>
                          <?php  if(!empty($appointment_schedule[0]->friday_start)){echo date("h:i a", strtotime($appointment_schedule[0]->friday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule[0]->friday_end)); }else{echo "-- : --";}?>
                          <hr>
                   
                          <h6>Saturdays:</h6>
                          <?php  if(!empty($appointment_schedule[0]->saturday_start)){echo date("h:i a", strtotime($appointment_schedule[0]->saturday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule[0]->saturday_end));}else{echo "-- : --";} ?>
                          <hr>
                   
                          <h6>Sundays:</h6>
                          <?php if(!empty($appointment_schedule[0]->sunday_start)){echo date("h:i a", strtotime($appointment_schedule[0]->sunday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule[0]->sunday_end));}else{echo "-- : --";} ?>
                          <hr>
                   
                    <button type="button" class="btn btn-info me-4 mb-2" data-bs-toggle="modal"
                          data-bs-target="#AppointmentScheduleModal">
                              <span class="tf-icons bx bx-edit"></span>&nbsp; Edit Schedule
                            </button> 
                      </div>
                    </div>
                  </div>
                
                </div>
              </div>
             
            </div>
            <!-- / Content -->

           
          