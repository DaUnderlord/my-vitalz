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
               
                <div class="col-xl-12">
                    
                    <h4>Medication History</h4>
                     <div class="col-xl-4">
                    <a href="?pg=patient_medications&ptid=<?php echo $pat_user[0]->ref_code;?>&pscid=<?php echo date('d-M-Y', $prescription->date); ?>" class="btn btn-primary btn-sm me-2"><i class="bx bx-reply"></i> Back</a>
                    </div>
                        
                  <div class="card mb-4">
                    <h5 class="card-header"><?php echo $prescription->drug_name; ?></h5>
                    <div class="card-body">
                      <div>
                      <table class="table table-hover">
                   <thead>
                          <th>Date</th>
                          <th>Morning</th>
                          <th>Afternoon</th>
                          <th>Night</th>
                          </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        if(!empty($days)){
                            for($i=count($days)-1; $i>=0; $i--){
                        ?>
                      <tr>
                        <td><strong><?php echo $days[$i]; ?></strong></td>
                        <td><?php if(empty($morning[$days[$i]])){echo "--"; }else{ echo $morning[$days[$i]]; }?></td>
                        <td><?php if(empty($afternoon[$days[$i]])){echo "--"; }else{ echo $afternoon[$days[$i]]; }?></td>
                        <td><?php if(empty($night[$days[$i]])){echo "--"; }else{ echo $night[$days[$i]]; }?></td>
                        
                      </tr>
                     <?php
                            }
                        }else{
                                ?>
                        <td>No records</td>
                        <?php
                        }
                        ?>
                    </tbody>
                  </table>
                      </div>
                    </div>
                  </div>
                
                
                </div>
              </div>
             
            </div>
            <!-- / Content -->

           
          