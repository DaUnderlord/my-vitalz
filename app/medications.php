            <div class="row">
              <div class="col-12">
                <h5 class="mb-1">Medications</h5>
                <p class="text-muted mb-3">Track your daily medication intake and view history.</p>
              </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h6 class="mb-0">Prescriptions</h6>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                        <select class="form-select mb-3" name="period" required>
                          <option value="">Select period</option>
                          <option value="Morning">Morning</option>
                          <option value="Afternoon">Afternoon</option>
                          <option value="Night">Night</option>
                        </select>
                        
                        <p class="text-muted small mb-3">Mark medications as taken for the selected period.</p>
                        <ul class="list-group list-group-flush mb-3">
                            <?php
                        for($i=count($prescriptions)-1; $i>=0; $i--){
                            ?>
                          <li class="list-group-item px-0">
                            <div class="d-flex align-items-start">
                              <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" name="prescription_taken[]" value="<?php echo $prescriptions[$i]->id; ?>" <?php if($prescription_taken_today[$i]==1){echo "checked"; } ?> id="med_<?php echo $i; ?>">
                              </div>
                              <div class="flex-grow-1">
                                <label class="form-check-label fw-semibold" for="med_<?php echo $i; ?>"><?php echo $prescriptions[$i]->drug_name; ?></label>
                                <div class="small text-muted"><?php echo $prescriptions[$i]->drug_type; ?></div>
                                <div class="mt-1">
                                  <span class="badge bg-light text-dark me-1"><?php echo $prescriptions[$i]->dosage; ?></span>
                                  <span class="badge bg-light text-dark"><?php echo $prescriptions[$i]->frequency; ?></span>
                                </div>
                                <div class="mt-1 small text-muted">
                                  <div>Prescribed: <?php echo date("d-M-Y", $prescriptions[$i]->date); ?></div>
                                  <div>Doctor: <?php echo $prescription_doctor[$i]->first_name." ".$prescription_doctor[$i]->last_name; ?></div>
                                  <?php if(!empty($prescription_last_taken[$i]->period_taken)){ ?>
                                  <div>Last Taken: <?php echo $prescription_last_taken[$i]->period_taken." ".$prescription_last_taken[$i]->date; ?></div>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>
                          </li>
                      <?php
                        }
                            ?>
                        </ul>
                      <button type="submit" class="btn btn-primary me-4 mb-2">
                              Save
                            </button>  
                        </form>  
                </div>
                    </div>
                </div>

                <div class="col-xl-6">
                  <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h6 class="mb-0">History</h6>
                    </div>
                    <div class="card-body">
                      <div>
                      <table class="table table-sm table-hover align-middle">
                   
                    <tbody class="table-border-bottom-0">
                        <?php
                        if(!empty($medications)){
                            for($i=count($medications)-1; $i>=0; $i--){
                        ?>
                      <tr>
                        <td><strong><?php echo $taken_prescription[$i]->drug_name; ?></strong></td>
                        <td><?php echo $medications[$i]->period_taken; ?></td>
                        <td><?php echo $medications[$i]->date; ?></td>
                        
                      </tr>
                     <?php
                            }
                        }
                                ?>
                    </tbody>
                  </table>
                      </div>
                    </div>
                  </div>
                
                </div>
              </div>