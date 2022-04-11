<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dough;
use Illuminate\Support\Facades\{Validator, Log};
use Exception;

class DoughController extends Controller
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

        $dough = Dough::select('id', 'name');
        if(!empty($search)){
            $dough->where("name", "LIKE", "%{$search}%");
        }

        return response()->json($dough->paginate($paginate), 200);
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
            'name' => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try{
            $dough = new Dough();
            $dough->name = $request->name;
            $dough->save();
            
            return response()->json(['success' =>  'successfully created dough!'], 201);
        } catch(Exception $e){
            Log::info("[Dough]store: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dough $dough
     * @return \Illuminate\Http\Response
     */
    public function show(Dough $dough)
    {
        return response()->json($dough, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dough $dough
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dough $dough)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try{
            $dough->name = $request->name;
            $dough->save();
            
            return response()->json(['success' =>  'successfully updated dough!'], 201);
        } catch(Exception $e){
            Log::info("[Dough]update: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dough $dough
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dough $dough)
    {
        try{
            $dough->delete();
            return response()->json(['success' => 'successfully deleted dough!']);
        } catch(Exception $e){
            Log::info("[Dough]destroy: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }
}
