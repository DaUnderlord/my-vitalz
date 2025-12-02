
            <div class="container-xxl flex-grow-1 container-p-y">
             <h4>Edit Product</h4>
                <div class="row mt-4">
                    
               
                <div class="col-xl-12">
                  <div class="card mb-4">
                    <h5 class="card-header">Product Details</h5>
                    <div class="card-body">
                      <div>
                      
                          <form  method="post" enctype="multipart/form-data">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                             <?php
        if($my_products[0]->product_ref==NULL){
            $prod = $my_products[0];
        }else{
            $prod = $product_ref[0];
        }
    
    ?>
                              <input type = "hidden" name = "pid" value = "<?php echo $my_products[0]->id; ?>">
                                <div class="row">
                                <div class="col-sm-6">
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Product Name</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="text"
                                      name="product_nameed"
                                      class="form-control"
                                      placeholder="Product Name"
                                          required
                                          value="<?php echo $prod->name; ?>"
                                          <?php
        if($my_products[0]->product_ref!=NULL){
            echo "readonly";
        }
                                          ?>
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                  
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Category</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="text"
                                      name="product_categoryed"
                                      class="form-control"
                                      placeholder="Category"
                                          required
                                          value="<?php echo $prod->category; ?>"
                                          <?php
        if($my_products[0]->product_ref!=NULL){
            echo "readonly";
        }
                                          ?>
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                 
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Price</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="number"
                                      name="product_priceed"
                                      class="form-control"
                                      placeholder="Price"
                                          required
                                          value="<?php echo $my_products[0]->price; ?>"
                                          
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                 
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Description</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <textarea
                                      name="product_descriptioned"
                                      class="form-control"
                                      placeholder="Description"
                                             required
                                          <?php
        if($my_products[0]->product_ref!=NULL){
            echo "readonly";
        }
                                          ?>
                                             ><?php echo $prod->description; ?></textarea>
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                  
                                  
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Change Product Image</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="file"
                                      name="product_imageed"
                                      class="form-control"
                                          
                                          <?php
        if($my_products[0]->product_ref!=NULL){
            echo "disabled";
        }
                                          ?>
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                </div> 
                              <div class="col-sm-6">
                              <img src="assets/products/<?php echo $prod->photo; ?>" class="img col-sm-12" />
                              </div>
                                 
                               
                              <div class="modal-footer">
                                <a  class="btn btn-outline-secondary" href="?pg=store">
                                  Back
                                </a>
                                <button type="submit" class="btn btn-primary">Save</button>
                              </div>
                              </div>
                            </form>
                          
                      </div>
                    </div>
                  </div>
                
                </div>
               

              </div>
             
            </div>
            <!-- / Content -->

           
          