
            <div class="row">
              <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div>
                    <h5 class="mb-1">Shop</h5>
                    <p class="text-muted mb-0">Browse health devices and medications.</p>
                  </div>
                  <a href="?pg=checkout" class="btn btn-primary"><i class="bx bx-cart me-1"></i> Checkout</a>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12">
                <div class="d-flex gap-2 align-items-center">
                  <a href="" class="btn btn-sm btn-outline-primary">Devices</a>
                  <a href="" class="btn btn-sm btn-outline-primary">Drugs</a>
                  <div class="ms-auto" style="max-width:300px;">
                    <input class="form-control form-control-sm" type="text" placeholder="Search products...">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
                    
                     <?php
                        if(!empty($all_products)){
                        for($i=count($all_products)-1; $i>=0; $i--){
                            if($all_products[$i]->product_ref==NULL){
                        ?>
                   <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                      <div class="card h-100">
                        <img src="assets/products/<?php echo $all_products[$i]->photo; ?>" class="card-img-top" alt="<?php echo $all_products[$i]->name; ?>" style="height:180px;object-fit:cover;">
                        <div class="card-body d-flex flex-column">
                          <h6 class="card-title mb-2"><?php echo $all_products[$i]->name; ?></h6>
                          <div class="mb-3">
                            <span class="h5 text-primary mb-0">₦<?php echo number_format($all_products[$i]->price); ?></span>
                          </div>
                          <button class="btn btn-primary btn-sm w-100 mt-auto" onclick="addToCart(<?php echo $all_products[$i]->id; ?>, 1, 'btn<?php echo $all_products[$i]->id; ?>')" id="btn<?php echo $all_products[$i]->id; ?>"><i class="bx bx-cart me-1"></i> Add to Cart</button>
                        </div>
                      </div>
                    </div>
                    
                     <?php
                        
                        }else{
                                ?>
                    
                      <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                      <div class="card h-100">
                        <img src="assets/products/<?php echo $product_ref[$i]->photo; ?>" class="card-img-top" alt="<?php echo $product_ref[$i]->name; ?>" style="height:180px;object-fit:cover;">
                        <div class="card-body d-flex flex-column">
                          <h6 class="card-title mb-2"><?php echo $product_ref[$i]->name; ?></h6>
                          <div class="mb-3">
                            <span class="h5 text-primary mb-0">₦<?php echo number_format($all_products[$i]->price); ?></span>
                          </div>
                          <button class="btn btn-primary btn-sm w-100 mt-auto" onclick="addToCart(<?php echo $all_products[$i]->id; ?>, 1, 'btn<?php echo $all_products[$i]->id; ?>')" id="btn<?php echo $all_products[$i]->id; ?>"><i class="bx bx-cart me-1"></i> Add to Cart</button>
                        </div>
                      </div>
                    </div>
                    <?php
                            }
                        }}
                            ?>
            </div>