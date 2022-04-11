<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\{Validator, Log};

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $paginate = $request->get('paginate');
        $paginate = $paginate > 0 && $paginate <= 100 ? $paginate : 10;

        $customer =  Customer::select('id','name', 'email');
        if(!empty($search)){
            $customer->where("name", "{$search}");
        }

        return response()->json($customer->paginate($paginate), 200);
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
            'email' => 'required|email',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try{
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->save();

            return response()->json(['success' =>  'successfully created customer!'], 201);
        } catch(Exception $e){
            Log::info("[Customer]store: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try{
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->save();

            return response()->json(['success' =>  'successfully updated customer!'], 201);
        } catch(Exception $e){
            Log::info("[Customer]update: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try{
            $customer->delete();
            return response()->json(['success' => 'successfully deleted customer!']);
        } catch(Exception $e){
            Log::info("[Customer]destroy: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }
}
