            <div class="row">
              <div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Pending appointments</h6>
                  </div>
                  <div class="card-body">
                    <?php if(!empty($pending_appointments)){ ?>
                      <ul class="list-group list-group-flush">
                        <?php for($i=count($pending_appointments)-1; $i>=0; $i--){ ?>
                          <li class="list-group-item px-0">
                            <div class="d-flex align-items-start">
                              <div class="me-3">
                                <span class="badge bg-primary"><?php echo date('d M', $pending_appointments[$i]->start_time); ?></span>
                              </div>
                              <div class="flex-grow-1">
                                <div class="fw-semibold"><?php echo $appointment_doctor[$i]->first_name.' '.$appointment_doctor[$i]->last_name; ?></div>
                                <small class="text-muted"><?php echo date('D, d-M-Y', $pending_appointments[$i]->start_time); ?> • <?php echo date('h:i a', $pending_appointments[$i]->start_time).' - '.date('h:i a', $pending_appointments[$i]->end_time); ?></small>
                                <div class="mt-1">
                                  <?php
                                    $st = $pending_appointments[$i]->doc_accept;
                                    $label = 'Awaiting Doctor\'s Approval'; $chip='warning';
                                    if($st==='1'){ $label='Doctor Approved'; $chip='success'; }
                                    else if($st==='2'){ $label='Re-scheduled'; $chip='primary'; }
                                    else if($st==='3'){ $label='Rejected'; $chip='danger'; }
                                  ?>
                                  <span class="badge bg-<?php echo $chip; ?> me-2"><?php echo $label; ?></span>
                                  <?php if($pending_appointments[$i]->channel){ ?><span class="badge bg-light text-dark"><?php echo $pending_appointments[$i]->channel; ?></span><?php } ?>
                                </div>
                                <?php if($pending_appointments[$i]->address){ ?><div class="mt-1 small"><i class="bx bx-map me-1"></i><?php echo $pending_appointments[$i]->address; ?></div><?php } ?>
                              </div>
                            </div>
                          </li>
                        <?php } ?>
                      </ul>
                    <?php } else { ?>
                      <div class="alert alert-info mb-0">No pending appointments.</div>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Available Specialists</h6>
                  </div>
                  <div class="card-body">
                    <div class="row g-3">
                      <?php if(!empty($my_doctors)){ for($i=0; $i<count($my_doctors); $i++){ ?>
                        <div class="col-12">
                          <div class="d-flex align-items-center justify-content-between p-2 border rounded-3">
                            <div class="d-flex align-items-center">
                              <div class="avatar me-3">
                                <img src="../assets/<?php if($doctors_details[$i]->photo!=""){echo 'images/'.$doctors_details[$i]->photo;}else{ echo 'img/avatars/user.png'; } ?>" alt="Avatar" class="rounded-circle" />
                              </div>
                              <div>
                                <div class="fw-semibold"><?php echo $doctors_details[$i]->first_name.' '.$doctors_details[$i]->last_name; ?></div>
                                <small class="text-muted">Specialist</small>
                              </div>
                            </div>
                            <a href="?pg=doctor_page&d=<?php echo $doctors_details[$i]->ref_code; ?>" class="btn btn-sm btn-primary">Book</a>
                          </div>
                        </div>
                      <?php } } else { ?>
                        <div class="col-12"><div class="alert alert-light border">You currently have no doctor.</div></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>