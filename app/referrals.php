
            <div class="row">
              <div class="col-12">
                <h5 class="mb-1">Referrals</h5>
                <p class="text-muted mb-3">Invite friends and earn rewards.</p>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 mb-4">
                <div class="card">
                  <div class="card-body text-center">
                    <div class="mb-2"><i class="bx bx-gift bx-lg text-primary"></i></div>
                    <h3 class="text-primary mb-1">₦<?php echo number_format($user[0]->ref_bonus,2); ?></h3>
                    <div class="fw-semibold">Referral Bonus</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h6 class="mb-0">Invite friends</h6>
                  </div>
                  <div class="card-body">
                    <label for="referralPhone" class="form-label">Phone numbers</label>
                    <input type="text" class="form-control" id="referralPhone" placeholder="Enter phone numbers" />
                    <div class="form-text">Enter phone numbers to send invites.</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="mb-0">My Referrals</h6>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-sm table-hover align-middle">
                    <thead>
                      <tr>
                        <th>User</th>
                        <th>Date</th>
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        if(!empty($my_referrals)){
                            for($i=count($my_referrals)-1; $i>=0; $i--){
                        ?>
                      <tr>
                        <td><strong><?php echo $my_referrals[$i]->first_name." ".$my_referrals[$i]->last_name; ?></strong></td>
                        <td><?php echo $my_referrals[$i]->date; ?></td>
                        
                      </tr>
                     <?php
                            }
                        }else{
                            
                                ?>
                         <tr>
                        <td>No referrals</td>
                       
                      </tr>
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