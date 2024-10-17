<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
 use App\Product;
use GuzzleHttp\Middleware;

 class ProductController extends Controller{


public function __construct()
{
   //return  $this->middleware('auth');

}

public function getProducts(): \Illuminate\Http\JsonResponse


{
 


try {
    //code...


    $products=Product::with('cooperative','category','unit');



//$products = Product::all();

//formated products with their respective category name and umit name.

$formattedProducts=$products->map(function($product){

return[
'id'=>$product->id,
'name'=>$product->name,
'category'=>$product->category->name,
'unit'=>$product->unit->name,
'cooperative'=>$product->cooperative->name  

];
});




return response()->json([
    "success" => true,
    "data" => $formattedProducts,


],200);}catch (\Exception $e) {
    //throw $th;
     return response()->json([
        "success" => false,
        "message" => $e->getMessage()
    ],500);
}



}











 }