
            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> Account</h4>

                 <div class="row">
                <div class="col-md-12">
                    
              <div class="row">
                  
                <div class="col-xl-12">
                    
               
                  <div class="nav-align-top mb-4">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link active"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-profile"
                          aria-controls="navs-profile"
                          aria-selected="true"
                        >
                          Profile
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-wallet"
                          aria-controls="navs-wallet"
                          aria-selected="false"
                        >
                          Wallet
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-cards"
                          aria-controls="navs-cards"
                          aria-selected="false"
                        >
                          Cards
                        </button>
                      </li>
                     
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="navs-profile" role="tabpanel">
                          <h5 class="card-header">Personal Details</h5>
                    <!-- Account -->
                    <div class="card-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img
                          src="../assets/<?php if($user[0]->photo!=""){echo "images/".$user[0]->photo;}else{ echo 'img/avatars/user.png'; } ?>"
                          alt="user-avatar"
                          class="d-block rounded"
                          height="100"
                          width="100"
                          id="uploadedAvatar"
                        />
                        <div class="button-wrapper">
                            <form  method="POST" enctype="multipart/form-data" id="form_pic">
                            <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>"> 
                          <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Upload new photo</span>
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input
                              type="file"
                              id="upload"
                              class="account-file-input"
                                   name="upload_profile"
                              hidden
                              accept="image/png, image/jpeg" onchange="$('#form_pic').submit();"
                            />
                          </label>
                         

                          <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                            </form>
                        </div>
                      </div>
                    </div>
                    <hr class="my-1" />
                  
                      <form id="formAccountSettings" method="POST" class="my-4">
                       <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>"> 
                       <input type="hidden" name="action" value="update_profile">
                          <div class="row">
                            
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">First Name</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="first_name"
                              value="<?php echo $user[0]->first_name; ?>"
                              required
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input class="form-control" type="text" name="last_name" id="lastName" value="<?php echo $user[0]->last_name; ?>" required />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input
                              class="form-control"
                              type="text"
                              id="email"
                              name="email"
                              value="<?php echo $user[0]->email; ?>"
                              placeholder="john.doe@example.com"
                                   disabled
                            />
                          </div>
                         
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="phoneNumber">Phone Number</label>
                            <div class="input-group input-group-merge">
                              <span class="input-group-text">NG (+234)</span>
                              <input
                                type="text"
                                id="phoneNumber"
                                name="phone"
                                class="form-control"
                                placeholder="202 555 0111"
                                     value="<?php echo $user[0]->phone; ?>"
                                     required
                              />
                            </div>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo $user[0]->address; ?>" required />
                          </div>  
                            
                            <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">About me</label>
                                <textarea type="text" class="form-control" id="about" name="about" placeholder="Write about yourself"  required ><?php echo $user[0]->about; ?></textarea>
                          </div>
                              
                          <div class="mb-3 col-md-6">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select" id="state" name="state" required>
                              <?php $states = [
                                'Abia','Abuja','Adamawa','Akwa Ibom','Anambra','Bauchi','Bayelsa','Benue','Borno','Cross River',
                                'Delta','Ebonyi','Edo','Ekiti','Enugu','Gombe','Imo','Jigawa','Kaduna','Kano','Katsina','Kebbi',
                                'Kogi','Kwara','Lagos','Nasarawa','Niger','Ogun','Ondo','Osun','Oyo','Plateau','Rivers','Sokoto',
                                'Taraba','Yobe','Zamfara'
                              ]; ?>
                              <option value="">Select State</option>
                              <?php foreach($states as $st){ ?>
                                <option value="<?php echo $st; ?>" <?php if(strtolower($user[0]->state)==strtolower($st)){ echo 'selected'; } ?>><?php echo $st; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input class="form-control" type="text" id="city" name="city" placeholder="Enter City" value="<?php echo $user[0]->city; ?>" required />
                          </div>
                         
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="country">Country</label>
                            <select name="country" class="select2 form-select" required>
                              <option value="">Select</option>
                              <option value="Nigeria" <?php if($user[0]->country=="Nigeria"){echo "selected";} ?>>Nigeria</option>
                             
                            </select>
                          </div>
                              
                              
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="country">Profile Status</label>
                            <select name="profile_status" class="select2 form-select" >
                              <option value="" <?php if($user[0]->public==NULL){echo "selected";} ?>>Private</option>
                              <option value="1" <?php if($user[0]->public==1){echo "selected";} ?>>Public</option>
                             
                            </select>
                          </div>
                          
                        </div>
                        <div class="mt-2">
                          <button type="submit" class="btn btn-primary me-2">Save details</button>
                         
                        </div>
                      </form>
                      </div>
                        
                      <div class="tab-pane fade" id="navs-wallet" role="tabpanel">
                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="../assets/img/icons/unicons/cc-primary.png" alt="chart success" class="rounded">
                            </div>
                            <div class="dropdown">
                              <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="javascript:void(0);">Fund wallet</a>
                              </div>
                            </div>
                          </div>
                          <span class="fw-semibold d-block mb-1">Balance</span>
                          <h3 class="card-title mb-2">N<?php echo number_format($user[0]->wallet,2); ?></h3>
                          
                        </div>
                      </div>
                    </div>
                      </div>
                        
                      <div class="tab-pane fade" id="navs-cards" role="tabpanel">
                     
                      </div>
                        
                    </div>
                  </div>
               
                    </div>
            
              </div>
            </div>
            <!-- / Content -->

           
          