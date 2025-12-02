
            <div class="container-xxl flex-grow-1 container-p-y">
             <h4>Appointment Details</h4>
                 <div class="card">
                    <div class="card-body">
             <div class="col-md-12 ">
             <div class="row">
             <div class="col-md-2 ">
                 <img
                          src="../assets/<?php if($pat_user[0]->photo!=""){echo "images/".$pat_user[0]->photo;}else{ echo 'img/avatars/user.png'; } ?>"
                          alt="user-avatar"
                          class="d-block rounded-circle"
                          height="130"
                          width="130"
                          id="uploadedAvatar"
                        />
                 </div>
                 
                 <div class="col-md-6 mb-4 ml-4">
                     <p>First Name: <b><?php echo $pat_user[0]->first_name;?></b></p>
                     <p>Last Name: <b><?php echo $pat_user[0]->last_name;?></b></p>
                     <p>Email: <b><?php echo $pat_user[0]->email;?></b></p>
                     <p>Phone: <b><?php echo $pat_user[0]->phone;?></b></p>
                     <p>Appointment Date: <b><?php echo date("D, d-M-Y", $appointment[0]->start_time); ?></b></p>
                     <p>Appointment Time: <b><?php echo date("h:i a", $appointment[0]->start_time); ?> - <?php echo date("h:i a", $appointment[0]->end_time); ?></b></p>
                     <p>Symptoms: <br><b><?php echo str_replace(",", ", ", $appointment[0]->symptoms); ?></b></p>
                      <p>
                        Appointment Status:<b> <?php if($appointment[0]->doc_accept=="1"){echo "Doctor Approved Meeting";}else if($appointment[0]->doc_accept=="2"){echo "Doctor Re-sheduled Meeting";}else if($appointment[0]->doc_accept=="3"){echo "Doctor Rejected Meeting";}else if($appointment[0]->doc_accept==NULL){echo "Awaiting Doctor's Approval";}
                          ?></b></p>
                        <?php if($appointment[0]->address!=NULL){
                            ?>
                        <p>
                            Meeting Link/Location:<b> <?php echo $appointment[0]->address; ?></b></p>
                        <?php
                            }
                        ?>
                 </div>
                  
                 <div class="col-md-4 mb-4">
                     <button data-bs-toggle="modal"
                          data-bs-target="#AcceptAppointment" class="btn btn-primary btn-sm me-2 mb-2"><i class="bx bx-calendar-check"></i> Accept Appointment</button> 
                     
                     <button data-bs-toggle="modal"
                          data-bs-target="#RescheduleAppointment" class="btn btn-warning btn-sm me-2 mb-2"><i class="bx bx-calendar"></i> Reschedule Appointment</button>  
                     
                     <button data-bs-toggle="modal"
                          data-bs-target="#RejectAppointment" class="btn btn-danger btn-sm me-2 mb-2"><i class="bx bx-calendar-x"></i> Reject Appointment</button>
                     
                     <a href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>" class="btn btn-info btn-sm me-2"><i class="bx bx-time"></i> Readings History</a>
                 </div>
                  
                  </div>
                  </div>
                  </div>
                  </div>
                 
                <div class="row mt-4">
               
              </div>
             
            </div>
            <!-- / Content -->

            <!-- Modal -->
 <div class="modal fade" id="AcceptAppointment" data-bs-backdrop="static" tabindex="-1">
                          <div class="modal-dialog">
                            <form class="modal-content" method="post">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                        <input type = "hidden" name = "accept_appointment" value = "<?php echo $appointment[0]->id; ?>">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">Accept this appointment</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                              <div class="modal-body">
                               
                                <div class="row">
                                  <div class="col mb-3">
                                    <label  class="form-label">Address / Meeting Link</label>
                                    <input
                                      type="text"
                                      name="address"
                                      class="form-control"
                                      placeholder="Enter meeting address / link"
                                           value="<?php echo $appointment[0]->address; ?>"
                                           required
                                    />
                                  </div>
                                </div>
                                  
                                <div class="row">
                                  <div class="col mb-3">
                                    <label  class="form-label">Consultancy Fee</label>
                                     <input
                                      type="number"
                                      step="0.01"
                                      name="cost"
                                      class="form-control"
                                      placeholder="Enter your consultancy fee"
                                            value="<?php echo $appointment[0]->cost; ?>"
                                           required
                                    />
                                  </div>
                                </div>
                               
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="submit" class="btn btn-primary">Accept</button>
                              </div>
                            </form>
                          </div>
                        </div>
          
 <div class="modal fade" id="RescheduleAppointment" data-bs-backdrop="static" tabindex="-1">
                          <div class="modal-dialog">
                            <form class="modal-content" method="post">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                        <input type = "hidden" name = "reschedule_appointment" value = "<?php echo $appointment[0]->id; ?>">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">Reschedule this appointment</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                              <div class="modal-body">
                               
                                <div class="row">
                                     <div class="col mb-3">
                                    <label  class="form-label">Select Date</label>
                            <input class="form-control" type="date" name="appointment_date_reschedule" id="appt_date" onkeyup="var dt=$('#appt_date').val(); appointmt_time('<?php echo $user[0]->ref_code; ?>', dt);", onblur="var dt=$('#appt_date').val(); appointmt_time('<?php echo $user[0]->ref_code; ?>', dt);" required min="<?php echo date("Y-m-d", strtotime("today")); ?>">
                                 
                             </div>
                             </div>
                             
                                   <div class="row">
                             <div class="col mb-3">
                                    <label  class="form-label">Select time of the day</label>
                            <select class="form-select" name="appointment_time_reschedule" id="appt_time" required>
                                   <option value="">--</option>
                                 </select>
                             </div>
                                  </div>
                                  
                                <div class="row">
                                  <div class="col mb-3">
                                    <label  class="form-label">Address / Meeting Link</label>
                                    <input
                                      type="text"
                                      name="address"
                                      class="form-control"
                                      placeholder="Enter meeting address / link"
                                           value="<?php echo $appointment[0]->address; ?>"
                                           required
                                    />
                                  </div>
                                </div>
                                  
                                <div class="row">
                                  <div class="col mb-3">
                                    <label  class="form-label">Consultancy Fee</label>
                                     <input
                                      type="number"
                                      step="0.01"
                                      name="cost"
                                      class="form-control"
                                      placeholder="Enter your consultancy fee"
                                            value="<?php echo $appointment[0]->cost; ?>"
                                           required
                                    />
                                  </div>
                                </div>
                               
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="submit" class="btn btn-primary">Reschedule and Accept</button>
                              </div>
                            </form>
                          </div>
                        </div>
          
 <div class="modal fade" id="RejectAppointment" data-bs-backdrop="static" tabindex="-1">
                          <div class="modal-dialog">
                            <form class="modal-content" method="post">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                        <input type = "hidden" name = "reject_appointment" value = "<?php echo $appointment[0]->id; ?>">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">Reject this appointment</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                              <div class="modal-body">
                               
                                <div class="row">
                                     <div class="col mb-3">
                                    Are you sure you want to reject this appointment?
                             </div>
                             </div>
                             
                               
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="submit" class="btn btn-primary">Reject</button>
                              </div>
                            </form>
                          </div>
                        </div>
          