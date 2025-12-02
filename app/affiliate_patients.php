
            <div class="row">
              <div class="col-12">
                <h5 class="mb-1">Affiliates</h5>
                <p class="text-muted mb-3">Manage your network of doctors, pharmacies, and hospitals.</p>
              </div>
            </div>
            <div class="row">
                <div class="col-xl-7">
                    <div class="card mb-4">
                    <div class="card-header">
                      <h6 class="mb-0">My Affiliates</h6>
                    </div>
                    <div class="card-body">
                  <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills mb-3" role="tablist">
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link active"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-pills-top-requests"
                          aria-controls="navs-pills-top-requests"
                          aria-selected="true"
                        >
                         Requests
                        </button>
                      </li> 
                        
                        <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-pills-top-specialists"
                          aria-controls="navs-pills-top-specialists"
                          aria-selected="false"
                        >
                         Specialists
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-pills-top-pharmacies"
                          aria-controls="navs-pills-top-pharmacies"
                          aria-selected="false"
                        >
                          Pharmacies
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-pills-top-hospitals"
                          aria-controls="navs-pills-top-hospitals"
                          aria-selected="false"
                        >
                          Hospitals
                        </button>
                      </li>
                    </ul>
                    <div class="tab-content p-0" style="box-shadow:none;">
                      <div class="tab-pane fade show active" id="navs-pills-top-requests" role="tabpanel">
                     
            <ul class="list-unstyled mb-0">
              <?php
                if(!empty($my_requests)){
                    for($i=0; $i<count($my_requests); $i++){
                
                ?>
              <li class="mb-3">
                  <form method="post" id="approve_<?php echo $request_details[$i]->ref_code; ?>">
                   <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                   <input type = "hidden" name = "approve_affliate" value = "<?php echo $my_requests[$i]->id; ?>">
                  </form>
                  
                  <form method="post" id="decline_<?php echo $request_details[$i]->ref_code; ?>">
                   <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                   <input type = "hidden" name = "decline_affliate" value = "<?php echo $my_requests[$i]->id; ?>">
                  </form>
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-start">
                    <div class="avatar me-3">
                      <img src="../assets/<?php if($request_details[$i]->photo!=""){echo "images/".$request_details[$i]->photo;}else{ echo 'img/avatars/user.png'; } ?>" alt="Avatar" class="rounded-circle">
                    </div>
                    <div class="me-2">
                      <h6 class="mt-2"><?php echo $request_details[$i]->first_name; ?> <?php echo $request_details[$i]->last_name; ?></h6>
                    </div>
                  </div>
                  <div class="ms-auto">
                    <a href="#" onclick="approve_request('approve_<?php echo $request_details[$i]->ref_code; ?>')" class="btn btn-success btn-sm">Approve</a>
                    <a  href="#" onclick="decline_request('decline_<?php echo $request_details[$i]->ref_code; ?>')" class="btn btn-danger btn-sm">Decline</a>
                  </div>
                </div>
              </li>
                <?php
                    }
                }else{
                        ?>
                <li>You currently have no requests</li>
                <?php
                }
                ?>
            </ul>
       
                      </div>
                        
                        <div class="tab-pane fade" id="navs-pills-top-specialists" role="tabpanel">
                     
            <ul class="list-unstyled mb-0">
              <?php
                if(!empty($my_doctors)){
                    for($i=0; $i<count($my_doctors); $i++){
                
                ?>
              <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-start">
                    <div class="avatar me-3">
                      <img src="../assets/<?php if($doctors_details[$i]->photo!=""){echo "images/".$doctors_details[$i]->photo;}else{ echo 'img/avatars/user.png'; } ?>" alt="Avatar" class="rounded-circle">
                    </div>
                    <div class="me-2">
                      <h6 class="mt-2"><?php echo $doctors_details[$i]->first_name; ?> <?php echo $doctors_details[$i]->last_name; ?></h6>
                    </div>
                  </div>
                  <div class="ms-auto">
                     <a  href="?pg=doctor_page&d=<?php echo $doctors_details[$i]->ref_code; ?>" class="btn btn-info btn-sm">View Profile</a>
                  </div>
                </div>
              </li>
                <?php
                    }
                }else{
                        ?>
                <li>You currently have no doctor</li>
                <?php
                }
                ?>
            </ul>
       
                      </div>
                      <div class="tab-pane fade" id="navs-pills-top-pharmacies" role="tabpanel">
                            
            <ul class="list-unstyled mb-0">
             <?php
                if(!empty($my_pharmacy)){
                    for($i=0; $i<count($my_pharmacy); $i++){
                
                ?>
              <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-start">
                    <div class="avatar me-3">
                      <img src="../assets/<?php if($pharmacy_details[$i]->photo!=""){echo "images/".$pharmacy_details[$i]->photo;}else{ echo 'img/avatars/user.png'; } ?>" alt="Avatar" class="rounded-circle">
                    </div>
                    <div class="me-2">
                      <h6 class="mt-2"><?php echo $pharmacy_details[$i]->first_name; ?> <?php echo $pharmacy_details[$i]->last_name; ?></h6>
                    </div>
                  </div>
                  <div class="ms-auto">
                    
                  </div>
                </div>
              </li>
                <?php
                    }
                }else{
                        ?>
                <li>You currently have no pharmacy</li>
                <?php
                }
                ?>
            </ul>
       
                      </div>
                      <div class="tab-pane fade" id="navs-pills-top-hospitals" role="tabpanel">
                            
            <ul class="list-unstyled mb-0">
             <?php
                if(!empty($my_hospital)){
                    for($i=0; $i<count($my_hospital); $i++){
                
                ?>
              <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-start">
                    <div class="avatar me-3">
                      <img src="../assets/<?php if($hospital_details[$i]->photo!=""){echo "images/".$hospital_details[$i]->photo;}else{ echo 'img/avatars/user.png'; } ?>" alt="Avatar" class="rounded-circle">
                    </div>
                    <div class="me-2">
                      <h6 class="mt-2"><?php echo $hospital_details[$i]->first_name; ?> <?php echo $hospital_details[$i]->last_name; ?></h6>
                    </div>
                  </div>
                  <div class="ms-auto">
                    
                  </div>
                </div>
              </li>
                <?php
                    }
                }else{
                        ?>
                <li>You currently have no hospital</li>
                <?php
                }
                ?>
            </ul>
       
                      </div>
                    </div>
                  </div>
                </div>
                    </div>
                </div>

                <div class="col-xl-5">
                
                  <?php
                if($user[0]->referral==""){
                  ?>
                  <div class="card mb-4">
                    <h5 class="card-header">Search for Specialists</h5>
                    <div class="card-body">
                      <div>
                        <label for="defaultFormControlInput" class="form-label">Enter Doctor's Phone Number</label>
                        <input
                          type="text"
                          class="form-control"
                          id="pname"
                          placeholder="Enter doctor's phone number"
                          aria-describedby="defaultFormControlHelp"
                        onkeyup="search_doctor();"
                        />
                        <div id="sresults" class="form-text mt-3">
                         
                        </div>
                          <hr>
                        <div id="public_doctors" class="form-text mt-3">
                            
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
            <!-- / Content -->

                   
          <script>
function approve_request(form_id) {
  let text = "You are about to approve this affiliate request";
  if (confirm(text) == true) {
    $("#"+form_id).submit();
  } else {
    text = "You canceled!";
  }
}
              
function decline_request(form_id) {
  let text = "You are about to decline this affiliate request";
  if (confirm(text) == true) {
    $("#"+form_id).submit();
  } else {
    text = "You canceled!";
  }
}
              
              
</script>   
          