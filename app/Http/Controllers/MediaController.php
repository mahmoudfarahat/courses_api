<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Category;
use App\Models\Playlist;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\File;
use App\Traits\sharedTrait;

class MediaController extends Controller
{
    use sharedTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $media =  Media::with('category','playlists')->get();


        if($media->count() < 1){


            return response()->json(['message' => 'there are no media'], 404);
        }
        return ApiResource::collection($media)->additional(['message' => [
            'message' => 'Data showed successfully ',
        ]]);;
    }

      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $media = new Media;

        $this->validateData($request);

       $media->title = $request->title;
       $media->description = $request->description;
       $media->type = $request->type;
       $media->writtenLecture = $request->writtenLecture;
       $media->Date = $request->Date;
       $media->category_id = $request->category_id;
       $media->playlist_id = $request->playlist_id;
       $media->link = $request->link;
       $media->slug = $this->slug($request->title);;



    $destinationPic = 'uploads/pictures/'.$media->fileCoverName;
    $media->fileCoverName = $this->save($request ,  $destinationPic , 'fileCoverName', 'pictures' );


    $destinationMedia = 'uploads/media/'.$media->fileMediaName;
    $media->fileMediaName = $this->save($request ,  $destinationMedia , 'fileMediaName' ,'media' );





       if ($media->save()) {
           return (new ApiResource($media))->additional(['message' => [
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
        $media = Media::with('category','playlists')->where('slug',$slug)->first();
        if (!$media) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        return (new ApiResource($media))
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

        $media = Media::find($id);


        if (!$media) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $this->validateData($request);
        $media->title = $request->title;
        $media->description = $request->description;
        $media->type = $request->type;
        $media->writtenLecture = $request->writtenLecture;
        $media->Date = $request->Date;
        $media->category_id = $request->category_id;
       $media->slug = $this->slug($request->title);;
       $media->link = $request->link;

            $media->playlist_id = $request->playlist_id;




        $destinationPic = 'uploads/pictures/'.$media->fileCoverName;
        $media->fileCoverName = $this->save($request ,  $destinationPic , 'fileCoverName', 'pictures' );


        $destinationMedia = 'uploads/media/'.$media->fileMediaName;
        $media->fileMediaName = $this->save($request ,  $destinationMedia , 'fileMediaName' ,'media' );



        if ($media->save()) {
            return (new ApiResource($media))->additional(['message' => [
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
        $media = Media::find($id);
        if (!$media) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        if ($media->delete()) {
            return (new ApiResource($media))->additional(['message' => [
                'message' => 'Data Successfully Deleted ',
            ]]);;
        }
    }



    public function validateData($request)
    {
        $validator = $request->validate([
            'title'    => 'required|min:6|unique:media',
            'description' => 'required|min:6',
            'type' => 'required|min:6',
            'Date'    => 'required|Date',
            'category_id' => 'required|numeric',
            'playlist_id' => 'numeric',
            'fileMediaName' => 'required|mimes:mp4,mp3',
            'link' =>'URL',
            'fileCoverName' =>'mimes:png,jpg,jpeg'


        ]);



        return   $validator;


}


public function search(Request $request)
{

    $media =Media::where('title','like','%'.$request->title.'%')->with('category','playlists')->get();

    if( $media->count() < 1){
        return response()->json(['message' => 'title does not match'], 404);
    }
    return (new ApiResource($media))->additional(['message' => [
        'message' => 'title matches ',
    ]]);
}

}
