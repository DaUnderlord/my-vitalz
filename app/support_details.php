            <div class="container-xxl flex-grow-1 container-p-y">
                  <h4>Ticket Information</h4>
                 <div class="card">
                    <div class="card-body">
             <div class="col-md-12 ">
             <div class="row">
            
                 <div class="col-md-7 mb-1">
                     <p>Requestor: <b><?php echo $user[0]->first_name." ".$user[0]->last_name;?></b></p>
                     <p>Ticket Id: <b><?php echo $my_tickets[0]->ticket_id;?></b></p>
                     <p>Subject: <b><?php echo $my_tickets[0]->subject;?></b></p>
                     <p>Priority: <b><?php echo $my_tickets[0]->priority;?></b></p>
                     
                 </div>
                  
                 <div class="col-md-5 mb-1">
                     <p>Status: <b><?php echo $my_tickets[0]->status;?></b></p>
                     <p>Submitted: <b><?php echo $my_tickets[0]->date; ?></b></p>
                     <p>Last Updated: <b><?php echo $my_tickets[0]->last_updated;?></b></p>
                 </div>
                  
                 <div class="col-md-4 mb-1">
                     <a href="" class="btn btn-primary  me-2">Reply</a>
                     
                 </div>
                  
                  </div>
                  </div>
                  </div>
                  </div>
                
                <div class="row mt-4">
               
                <div class="col-xl-12">
                    
                    <h4>Medication History</h4>
                        
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

           
          