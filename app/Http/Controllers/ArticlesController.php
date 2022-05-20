<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use  Validator;
use App\Traits\sharedTrait;

class ArticlesController extends Controller
{
    use sharedTrait;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $article =  Article::with('category')->get();
        return ApiResource::collection($article)->additional(['message' => [
            'message' => 'Data showed successfully ',
        ]]);;
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
        $article = new Article;
        $this->validateData($request);
        $article->title = $request->title;
        $article->subject = $request->subject;
        $article->Date = $request->Date;
        $article->slug = $this->slug($request->title);;
        $article->category_id =$request->category_id;

        $destinationPic = 'uploads/pictures/'.$article->fileCoverName;
        $article->fileCoverName = $this->save($request ,  $destinationPic , 'fileCoverName', 'pictures' );

        if ($article->save()) {
            return (new ApiResource($article))->additional(['message' => [
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
        $article = Article::with('category')->where('slug',$slug)->first();
        if (!$article) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        return (new ApiResource($article))
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

        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $data = $this->validateData($request);

        $article->title = $request->title;
        $article->subject = $request->subject;
        $article->Date = $request->Date;
        $article->category_id = $request->category_id;

        $destinationPic = 'uploads/pictures/'.$article->fileCoverName;
        $article->fileCoverName = $this->save($request ,  $destinationPic , 'fileCoverName', 'pictures' );
        
        if ($article->save()) {
            return (new ApiResource($article))->additional(['message' => [
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

        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        if ($article->delete()) {
            return (new ApiResource($article))->additional(['message' => [
                'message' => 'Data Successfully Deleted ',
            ]]);;
        }



    }


    public function validateData($request)
    {
        $validator = $request->validate([
            'title'    => 'required|min:6|unique:articles',
            'subject' => 'required|min:6',
            'Date'    => 'required|Date',
            'category_id' => 'required|numeric ',
            // 'fileCoverName' =>'mimes:png,jpg,jpeg'

        ]);



        return   $validator;

    }

    public function search(Request $request)
    {

        $article =Article::where('title','like','%'.$request->title.'%')->with('category')->get();

        if( $article->count() < 1){
            return response()->json(['message' => 'title does not match'], 404);
        }
        return (new ApiResource($article))->additional(['message' => [
            'message' => 'title matches ',
        ]]);
    }


}
