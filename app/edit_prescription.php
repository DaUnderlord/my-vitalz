
            <div class="container-xxl flex-grow-1 container-p-y">
             <h4>Patient Details</h4>
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
                 
                 <div class="col-md-6 mb-4">
                     <p>First Name: <b><?php echo $pat_user[0]->first_name;?></b></p>
                     <p>Last Name: <b><?php echo $pat_user[0]->last_name;?></b></p>
                     <p>Email: <b><?php echo $pat_user[0]->email;?></b></p>
                     <p>Phone: <b><?php echo $pat_user[0]->phone;?></b></p>
                 </div>
                  
                 <div class="col-md-4 mb-4">
                     <a href="?pg=patient_medications_all&ptid=<?php echo $pat_user[0]->ref_code;?>" class="btn btn-primary btn-sm me-2"><i class="bx bx-capsule"></i> Medications</a>
                     <a href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>" class="btn btn-info btn-sm me-2"><i class="bx bx-time"></i> Readings History</a>
                 </div>
                  
                  </div>
                  </div>
                  </div>
                  </div>
                 
                <div class="row mt-4">
                      
                 <div class="col-md-12">
                <div class="row">
                    <h4>Edit Prescription</h4>
                    
<div class="col-xl-12">
                    <div class="card mb-4">
                   
                    <div class="card-body">
                        
                 <form class="form" method="post">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                        <input type = "hidden" name = "pres_id" value = "<?php echo $medications[0]->id; ?>">
                         
                           
                                <div class="row" id="prescriptions">
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Drug Name</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <input
                                      type="text"
                                      name="drug_nameed"
                                      class="form-control"
                                      placeholder="Drug name"
                                          required
                                          value="<?php echo $medications[0]->drug_name; ?>"
                                    />
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                     
                           
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Drug Type</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <select
                                      name="drug_typeed"
                                      class="form-control"
                                          required >
                                       <option value="tablets" <?php if($medications[0]->drug_type=="tablets"){ echo "selected"; } ?>>Tablets</option>
                                       <option value="capsules" <?php if($medications[0]->drug_type=="capsules"){ echo "selected"; } ?>>Capsules</option>
                                       <option value="syrup" <?php if($medications[0]->drug_type=="syrup"){ echo "selected"; } ?>>Syrup</option>
                                          
                                          </select>
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                     
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Duration</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <input
                                      type="text"
                                      name="durationed"
                                      class="form-control"
                                      placeholder="Duration"
                                          required
                                          value="<?php echo $medications[0]->duration; ?>"
                                    />
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                     
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Dosage</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <input
                                      type="text"
                                      name="dosageed"
                                      class="form-control"
                                      placeholder="Dosage"
                                          required
                                          value="<?php echo $medications[0]->dosage; ?>"
                                    />
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                     
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Frequency</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <input
                                      type="text"
                                      name="frequencyed"
                                      class="form-control"
                                      placeholder="Frequency"
                                         required 
                                          value="<?php echo $medications[0]->frequency; ?>"
                                    />
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                     
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Additional Information</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <textarea
                                      type="text"
                                      name="additionaled"
                                      class="form-control"
                                      placeholder="Any additional details"
                                          
                                             ><?php echo $medications[0]->additional_info; ?></textarea>
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                                   
                     </div>
                              <div class="modal-footer">
                                
                                <a href="?pg=patient_medications_all&ptid=<?php echo $pat_user[0]->ref_code;?>" class="btn btn-primary">Back</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                              </div>
                            </form>
                        
                </div>
                    </div>
                </div>

                </div>
                </div>
                

              </div>
             
            </div>
            <!-- / Content -->
