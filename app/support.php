
            <div class="container-xxl flex-grow-1 container-p-y">
             <h4>Support</h4>
                <div class="row mt-4">
                <div class="col-xl-12"> 
                    <button type="button" class="btn btn-info me-4 mb-2 text-white" data-bs-toggle="modal"
                          data-bs-target="#AddSupportTicket">
                              <span class="tf-icons bx bx-plus "></span>&nbsp; New Ticket
                            </button> 
                    </div>
                    
                <div class="col-xl-12">
                     <div class="card">
                <h5 class="card-header">My Tickets</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>S/N</th>
                        <th>Ticket ID</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $sn =1;
                        if(!empty($my_tickets)){
                        for($i=count($my_tickets)-1; $i>=0; $i--){
                        ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $my_tickets[$i]->ticket_id; ?></td>
                        <td><?php echo $my_tickets[$i]->subject; ?></td>
                        <td><?php echo $my_tickets[$i]->status; ?></td>
                        <td><?php echo $my_tickets[$i]->date; ?></td>
                        <td><a href="?pg=support_details&tkid=<?php echo $my_tickets[$i]->ticket_id; ?>" class="btn btn-info text-white">View</a></td>
                        
                      </tr>
                        <?php
                            $sn+=1;
                        }
                        }else{
                            
                            ?>
                            
                      <tr>
                        <td>No tickets</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                       
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
            <!-- / Content -->

           
          