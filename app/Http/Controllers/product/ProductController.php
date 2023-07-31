<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Requests\ProductRequest;
use App\Models\product\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    
    public function filteringByPrice(Request $request){
     
        try{
        $query = Product::query();
         
        // Filtering based on price
        
         $minPrice = $request->input('min_price');
            if($minPrice !== null){
             $query->where('price', '>',$minPrice);
            }
        // Filtering based on other attributes
        // Add more filters as needed

        // Apply sorting and pagination
        $sortField = $request->input('sort', 'id');
        $sortOrder = $request->input('order', 'asc');
        $products = $query->orderBy($sortField, $sortOrder)->paginate(10);
       return response()->json($products);

    }
       
        catch(\Exception $e){
            return response()->json(['message'=>'Failed to create product. Please try again later.',500]);


        }

     }

     public function sortingProducts(Request $request){
        try{

            $sortField = $request->input('sort','id');
           
            $orderedField = $request->input('order','asc');
            
            $products = Product::orderBy($sortField,$orderedField)->paginate(10);

         return response()->json([$products,200]);
        }
        
        catch(\Exception $e){
            return response()->json(['message'=>'Failed to Get  product. Please try again later.',500]);
        }
     } 

  /**
 * function get All prduct response Pagination And filtering and Sorting 
 */
   public function index(){
        try{

         $products = Product::paginate(10);
         return response()->json($products);

        }
        catch(\Exception $e){
            return response()->json(['message'=>'Failed to Get product. Please try again later.',500]);

        }
      
    }
     
    public function storeProduct(ProductRequest $productRequest){
      
        /**
         * Check Product is Already Exist return Error Response
         */

         
        $existingProduct = Product::where('name',$productRequest->input('name'))->first(); 
        
        if($existingProduct){
            
            return response()->json(['message'=>'the Product is Already Exist']);
         
        }

        try{

            $product = Product::create($productRequest->all());
            
            return response()->json($product,201);
           
        }
        catch(\Exception $e){
         
        return response()->json(['message'=>'Failed to create product. Please try again later.',500]);

    }
    
}
   public function updateProduct(ProductRequest $productRequest,int $id){

    $product = Product::find($id);
    if(!$product){
        
        return response()->json(['message'=>'the Product is not Found']);

    }

    $product->update($productRequest->all());

    return response()->json($product,200);
        
}   

public function searchProduct(Request $request){
    
    $query = Product::query();
  
    /**
    * Filtering base Price 
    */
     
    $minPrice = $request->input('min_price');

    if($minPrice !== null){

        $query->where('min_price', '>=', $minPrice);
    }

            // Searching based on keyword
     $keyWord = $request->input('keyword');

     if ($keyWord !==null){
     
        $query->where(function($query) use ($keyWord){
        
            $query ->where('name', 'like',"%$keyWord%")->orWhere('description','like',"%$keyWord%");
        });
     }
    
     // Apply sorting and pagination
    $sortField = $request->input('sort','id');
    $sortOrder = $request->input('order','asc');
    
    $products = $query->orderBy($sortField,$sortOrder)->paginate(10);

    return response()->json($products);

}


public function destroy( int $id){

    // Find the product by its ID
    
   $product = Product::find($id);
   
   if(!$product){
    return response()->json(['message'=> 'this is not Found ']);
   }
   $product->delete();
  
   return response()->json(['message'=>'Successfully Delete']);
}
}
