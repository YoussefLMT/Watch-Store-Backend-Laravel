<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'status' => 200,
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'price'=> 'required',
            'quantity' => 'required',
            'category' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if($validator->fails()){

            return response()->json([
                'validation_err' => $validator->messages(),
            ]);

        }else{

        $product = new Product;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category = $request->category;
        $product->description = $request->description;

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $image_name = time(). '.' .$extension;
            $image->move('uploads/images', $image_name);
            $product->image = 'uploads/images/' . $image_name;
        }

        $product->save();

        return response()->json([
            'status' => 200,
            'message' => "Product added successfully",
        ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if($product){

            return response()->json([
                'status' => 200,
                'product' => $product,
            ]);

        }else{

            return response()->json([
                'status' => 404,
                'message' => 'Product not found!',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'price'=> 'required',
            'quantity' => 'required',
            'description' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);


        if($validator->fails()){

            return response()->json([
                'status' => 422,
                'validation_err' => $validator->messages(),
            ]);

        }else{

        $product = Product::find($id);

        if($product){

            $product->name = $request->name;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->description = $request->description;

            // if($request->hasFile('image'))
            // {
            //     $path = $product->image;
            //     if(File::exists($path)){
            //         File::delete($path);
            //     }
            //     $image = $request->file('image');
            //     $extension = $image->getClientOriginalExtension();
            //     $image_name = time(). '.' .$extension;
            //     $image->move('uploads/images', $image_name);
            //     $product->image = 'uploads/images/' . $image_name;
            // }

            $product->update();
    
            return response()->json([
                'status' => 200,
                'message' => "Updated successully",
            ]);

        }else{

            return response()->json([
                'status' => 404,
                'message' => 'Product not found!',
            ]);
        }
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if($product){

            $product->delete();
    
            return response()->json([
                'status' => 200,
                'message' => 'Deleted successfully',
            ]);

        }else{

            return response()->json([
                'status' => 404,
                'message' => 'Product not found!',
            ]);
        }
    }


    public function getSpecificProducts()
    {
        $latest_products = Product::orderBy('id', 'desc')->limit(4)->get();
        $home_products = Product::limit(8)->get();

        return response()->json([
            'status' => 200,
            'latest_products' => $latest_products,
            'home_products' => $home_products
        ]);
    }


    public function getProductsByCategory($category)
    {
        $products = Product::where('category', $category)->get();

        return response()->json([
            'status' => 200,
            'products' => $products
        ]);
    }
}
