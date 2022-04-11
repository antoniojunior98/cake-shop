<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Support\Facades\{Validator, Log};
use Exception;

class CategoriesController extends Controller
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

        $categories = Categories::select('id', 'name');
        if(!empty($search)){
            $categories->where("name", "LIKE", "%{$search}%");
        }

        return response()->json($categories->paginate($paginate), 200);
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
            $categories = new Categories();
            $categories->name = $request->name;
            $categories->save();

            return response()->json(['success' =>  'successfully created category!'], 201);
        } catch(Exception $e){
            Log::info("[Categories]store: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function show(Categories $categories)
    {
        return response()->json($categories->first(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categories $categories)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try{
            $categories->name = $request->name;
            $categories->save();

            return response()->json(['success' =>  'successfully updated category!'], 201);
        } catch(Exception $e){
            Log::info("[Categories]update: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categories $categories)
    {
        try{
            $categories->delete();
            return response()->json(['success' => 'successfully deleted category!']);
        } catch(Exception $e){
            Log::info("[Categories]destroy: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }
}
