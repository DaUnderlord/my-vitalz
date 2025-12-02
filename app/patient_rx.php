<div class="row">
  <div class="col-12">
    <h5 class="mb-1">My Prescriptions</h5>
    <p class="text-muted mb-3">View prescriptions sent by your doctors and fulfilled by pharmacies.</p>
    <?php if(empty($rx_list)){ ?>
      <div class="alert alert-light border d-flex align-items-center"><i class="bx bx-info-circle me-2"></i> No prescriptions yet.</div>
    <?php } else { ?>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle">
            <thead>
              <tr>
                <th>Rx ID</th>
                <th>Doctor</th>
                <th>Pharmacy</th>
                <th>Status</th>
                <th>Medications</th>
                <th>Total</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($rx_list as $rx){ ?>
                <tr>
                  <td>#<?php echo $rx->id; ?></td>
                  <td><?php echo $rx->doctor_name ?? '-'; ?></td>
                  <td><?php echo $rx->pharmacy_name ?? '-'; ?></td>
                  <td>
                    <span class="badge bg-<?php echo ($rx->status??'pending')==='delivered'?'success':(($rx->status??'pending')==='pending'?'warning':'primary'); ?>">
                      <?php echo ucfirst($rx->status ?? 'pending'); ?>
                    </span>
                  </td>
                  <td>
                    <?php if(!empty($rx->medications)){ ?>
                      <ul class="list-unstyled mb-0">
                        <?php foreach($rx->medications as $m){ ?>
                          <li><?php echo $m->medication_name; ?> <small class="text-muted">(<?php echo $m->dosage; ?>, <?php echo $m->frequency; ?>, <?php echo $m->duration; ?>)</small></li>
                        <?php } ?>
                      </ul>
                    <?php } else { echo '-'; } ?>
                  </td>
                  <td><?php echo isset($rx->total_amount)? number_format($rx->total_amount,2):'-'; ?></td>
                  <td><?php echo date('M d, Y', strtotime($rx->created_at)); ?></td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <a href="/dashboard?pg=messages&partner=<?php echo $rx->doctor_id; ?>" class="btn btn-outline-primary">Message Doctor</a>
                      <?php if(($rx->status ?? 'pending')!='delivered'){ ?>
                        <a href="#" class="btn btn-primary">Request Refill</a>
                      <?php } ?>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
