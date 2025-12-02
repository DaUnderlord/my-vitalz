<?php
namespace App;

use DB;

class Products
{
public $product_id;
public $category_id;

    
    function __construct($product_id){
        $this->product_id = $product_id;
  }
    
    function price(){
        $product = DB::select('select * from products WHERE id='.$this->product_id);
        
        return $product[0]->price;
    }
    
    function productName(){
        $product = DB::select('select * from products WHERE id='.$this->product_id);
        if($product[0]->product_ref==NULL){
        return $product[0]->name;
        }else{
            $product2 = DB::select('select * from products WHERE id='.$product[0]->product_ref);
            return $product2[0]->name;
        }
    }
    
    function productPhoto(){
        $product = DB::select('select * from products WHERE id='.$this->product_id);
        
        return $product[0]->photo;
    }
    
    function categoryName(){
        $product = DB::select('select * from products WHERE id='.$this->product_id);
        
        return $product[0]->category;
    }
    
    function description(){
        $product = DB::select('select * from products WHERE id='.$this->product_id);
        
        return $product[0]->description;
    }
    
    
}