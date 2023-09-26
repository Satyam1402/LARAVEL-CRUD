<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        return view('products.index',['products'=>Product::latest()->paginate(2)]);
    }

    public function create(){
        return view('products.create');
    }

    public function store(Request $request){
        // dd($request->all()); through this we can track the frontend data that are coming or we debug the data


        // validate data- This means we tell the user to proper information about thier related datas.Before image we want to show our validation 

        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'image'=>'required|mimes:jpeg,png,jpg,gif|    max:10000'
        ]);

        //upload image
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('products'),$imageName);

        $product = new Product;
        $product->image = $imageName;
        $product->name = $request->name;
        $product->description = $request->description;

        $product->save();

        return back()->withSuccess('Product Created !!!!!');

        // dd($imageName);
    }

    public function edit($id){
        // dd($id);
        $product = product::where('id',$id)->first();

        return view('products.edit',['product'=> $product]);

    }

    public function update(Request $request,$id){
        //  dd($request->all()); 
        
        //validate data
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'image'=>'nullable|mimes:jpeg,png,jpg,gif|max:10000'
        ]);

        $product = Product::where('id',$id)->first();

        if (isset($request->image)) {
            //upload image
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('products'),$imageName);
            $product->image = $imageName;
            
        }

        // $product = new Product; --- we dont need anymore this line
        $product->name = $request->name;
        $product->description = $request->description;

        $product->save();

        return back()->withSuccess('Product Updated !!!!!');

    }

    public function destroy($id){
        $product = Product::where('id',$id)->first();
        $product->delete();
        return back()->withSuccess('Product deleted !!!!!');
    }
    public function show($id){
        $product = Product::where('id',$id)->first();
        return view('products.show',['product'=>$product]);
    }
}
