<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Models\Playlist;
use App\Traits\sharedTrait;
use Illuminate\Http\Request;
use Validator;

class PlaylistController extends Controller
{
    use sharedTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $playlist = Playlist::all();

        if($playlist->count() < 1){


            return response()->json(['message' => 'there are no playlists'], 404);
        }


        return ApiResource::collection($playlist)->additional(['message' => [
            'message' => 'Data showed successfully ',
        ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $playlist = new Playlist;
        $this->validateData($request);
        $playlist->title = $request->title;
        $playlist->Date = $request->Date;
        $playlist->description = $request->description;
        $playlist->slug = $this->slug($request->title);
        $playlist->category_id = $request->category_id;
        if ($playlist->save()) {
            return (new ApiResource($playlist))->additional(['message' => [
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
    public function show($slug)
    {
        $playlist = Playlist::where('slug', $slug)->with('category')->first();

        if (!$playlist) {
            return response()->json(['message' => 'Data Not Found'], 404);
        } else {
            return (new ApiResource($playlist))
                ->additional(['message' => [
                    'message' => 'Data retriverd ',
                ]]);
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
        $playlist = Playlist::find($id);
        if (!$playlist) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }
        $this->validateData($request);
        $playlist->title = $request->title;
        $playlist->Date = $request->Date;
        $playlist->description = $request->description;
        $playlist->slug = $this->slug($request->title);
        $playlist->category_id = $request->category_id;
        if ($playlist->save()) {
            return (new ApiResource($playlist))->additional(['message' => [
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
        $playlist = Playlist::find($id);
        if (!$playlist) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        if ($playlist->delete()) {
            return (new ApiResource($playlist))->additional(['message' => [
                'message' => 'Data Successfully Deleted ',
            ]]);
        }
    }


    public function validateData($request)
    {
        $validator = $request->validate([
            'title' => 'required|min:6|unique:playlists',
            'Date' => 'required|Date',
            'description' => 'required|min:6',
            'category_id' => 'required|numeric'
        ]);

        return $validator;

    }


 public function search(Request $request)
{

    $playlist =Playlist::where('title','like','%'.$request->title.'%')->with('media','category')->get();

    if( $playlist->count() < 1){
        return response()->json(['message' => 'title does not match'], 404);
    }
    return (new ApiResource($playlist))->additional(['message' => [
        'message' => 'title matches ',
    ]]);
}

}
