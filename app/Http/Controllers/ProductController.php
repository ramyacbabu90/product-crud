<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductToCategory;
use App\Models\ProductImage;
use Auth;
use Validator;
use File;
use Session;
use Redirect;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $products = Product::with('category','images')->get()->toArray();
         return view('index',[
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
        $oUser              = Auth::user();
        $loggedUserId       = $oUser->id;
        $category = Category::all();
        return view('add',['category' => $category]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $aRequest = $request->all();
        $aRules = [
            'name' => 'required|max:255',
            'price' => 'required|numeric|between:0,9999999999.99',
            'category'   => 'required',
            'unit'   => 'required',
            'discount_rate'   => 'required|numeric|between:0,100',
            //'discount_amount'   => 'required|numeric|between:0,9999999999.99',
            'tax_rate'   => 'required|numeric|between:0,100',
            //'tax_amount'   => 'required|numeric|between:0,9999999999.99',
            'stock_quantity'   => 'required|numeric',
            'image'   => 'required|array',
            'image.*'  => 'mimes:jpg,png,jpeg,webp,gif|max:2000|required',
        ];

        if (isset($aRequest['id']) && $aRequest['id'] !='' && !isset($aRequest['image'])) {
            unset($aRules['image']);
            unset($aRules['image.*']);
        }
        
        $validation = Validator::make($aRequest, $aRules, []);
        if ($validation->fails()) {

            $aError = $validation->errors()->toArray();
            foreach ($aError as $key => $aVal) {
                $aErrorMsg[] = implode(',', $aVal);
            }
            $msg = implode(',', $aErrorMsg);

            Session::flash('message', $msg);
            return Redirect::back();
        }

        if(isset($aRequest['id']) && $aRequest['id'] !='' && $aRequest['id'] > 0){
            $product = Product::find($aRequest['id']);
        }else{
           $product = new Product();//::create($validatedData);
        }

        
        $product->name = $aRequest['name'];
        $product->price = $aRequest['price'];
        $product->unit = $aRequest['unit'];
        $product->discount_rate = $aRequest['discount_rate'];
        if($aRequest['discount_rate'] > 0 ){ 
            $product->discount_amount = $aRequest['price'] *  $aRequest['discount_rate'] /100; //$aRequest['discount_amount'];
        }
        $product->tax_rate = $aRequest['tax_rate'];
        if($aRequest['tax_rate'] > 0 ){ 
            $product->tax_amount = $aRequest['price'] *  $aRequest['tax_rate'] /100; //$aRequest['tax_amount'];
        }
        $product->stock_status = $aRequest['stock_status'];
        $product->stock_quantity = $aRequest['stock_quantity'];

        if($aRequest['discount_from_date'] && $aRequest['discount_from_date'] !=''){
        $product->discount_from_date = date('Y-m-d', strtotime($aRequest['discount_from_date']));
        }
        if($aRequest['discount_to_date'] && $aRequest['discount_to_date'] !=''){
        $product->discount_to_date = date('Y-m-d', strtotime($aRequest['discount_to_date']));
        }
        
        $product->save();

        if(!empty($aRequest['category'])) {
            if(isset($aRequest['id']) && $aRequest['id'] !='' && $aRequest['id'] > 0){
               $delete=ProductToCategory::where('product_id',$aRequest['id'])->delete(); 
            }
            foreach ($aRequest['category'] as $key => $value) {
               ProductToCategory::create(['product_id' =>$product->id, 'category_id'=>$value]);
            }
        }

        if(!empty($aRequest['image'])) {
            foreach ($aRequest['image'] as $key => $file) {

                $file_name=$product->id.'-image'.$key.'-'.time().'.'.$file->getClientOriginalExtension();
                $location = 'uploads';
                $filepath =$this->uploadFileToPath($file,$file_name,$location);

                if($filepath) {
                    
                     $newProfile = ProductImage::create([
                        'product_id' =>$product->id,
                        'image' => $filepath,
                    ]);
                }
            }
        }
        

        return redirect()->route('list')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product  = Product::with('category','images')->where('id', $id)->first();
         return view('view-product',[
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product  = Product::with('category','images')->where('id', $id)->first()->toArray();
        $selected_cat = array_column($product['category'], 'id');
        $category = Category::all();
        return view('add',['category' => $category,'id'=>$id,'product'=>$product,'selected_cat'=>$selected_cat]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{

            $data= $request->all();
            $id = $data['id'];
            $delete=Product::where('id',$id)->delete();

            if($delete) {
                return response()->json([
                    'status' => true,
                    'msg'    => 'Item Deleted succesfully'
                ]); 
            }else {
                return response()->json([
                    'status' => false,
                    'msg'    => 'Something Went wrong. Please try again...!!'
                ]);  
            }

        } catch(Exception $e) {

            return response()->json([
                'status' => false,
                'msg'    => 'Server Error'
            ]);
        }
    }

    public function deleteImage(Request $request){

        try{

            $data= $request->all();
            $id = $data['id'];
            $delete=ProductImage::where('id',$id)->delete();

            if($delete) {
                return response()->json([
                    'status' => true,
                    'msg'    => 'Image Deleted succesfully'
                ]); 
            }else {
                return response()->json([
                    'status' => false,
                    'msg'    => 'Something Went wrong. Please try again...!!'
                ]);  
            }

        } catch(Exception $e) {

            return response()->json([
                'status' => false,
                'msg'    => 'Server Error'
            ]);
        }
    }

    public function uploadFileToPath($file,$file_name,$location) {

            try{
                $maxFileSize = 6097152;
                $valid_extension = array("pdf","jpeg","png","jpg","gif","webp");
                $extension = $file->getClientOriginalExtension();
                if(in_array(strtolower($extension),$valid_extension)){

                    $fileSize = $file->getSize();

                    if($fileSize <= $maxFileSize){

                        $path = public_path($location);
                        if(!File::isDirectory($path)){
                            File::makeDirectory($path, 0777, true, true);
                        }
                        $file->move($location,$file_name);
                        $filepath = $file_name;

                        return $filepath;

                    }else{
                        ErrorLog::log('size error', 'error', __METHOD__);
                        return false;
                    }

                }else{
                 ErrorLog::log('extension error', 'error', __METHOD__);
                 return false;
             }

         }catch(Exception $e){
            ErrorLog::log($e->getMessage(), 'error', __METHOD__);
        }

    }
}
