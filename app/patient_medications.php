
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
                     <a href="?pg=patient_medications_all&ptid=<?php echo $pat_user[0]->ref_code;?>" class="btn btn-primary btn-sm me-2 mb-2"><i class="bx bx-capsule"></i> Medications</a>
                     <a href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>" class="btn btn-info btn-sm me-2 mb-2"><i class="bx bx-time"></i> Readings History</a>
                 </div>
                  
                  </div>
                  </div>
                  </div>
                  </div>
                 
                <div class="row mt-4">
                      
                 <div class="col-md-12">
                <div class="row">
                    <h4>Prescriptions  (<?php echo date("d-M-Y", $medications[0]->date); ?>)</h4>
                     <div class="col-xl-4">
                    <a href="?pg=patient_medications_all&ptid=<?php echo $pat_user[0]->ref_code;?>" class="btn btn-primary btn-sm me-2"><i class="bx bx-reply"></i> Back</a>
                    </div>
                     <div class="col-xl-12">
                    <div class="card mb-4">
                   
                    <div class="card-body">
                    <div class="row">
                        
                  <?php
                        for($i=count($medications)-1; $i>=0; $i--){
                            
                            ?>
                  <div class="col-sm-4">
                  <div class="card shadow-none bg-transparent border border-primary mb-3">
                    <div class="card-body">
                    <div class="row">
                   
                         <div class="col-sm-10">
                      <h5 class="card-title txt-color-blue"><?php echo $medications[$i]->drug_name; ?></h5>
                             <h6><?php echo $medications[$i]->drug_type; ?></h6>
                      <small class="card-text"><?php echo $medications[$i]->dosage; ?> - (<?php echo $medications[$i]->frequency; ?>)</small><br>
                    <small class="card-text">Date: <?php echo date("d-M-Y", $medications[$i]->date); ?></small><br>
                             <small>Doctor: <?php echo $doctors[$i][0]->first_name." ".$doctors[$i][0]->last_name; ?></small>
                             <br>
                             <small class="card-text txt-color-blue">Last Compliance: <br><?php echo $compliance[$i][count($compliance[$i])-1]->date; ?></small><br>
                             <br>
                             <a href="" data-bs-toggle="modal"
                          data-bs-target="#ComplianceModal" class="btn btn-info btn-sm mb-2" onclick="check_compliance('<?php echo $pat_user[0]->ref_code; ?>', '<?php echo $medications[$i]->id; ?>')">Check Compliance</a>
                             
                             
                             <a href="?pg=edit_prescription&ptid=<?php echo $pat_user[0]->ref_code; ?>&pscid=<?php echo $medications[$i]->id; ?>" class="btn btn-info btn-sm mb-2">Edit</a>
                        </div>
                        </div>
                    </div>
                  </div>
                  </div>
                        <?php
                        }
                            ?>
                     
                        
                </div>
                </div>
                    </div>
                </div>
              

                </div>
                </div>
                

              </div>
             
            </div>
            <!-- / Content -->

           
          