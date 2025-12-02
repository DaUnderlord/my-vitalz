<?php
namespace App;

use DB;
use Cookie;
use Illuminate\Http\Response;
use App\products;

class Cart
    {
	
     
    	//Add a product order to our COOKIE/JSON based cart.
     
    	public function AddProduct($productid, $qty)
    	{
    	
    		//Checks to see if cart already exists.
    		
    		if (Cookie::get('cartno')!="")
    		{
    			
    			//JSON content is turned back into array.
    			
    			$cart = json_decode(Cookie::get('cartno'));	
    			
    		}
    		else
    		{
    		
    			$cart = array();	
    			
    		}
    		
    		//Add product id and quantity desired to cart.
     
    		$cart[] = array($productid, $qty);
    		
    		//Encode cart array back into JSON.
    		
    		$createcart = json_encode($cart);
    		
            
            $response = new Response("true");
      $response->withCookie(cookie('cartno', $createcart, 120));
      return $response;
    		
    	}
    	
    	//Displays our Cart to the page.
    	
    	public function DisplayCart()
    	{
    		
    		if (Cookie::get('cartno')!="")
    		{    		
                $cart = Cookie::get('cartno');
    		
    		
    			$product = json_decode($cart);
    			
    			//Loop through Cart array to display relevant Cart info.
                
                 $shopping_cart = "<table class='table table-striped' >
                    <tr>
                    <thead>
                      
                <th>S/N</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th style='text-align:right;'>Subtotal</th>
                <th></th>
                                    </thead>
                    </tr>";
    			$total = 0;
    			$sn = 1;
    			
				for($i=count($product)-1; $i>=0; $i--)
    			{
    			
    			/*	echo '<p>';
    				echo $order;
    				echo $product[0];
    				echo $product[1];
                    echo $product[2];
    				echo '</p>'; */
                    
                 $pd = new Products($product[$i][0]);
                    
                    $shopping_cart .=  "<tr>
                    
                    <td>".$sn."</td>
                    <td>".$pd->productName()."</td>
                    <td>
                <form method='get' name='form".$i."' id='form".$i."' >
                <input type='hidden' value='".$i."' name='cartid' id='cartid".$i."'  >
                <input type='number' min='1' value='".$product[$i][1]."' name='qty' id='qty".$i."' class='vertical-spin form-control' style='padding:1px;' onchange=\"$('#form".$i."').submit()\">
                <button class='btn' type='submit' style='padding:1px; color:green; background:none;' id='upct".$i."'>Update</button>
                </form>
                
                 <script>
            $('#form".$i."').submit(function(event) {
 
$('#upct".$i."').empty();
 $('#upct".$i."').append(\"Updating...\");
  // Stop form from submitting normally
  event.preventDefault();
 
  // Get some values from elements on the page:
  var 
    qty = $( this ).find( \"input[name='qty']\" ).val();
    cartid = $( this ).find( \"input[name='cartid']\" ).val();
 
      
 //   url = $( this ).attr( \"action\" );
    
    
 
  // Send the data using post
  var posting = $.get( '/update-cart', { 
      qty: qty,
      pd:cartid } );
 
  // Put the results in a div
  posting.done(function( data ) {
 
      $('#shoppingcart').load('/show-cart');
	  
  });
    
});
    
    </script>
    
                </td>
                     <td>N".number_format($pd->price(),2)."</td>
                     <td align='right'>N".number_format(intval($product[$i][1])*$pd->price(),2)." </td>
                     
                    <td> 
                    
                <form method='post' class='pull-right' id='remvitem".$i."'>
                    <input type='hidden' name='removeitem' value='".$i."'>
                <button class='btn' type='submit' style='padding:1px; color:#000; background:none;' title='Remove from cart'><i class='bx bx-trash'></i></button>
                    
                </form>
                   
                <script>
            $('#remvitem".$i."').submit(function(event) {
 
  // Stop form from submitting normally
  event.preventDefault();
 
  // Get some values from elements on the page:
  var 
    item = $( this ).find( \"input[name='removeitem']\" ).val();
 
      
 //   url = $( this ).attr( \"action\" );
    
    
 
  // Send the data using post
  var posting = $.get( '/remove-from-cart', { 
      pd: item } );
 
  // Put the results in a div
  posting.done(function( data ) {
      $('#shoppingcart').load('/show-cart');
  });
    
});
    
    </script>   
               
              
                    </td>
                    </tr>";
                    
                   $total += (intval($product[$i][1])*$pd->price());
    				$sn += 1;
    			
				}
                
                $shopping_cart .=  "<tr>
                <td></td>
                <td></td>
                <td></td>
                    <td><h3>Total</h3></td>
                        <td colspan='' align='right'><h4><b>N".number_format($total,2)."</b></h4></td>
                        <td></td>
                        
                    </tr>
                    
                    </table>
                
              ";
    			
    		}
    		else
    		{
    		
    			$shopping_cart = "<table class='table table-striped' >
                    <tr>
                    <thead>
                      
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
                <th></th>
                                    </thead>
                    </tr><tr><td colspan='5'>No Products In Cart</td></tr></table>"; 	
    			
    		}
    		
    		return $shopping_cart;
    	}
    	
    	//Remove product order from Cart.
    	
    	public function RemoveProduct($orderid)
    	{
    		
    		$cart = Cookie::get('cartno');
    		
    		//JSON content is turned back into array.
    		
    		$product = json_decode($cart);
    		
    		$newcart = array();
    		
    	//	foreach ($products as $order => $product)
				for($i=0; $i<count($product); $i++)
    		{
    			
    			//Check to see if $products key ($order) matches the $products key we wish to remove.
    			
    			if($i != $orderid)
    			{
    			
    				//If result is false add product order to new array.
    				
    				$newcart[] = array($product[$i][0], $product[$i][1]);
    				
    			}	
    			
    		}
    		
                $cprdd = count($newcart);

                if($cprdd<1){
              
                    $response = new Response("true");
      $response->withCookie(Cookie::forget('cartno'));
      return $response;
                    }else{
            
    		//Encode new Cart array into JSON.
    		
    		$createcart = json_encode($newcart);
    		
    	
            $response = new Response("true");
      $response->withCookie(cookie('cartno', $createcart, 120));
      return $response;
}
    		
    	}
    
    function updatecart($id, $qty){
        
	$cart = Cookie::get('cartno');
     
    		
    		//JSON content is turned back into array.
    		
    		$product = json_decode($cart);
    		
    		$newcart = array();
    		
    		//foreach ($products as $order => $product)
				for($i=0; $i<count($product); $i++)
    		{
    			
    			//Check to see if $products key ($order) matches the $products key we wish to remove.
    			
    			if($i == $id)
    			{
    			
    				//If result is false add product order to new array.
    				
    				$newcart[] = array($product[$i][0], $qty);
    				
    			}	else{
                    
                    $newcart[] = array($product[$i][0], $product[$i][1]);
                }
    			
    		}
            
    		//Encode new Cart array into JSON.
    		
    		$createcart = json_encode($newcart);
    		
    	$response = new Response("true");
      $response->withCookie(cookie('cartno', $createcart, 120));
      return $response;
	
}
    	
    }

