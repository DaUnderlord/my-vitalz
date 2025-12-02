
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
                    <h4 class="col-sm-9">Prescriptions</h4>  
                    <div class=" col-sm-3 text-right"><a href="?pg=new_prescription&ptid=<?php echo $pat_user[0]->ref_code;?>" class="btn btn-success btn-sm me-2 mb-2">+ Add Prescriptions</a></div>
                    
                     <div class="col-xl-12">
                    <div class="card mb-4">
                   
                    <div class="card-body">
                    <div class="row">
                        
                  <?php
                        for($i=count($pres_days)-1; $i>=0; $i--){
                            $pday= $pres_days[$i];
                            ?>
                  <div class="col-sm-4">
                  <div class="card shadow-none bg-transparent border border-primary mb-3">
                    <div class="card-body">
                    <div class="row">
                   
                         <div class="col-sm-10">
                             <h5><?php echo $pday; ?></h5>
                             <?php
                             for($i2=0; $i2<count($pres_by_days[$pday]); $i2++){
                                 ?>
                      <p class="card-title txt-color-blue"><?php echo $pres_by_days[$pday][$i2]->drug_name; ?></p><hr>
                             <?php
                             }
                            ?>
                             
                             <a href="?pg=patient_medications&ptid=<?php echo $pat_user[0]->ref_code; ?>&pscid=<?php echo $pday; ?>" class="btn btn-info btn-sm">Open</a>
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

           
          