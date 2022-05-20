<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Models\Book;
use App\Traits\sharedTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BookController extends Controller
{
    use sharedTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $book = Book::with('category')->get();
        if($book->count() < 1){
            return response()->json(['message' => 'there are no books'], 404);
        }

        return ApiResource::collection($book)->additional(['message' => [
            'message' => 'Data showed successfully ',
        ]]);
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

        $book = new Book;
        $this->validateData($request);

        $book->title = $request->title;
        $book->description = $request->description;
        $book->type = $request->type;
        $book->numberOfPages = $request->numberOfPages;
        $book->Date = $request->Date;
        $book->slug = $this->slug($request->title);
        $book->category_id = $request->category_id;


        $destinationPic = 'uploads/pictures/'.$book->fileCoverName;
        $book->fileCoverName = $this->save($request ,  $destinationPic , 'fileCoverName', 'pictures' );


        $destinationPdf = 'uploads/pdf/'.$book->fileBookName;
        $book->fileBookName = $this->save($request ,  $destinationPdf , 'fileBookName', 'pdf' );


        if ($book->save()) {
            return (new ApiResource($book))->additional(['message' => [
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
        $book = Book::with('category')->where('slug', $slug)->first();
        if (!$book) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        return (new ApiResource($book))
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

        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $this->validateData($request);

        $book->title = $request->title;
        $book->description = $request->description;
        $book->type = $request->type;
        $book->numberOfPages = $request->numberOfPages;
        $book->Date = $request->Date;
        $book->category_id = $request->category_id;

        $destinationPic = 'uploads/pictures/'.$book->fileCoverName;
        $book->fileCoverName = $this->save($request ,  $destinationPic , 'fileCoverName', 'pictures' );


        $destinationPdf = 'uploads/pdf/'.$book->fileBookName;
        $book->fileBookName = $this->save($request ,  $destinationPdf , 'fileBookName', 'pdf' );


        if ($book->save()) {
            return (new ApiResource($book))->additional(['message' => [
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

        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        if ($book->delete()) {
            return (new ApiResource($book))->additional(['message' => [
                'message' => 'Data Successfully Deleted ',
            ]]);
        }
    }

    public function validateData($request)
    {
        $validator = $request->validate([
            'title' => 'required|min:6|unique:books',
            'description' => 'required|min:6',
            'type' => 'required|min:6',
            'numberOfPages' => 'required|numeric',
            'Date' => 'required|Date',
            'category_id' => 'required|numeric',
            'fileBookName' =>'mimes:pdf,docx',
            'fileCoverName' =>'mimes:png,jpg,jpeg'
        ]);

        return $validator;

    }

    public function download(Request $request)
    {

        $book = Book::where('fileBookName', $request->fileBookName)->first();
        if (!$book) {
            return response()->json(['message' => 'Data Not Found'], 404);

        }
        $book->numberOfDownloads = $book->numberOfDownloads + 1;
        $book->save();
        return response()->download(public_path('uploads/pdf/' . $book->fileBookName), $book->fileBookName);

    }

    public function search(Request $request)
    {

        $book =Book::where('title','like','%'.$request->title.'%')->with('category')->get();

        if( $book->count() < 1){
            return response()->json(['message' => 'title does not match'], 404);
        }
        return (new ApiResource($book))->additional(['message' => [
            'message' => 'title matches ',
        ]]);
    }



}
