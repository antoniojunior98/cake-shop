<?php

namespace App\Http\Controllers;

use App\Jobs\NotifyAllCustomers;
use Illuminate\Http\Request;
use App\Models\{Product, Categories, Dough, SendProductDaily};
use Exception;
use Illuminate\Support\Facades\{Validator, Log};
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->only([
            'categories',
            'dough',
            'price',
            'weight'
        ]);
        
        $search = $request->get('search');
        $paginate = $request->get('paginate');
        $paginate = $paginate > 0 && $paginate <= 100 ? $paginate : 10;

        $product = new Product();
        $product = $product->getProduct($search);
        foreach($filter as $filter => $value){
            if(!empty($value)){
                $this->filterBy($product, $filter, $value);
            }
        }        
        if(!empty($search)){
            $product->where('product.name', 'LIKE', "%{$search}%")
                ->orWhere('categories.name', 'LIKE', "%{$search}%")
                ->orWhere('dough.name', 'LIKE', "%{$search}%");
        }
        
        return ProductResource::collection($product->paginate($paginate));
    }

    private function filterBy($model, $filter, $value)
    {
        switch ($filter) {
            case 'categories':
                $model->whereIn('categories.id', $value);
                break;
            case 'dough':
                $model->whereIn('dough.id', $value);
                break;
            case 'price':
                if(isset($value['min']) && isset($value['max'])){
                    $model->whereBetween('product.price', [$value['min'], $value['max']]);
                }
                break;
            case 'weight':
                if(isset($value['min']) && isset($value['max'])){
                    $model->whereBetween('product.weight', [$value['min'], $value['max']]);
                }
                break;
            default:
                break;
        }
        return $model;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'weight' => 'required|integer',
            'amount' => 'required|integer'
        ]);
        $sendProductDaily = $request->get('send_product_daily');
        $this->checkCategories($validate, $request->categories_id);
        $this->checkDough($validate, $request->dough_id);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try{
            $product = new Product();
            $product->categories_id = $request->categories_id;
            $product->dough_id = $request->dough_id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->weight = $request->weight;
            $product->amount = $request->amount;
            $product->save();
            if($sendProductDaily){
                $SendProductDaily = new SendProductDaily();
                $SendProductDaily->product_id = $product->id;
                $SendProductDaily->save();
            }
            NotifyAllCustomers::dispatch($product); 

            return response()->json(['success' =>  'successfully created product!'], 201);
        } catch(Exception $e){
            Log::info("[Product]store: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->categories = $product->categories->name;
        $product->dough = $product->dough->name;

        return ProductResource::collection([$product]);
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'weight' => 'required|integer',
            'amount' => 'required|integer'
        ]);
        $sendProductDaily = $request->get('send_product_daily');
        
        $this->checkCategories($validate, $request->categories_id);
        $this->checkDough($validate, $request->dough_id);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try{
            $product->categories_id = $request->categories_id;
            $product->dough_id = $request->dough_id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->weight = $request->weight;
            $product->amount = $request->amount;
            $product->save();
            if($sendProductDaily){
                $SendProductDaily = new SendProductDaily();
                $SendProductDaily->product_id = $product->id;
                $SendProductDaily->save();
            }
            
            return response()->json(['success' =>  'successfully updated product!'], 201);
        } catch(Exception $e){
            Log::info("[Product]update: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }

    private function checkCategories($validate, $categories_id)
    {
        $categories = Categories::where('id', $categories_id)
            ->first();

        $validate->after(function ($validate) use ($categories) {
            if(!isset($categories)){
                $validate->errors()->add('categories', __('validation.required', [
                    'attribute' => 'categories',
                ]));
            }
            return;
        });
    }

    private function checkDough($validate, $dough_id)
    {
        $dough = Dough::where('id', $dough_id)
            ->first();

        $validate->after(function ($validate) use ($dough) {
            if(!isset($dough)){
                $validate->errors()->add('dough', __('validation.required', [
                    'attribute' => 'dough',
                ]));
            }
            return;
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try{
            $product->delete();
            return response()->json(['success' => 'successfully deleted product!']);
        } catch(Exception $e){
            Log::info("[Product]Destroy: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
        
    }
}
