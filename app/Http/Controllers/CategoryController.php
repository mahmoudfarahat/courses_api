<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\ApiResource;



use  Validator;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $category = Category::all();
        $category =  Category::with('articles','books','playlists','media')->get();

        if($category->count() < 1){


            return response()->json(['message' => 'there are no categories'], 404);
        }

        return ApiResource::collection($category)->additional(['message' => [
            'message' => 'Data showed successfully ',
        ]]);;

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

        $category = new Category;
        $this->validateData($request);

        $category->type = $request->type;

        $saved = $category->save();
        if ($saved) {
            return (new ApiResource($category))->additional(['message' => [
                'message' => 'Data inserted successfuly ',
            ]]);
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

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        return (new ApiResource($category))
            ->additional(['message' => [
                'message' => 'Data retriverd ',
            ]]);
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

        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $this->validateData($request);

        $category->type = $request->type;



        if ($category->save()) {
            return (new ApiResource($category))->additional(['message' => [
                'message' => 'Data Successfully Updated ',
            ]]);;;
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
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        if ($category->delete()) {
            return (new ApiResource($category))->additional(['message' => [
                'message' => 'Data Successfully Deleted ',
            ]]);;
        }
    }



        public function validateData($request)
        {
            $validator = $request->validate([
                'type'    => 'required|min:3|unique:category'
            ]);


            return   $validator;


    }
}
