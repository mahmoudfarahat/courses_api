<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Models\Bio;
use Illuminate\Http\Request;

class BioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bios = Bio::all();
        return ApiResource::collection($bios)->additional(['message' => [
            'message' => 'Data showed successfully ',
        ]]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $this->validateData($request);

        $bio->title = $request->title ;
        $bio->content = $request->content ;


        $saved =$bio->save();

        if ($saved) {
            return (new ApiResource($saved))->additional(['message' => [
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
        $bio = Bio::find($id);
        if (!$bio) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }
        return (new ApiResource($bio))
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $bio = Bio::find($id);
        if (!$bio) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

         $this->validateData($request);
        $bio->title = $request->title ;
        $bio->content = $request->content ;


        $updated =$bio->save();
        if ($updated) {
            return (new ApiResource($updated))->additional(['message' => [
                'message' => 'Data Successfully Updated ',
            ]]);
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
        $bio = Bio::find($id);
        if (!$bio) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }
        if ($bio->delete()) {
            return (new ApiResource($bio))->additional(['message' => [
                'message' => 'Data Successfully Deleted ',
            ]]);
        }
    }

    public function validateData($request)
    {
        $validator = $request->validate([
            'title' => 'required|min:6|unique:bio',
            'content' => 'required|min:6',
        ]);

        return $validator;
    }
}
