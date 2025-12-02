
            <div class="container-xxl flex-grow-1 container-p-y">
             <h4>Store</h4>
                <div class="row mt-4">
                <div class="col-xl-12"> 
                    <button type="button" class="btn btn-info me-4 mb-2 text-white" data-bs-toggle="modal"
                          data-bs-target="#AddProductModal">
                              <span class="tf-icons bx bx-plus "></span>&nbsp; Add Product
                            </button>  
                    
                    <button type="button" class="btn btn-info me-4 mb-2 text-white" data-bs-toggle="modal"
                          data-bs-target="#AddStoreProductModal">
                              <span class="tf-icons bx bx-plus "></span>&nbsp; Add Product From Store
                            </button> 
                    </div>
                    
                <div class="col-xl-12">
                     <div class="card">
                <h5 class="card-header">My Products</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Actions</th>
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        if(!empty($my_products)){
                        for($i=count($my_products)-1; $i>=0; $i--){
                            if($my_products[$i]->product_ref==NULL){
                        ?>
                      <tr>
                        <td><img src='assets/products/<?php echo $my_products[$i]->photo; ?>' width='50px' height="50px"></td>
                        <td><?php echo $my_products[$i]->name; ?></td>
                        <td><?php echo $my_products[$i]->sku; ?></td>
                        <td>N<?php echo number_format($my_products[$i]->price); ?></td>
                        <td><?php echo $my_products[$i]->category; ?></td>
                        <td><?php echo $my_products[$i]->date; ?></td>
                        <td><a class="btn btn-info text-white" href="?pg=editproduct&pd=<?php echo $my_products[$i]->id; ?>">Edit</a></td>
                        
                      </tr>
                        <?php
                        
                        }else{
                            ?>
                      <tr>
                        <td><img src='assets/products/<?php echo $product_ref[$i]->photo; ?>' width='50px' height="50px"></td>
                        <td><?php echo $product_ref[$i]->name; ?></td>
                        <td><?php echo $product_ref[$i]->sku; ?></td>
                        <td>N<?php echo number_format($my_products[$i]->price); ?></td>
                        <td><?php echo $product_ref[$i]->category; ?></td>
                        <td><?php echo $product_ref[$i]->date; ?></td>
                        <td><a class="btn btn-info text-white" href="?pg=editproduct&pd=<?php echo $my_products[$i]->id; ?>">Edit</a></td>
                        
                      </tr>
                        <?php
                        }
                        }
                        }else{
                            
                            ?>
                            
                      <tr>
                        <td>No products</td>
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

           
          