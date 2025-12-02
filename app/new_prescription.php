
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
                     <a href="?pg=patient_medications&ptid=<?php echo $pat_user[0]->ref_code;?>" class="btn btn-primary btn-sm me-2">Medications</a>
                     <a href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>" class="btn btn-info btn-sm me-2">Readings History</a>
                 </div>
                  
                  </div>
                  </div>
                  </div>
                  </div>
                 
                <div class="row mt-4">
                      
                 <div class="col-md-12">
                <div class="row">
                    <h4>New Prescriptions</h4>
                    
<div class="col-xl-12">
                    <div class="card mb-4">
                   
                    <div class="card-body">
                        
                 <form class="form" method="post">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                         
                           
                                <div class="row" id="prescriptions">
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Drug Name</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <input
                                      type="text"
                                      name="drug_name[]"
                                      class="form-control"
                                      placeholder="Drug name"
                                          required
                                    />
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                     
                           
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Drug Type</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <select
                                      name="drug_type[]"
                                      class="form-control"
                                          required >
                                       <option value="tablets">Tablets</option>
                                       <option value="capsules">Capsules</option>
                                       <option value="syrup">Syrup</option>
                                          
                                          </select>
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                     
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Quantity</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <input
                                      type="text"
                                      name="quantity[]"
                                      class="form-control"
                                      placeholder="Quantity"
                                          required
                                    />
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                     
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Dosage</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <input
                                      type="text"
                                      name="dosage[]"
                                      class="form-control"
                                      placeholder="Dosage"
                                          required
                                    />
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                     
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Frequency</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <input
                                      type="text"
                                      name="frequency[]"
                                      class="form-control"
                                      placeholder="Frequency"
                                         required 
                                    />
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                     
                                <div class="col-sm-4">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Additional Information</label>
                                      <div class="row">
                                      <div class="col-sm-12">
                                        
                                   <textarea
                                      type="text"
                                      name="additional[]"
                                      class="form-control"
                                      placeholder="Any additional details"
                                          
                                             ></textarea>
                                  </div>
                                          
                                   
                                  </div>
                                  </div>
                                </div> 
                                    <hr>
                     </div>
                              <div class="modal-footer">
                                
                                <button type="button" class="btn btn-success" onclick="addPrescription()">+ Add Drug</button>
                                <button type="submit" class="btn btn-primary">Send Prescription</button>
                              </div>
                            </form>
                        
                </div>
                    </div>
                </div>

                </div>
                </div>
                

              </div>
             
            </div>
            <!-- / Content -->

           <script>
               var pr = 1;
function addPrescription(){
    var pres = '<div class="row pe-0" id="pr'+pr+'">'+
        '<div class="col-sm-12" style="text-align:right;"><i class="bx bx-trash mb-2" onclick="$(\'#pr'+pr+'\').remove()"></i></div>'+
    '<div class="col-sm-4">'+
                                  '<div class="col mb-3">'+
                                    '<label for="nameBackdrop" class="form-label">Drug Name</label>'+
                                      '<div class="row">'+
                                      '<div class="col-sm-12">'+
                                        
                                   '<input'+
                                      ' type="text"'+
                                      ' name="drug_name[]"'+
                                      ' class="form-control"'+
                                      ' placeholder="Drug name"'+
                                          ' required'+
                                    ' />'+
                                  ' </div>'+
                                          
                                   
                                  ' </div>'+
                                  ' </div>'+
                                ' </div>'+ 
                     
                           
                                ' <div class="col-sm-4">'+
                                  ' <div class="col mb-3">'+
                                    ' <label for="nameBackdrop" class="form-label">Drug Type</label>'+
                                      ' <div class="row">'+
                                      ' <div class="col-sm-12">'+
                                        
                                   ' <select'+
                                      ' name="drug_type[]"'+
                                      ' class="form-control"'+
                                          ' required >'+
                                       ' <option value="tablets">Tablets</option>'+
                                       ' <option value="capsules">Capsules</option>'+
                                       ' <option value="syrup">Syrup</option>'+
                                          
                                          ' </select>'+
                                  ' </div>'+
                                          
                                   
                                  ' </div>'+
                                  ' </div>'+
                                ' </div>'+ 
                     
                                ' <div class="col-sm-4">'+
                                  ' <div class="col mb-3">'+
                                    ' <label for="nameBackdrop" class="form-label">Quantity</label>'+
                                      ' <div class="row">'+
                                      ' <div class="col-sm-12">'+
                                        
                                   ' <input'+
                                      ' type="text"'+
                                      ' name="quantity[]"'+
                                      ' class="form-control"'+
                                      ' placeholder="Quantity"'+
                                          ' required'+
                                    ' />'+
                                  ' </div>'+
                                          
                                   
                                  ' </div>'+
                                  ' </div>'+
                                ' </div> '+
                     
                                ' <div class="col-sm-4">'+
                                  ' <div class="col mb-3">'+
                                    ' <label for="nameBackdrop" class="form-label">Dosage</label>'+
                                      ' <div class="row">'+
                                      ' <div class="col-sm-12">'+
                                        
                                   ' <input'+
                                      ' type="text"'+
                                      ' name="dosage[]"'+
                                      ' class="form-control"'+
                                      ' placeholder="Dosage"'+
                                          'required'+
                                    ' />'+
                                  ' </div>'+
                                          
                                   
                                  ' </div>'+
                                  ' </div>'+
                                ' </div> '+
                     
                                ' <div class="col-sm-4">'+
                                  ' <div class="col mb-3">'+
                                    ' <label for="nameBackdrop" class="form-label">Frequency</label>'+
                                      ' <div class="row">'+
                                      ' <div class="col-sm-12">'+
                                        
                                   ' <input'+
                                      ' type="text"'+
                                      ' name="frequency[]"'+
                                      ' class="form-control"'+
                                      ' placeholder="Frequency"'+
                                         ' required'+ 
                                    ' />'+
                                  ' </div>'+
                                          
                                   
                                  ' </div>'+
                                  ' </div>'+
                                ' </div> '+
                     
                                ' <div class="col-sm-4">'+
                                  ' <div class="col mb-3">'+
                                    ' <label for="nameBackdrop" class="form-label">Additional'+ ' Information</label>'+
                                      ' <div class="row">'+
                                      ' <div class="col-sm-12">'+
                                        
                                   ' <textarea'+
                                      ' type="text"'+
                                      ' name="additional[]"'+
                                      ' class="form-control"'+
                                      ' placeholder="Any additional details"'+
                                          
                                             ' ></textarea>'+
                                  ' </div>'+
                                          
                                   
                                  ' </div>'+
                                  ' </div>'+
                               ' </div> '+
                                    ' <hr>'+
         ' </div> ';
    
    $('#prescriptions').append(pres);
    pr +=1;
}
</script>
          