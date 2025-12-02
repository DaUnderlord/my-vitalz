<?php
// Patient Messages & Notifications page
?>

<div class="row">
  <div class="col-12">
    <h5 class="mb-3">Messages &amp; Notifications</h5>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <?php if (empty($all_notifications)) { ?>
          <p class="text-muted mb-0">You have no messages yet.</p>
        <?php } else { ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($all_notifications as $msg) { ?>
              <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <strong>
                      <?php
                        if (isset($msg->title) && $msg->title !== '') {
                            echo $msg->title;
                        } else {
                            echo 'Message';
                        }
                      ?>
                    </strong><br>
                    <small class="text-muted">
                      <?php
                        if (isset($msg->date) && $msg->date !== '') {
                            // Some notifications store a UNIX timestamp, others a formatted string
                            if (is_numeric($msg->date)) {
                                echo \App\functions::format_date_time($msg->date);
                            } else {
                                echo $msg->date;
                            }
                        }
                      ?>
                    </small>
                    <p class="mb-0 mt-1">
                      <?php
                        if (isset($msg->message) && $msg->message !== '') {
                            echo $msg->message;
                        } elseif (isset($msg->description) && $msg->description !== '') {
                            echo $msg->description;
                        }
                      ?>
                    </p>
                  </div>
                  <div class="ms-2">
                    <?php if (isset($msg->link) && $msg->link) { ?>
                      <a href="<?php echo $msg->link; ?>" class="btn btn-sm btn-outline-primary">
                        Open
                      </a>
                    <?php } ?>
                  </div>
                </div>
              </li>
            <?php } ?>
          </ul>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
