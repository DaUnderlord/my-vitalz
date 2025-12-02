
            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> Doctor's Profile</h4>

                 <div class="row">
                     <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
                  <div class="card">
                    <div class="row row-bordered g-0">
                      
                        <div class="col-md-6 py-3">
                        <div class="text-center ">
                        <img
                          src="../assets/<?php if($doctor_profile[0]->photo!=""){echo "images/".$doctor_profile[0]->photo;}else{ echo 'img/avatars/user.png'; } ?>"
                          alt="user-avatar"
                          class=" rounded-circle"
                          height="100"
                          width="100"
                          id="uploadedAvatar"
                        />
                            </div>
                            
                            <div class="row text-center">
                            <h4 class="mt-2"><?php echo $doctor_profile[0]->first_name." ".$doctor_profile[0]->last_name; ?></h4>
                                <p class="text-center mt-0"><?php echo $doctor_profile[0]->specialization; ?></p>
                                
                                 <div class="text-center">
                                 <span class="me-2">
                              <span class="badge bg-label-primary p-2"><i class="bx bx-phone-call text-primary"></i></span>
                            </span>
                                
                                 <span class="me-2">
                              <span class="badge bg-label-primary p-2"><i class="bx bx-chat text-primary"></i></span>
                            </span>
                                 <span class="me-2">
                              <span class="badge bg-label-primary p-2"><i class="bx bx-video text-primary"></i></span>
                            </span>
                            </div>
                            </div>
                            
                            <div class="row p-3 mt-3">
                                <div class="col-sm-12">
                                <h4 class="mb-0">Available Days</h4>
                                    <div class="col-sm-12">
                                <div class="demo-inline-spacing">
                                    <?php 
                                    if($appointment_schedule->sunday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Sunday</span>
                                    <?php
                                    }
                                    
                                    if($appointment_schedule->monday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Monday</span>
                                     <?php
                                    }
                                    
                                    if($appointment_schedule->tuesday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Tuesday</span>
                                     <?php
                                    }
                                    
                                    if($appointment_schedule->wednesday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Wednesday</span>
                                     <?php
                                    }
                                    
                                    if($appointment_schedule->thursday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Thursday</span>
                                     <?php
                                    }
                                    
                                    if($appointment_schedule->friday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Friday</span>
                                     <?php
                                    }
                                    
                                    if($appointment_schedule->saturday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Saturday</span>
                                     <?php
                                    }
                                    
                                        ?>
                                    </div>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="row p-3 mt-2">
                                <div class="col-sm-12">
                                <h4 class="mb-0">Available Periods</h4>
                                    <div class="col-sm-12">
                                <div class="demo-inline-spacing">
                                     <?php
                                    if($appointment_schedule->sunday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Sundays<br><br><?php echo date("h:i a", strtotime($appointment_schedule->sunday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule->sunday_end));?></span>
                                    <?php   
                                    }
                                    
                                    if($appointment_schedule->monday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Mondays<br><br><?php echo date("h:i a", strtotime($appointment_schedule->monday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule->monday_end));?></span>
                                    <?php   
                                    }
                                    
                                    
                                    if($appointment_schedule->tuesday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Tuesdays<br><br><?php echo date("h:i a", strtotime($appointment_schedule->tuesday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule->tuesday_end));?></span>
                                    <?php   
                                    }
                                    
                                    
                                    if($appointment_schedule->wednesday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Wednesdays<br><br><?php echo date("h:i a", strtotime($appointment_schedule->Wednesday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule->Wednesday_end));?></span>
                                    <?php   
                                    }
                                    
                                    
                                    if($appointment_schedule->thursday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Thursdays<br><br><?php echo date("h:i a", strtotime($appointment_schedule->thursday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule->thursday_end));?></span>
                                    <?php   
                                    }
                                    
                                    
                                    if($appointment_schedule->friday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Fridays<br><br><?php echo date("h:i a", strtotime($appointment_schedule->friday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule->friday_end));?></span>
                                    <?php   
                                    }
                                    
                                    if($appointment_schedule->saturday_start!=""){
                                        ?>
                                    <span class="badge rounded-pill bg-label-primary">Saturdays<br><br><?php echo date("h:i a", strtotime($appointment_schedule->saturday_start)); ?> - <?php echo date("h:i a", strtotime($appointment_schedule->saturday_end));?></span>
                                    <?php   
                                    }
                                    
                                    ?>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="row p-3 mt-2">
                                <div class="col-sm-12">
                                <h4 class="mb-0">Consultation Fee</h4>
                                    <div class="col-sm-12">
                                <div class="demo-inline-spacing">
                                    <h4 class="text-primary">N20,000</h4>
                                    
                                    </div>
                                    </div>
                                </div>
                            </div>
                          
                      </div>
                        
                      <div class="col-md-6">
                        <div class="card-body">
                            <h4 class="mt-3">Book an Appointment</h4>
                         <form class="form" method="post">
                             <input type="hidden" name="doctor" value="<?php echo $doctor_profile[0]->ref_code; ?>">
                             <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>"> 
                             <div class="form-group">
                                 <label>Select Date</label>
                            <input class="form-control" type="date" name="appointment_date" id="appt_date" onkeyup="var dt=$('#appt_date').val(); appointmt_time('<?php echo $doctor_profile[0]->ref_code; ?>', dt);", onblur="var dt=$('#appt_date').val(); appointmt_time('<?php echo $doctor_profile[0]->ref_code; ?>', dt);" required min="<?php echo date("Y-m-d", strtotime("today")); ?>">
                                 
                             </div>
                             
                             <div class="form-group mt-3">
                                 <label>Select time of the day</label>
                            <select class="form-select" name="appointment_time" id="appt_time" required>
                                   <option value="">--</option>
                                 </select>
                             </div>
                             
                             <div class="form-group mt-3">
                                 <label>Select channel</label>
                            <select class="form-select" required name="channel">
                                   <option value="">--</option>
                                   <option value="Text Chat">Text Chat</option>
                                   <option value="Audio Call">Audio Call</option>
                                   <option value="In-app Call">In-app Call</option>
                                   <option value="Whatsapp Call">Whatsapp Call</option>
                                   <option value="Physical Meeting (In Person)">Physical Meeting (In Person)</option>
                                   <option value="Video In-app Call">Video In-app Call</option>
                                 </select>
                             </div>
                             
                             <div class="form-group mt-3">
                                 <label>Purpose</label>
                            <select class="form-select" required name="purpose" id="purpose" onchange="if($('#purpose').val()=='Special Cases'){$('#symptoms').removeClass('d-none');}else{$('#symptoms').addClass('d-none');}">
                                   <option value="">--</option>
                                   <option value="Routine Blood Pressure Review">Routine Blood Pressure Review</option>
                                   <option value="Routine Blood Glucose Review">Routine Blood Glucose Review</option>
                                   <option value="Routine Blood Pressure and Glucose Review">Routine Blood Pressure and Glucose Review</option>
                                   <option value="Other Routine Reviews">Other Routine Reviews e.g heart rate, stress readings</option>
                                   <option value="Special Cases">Special Cases</option>
                                 </select>
                             </div>
                             
                             <div class="form-group mt-3 d-none" id="symptoms">
                                 <label>Select your symptoms (Optional)</label><br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Breathlessness"> Breathlessness<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Non Productive Cough"> Non Productive Cough<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Cough Productive of Frothy Sputum"> Cough Productive of Frothy Sputum<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Cough Productive of a yellowish Sputum"> Cough Productive of a yellowish Sputum<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Cough Productive of blood or bloody Sputum"> Cough Productive of blood or bloody Sputum<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Difficulty with breathing at rest"> Difficulty with breathing at rest<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Difficulty with breathing on exertion"> Difficulty with breathing on exertion<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Difficulty with breathing when lying down"> Difficulty with breathing when lying down<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Need for multiple pillows to sleep at night"> Need for multiple pillows to sleep at night<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Leg swelling"> Leg swelling<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Faint Attacks"> Faint Attacks<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Weakness of one side of the body"> Weakness of one side of the body<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Shifting of the mouth"> Shifting of the mouth<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Weakness of one side of the face"> Weakness of one side of the face<br>
                          <input class="checkbox" type="checkbox" name="symptoms[]" value="Difficulty with swallowing"> Difficulty with swallowing<br>
                         Others
                                 <textarea class="form-control" name="symptoms[]" ></textarea>
                                 <br>
                             </div>
                             
                             <div class="form-group mt-3">
                              <button class="btn btn-primary bt-sm">Book</button>
                             </div>
                             
                            </form>
                        </div>
                       
                      </div>
                    </div>
                  </div>
                </div>
               
            </div>
            <!-- / Content -->

           
          