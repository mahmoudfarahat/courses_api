<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\Question;


use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\File;
use  Validator;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions =  Question::all();
        if( $questions->count() < 1){
            return response()->json(['message' => 'there are no questions'], 404);
        }
        return ApiResource::collection($questions)->additional(['message' => [
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
        $question = new Question;

        $data = $this->validateData($request);
        $saved = Question::create($data);
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
        $qestion = Question::find($id);

        if (!$qestion) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        return (new ApiResource($qestion))
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

        $question = Question::find($id);


        if (!$question) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        if(!$question->answer){
            $request->validate([
                'answer' => 'required|min:6',
                'answerDate' => 'required|date'
            ]);


            $question->answer = $request->answer;
            $question->answerDate = $request->answerDate;


            if ($question->save()) {
                return (new ApiResource($question))->additional(['message' => [
                    'message' => 'Answer is Successfully added ',
                ]]);;;
            }
        }

        return response()->json(['message' => 'Answer is Already exsited'], 404);

    }

    public function changeAnswer(Request $request, $id)
    {

        $question = Question::find($id);

        if (!$question) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

            $request->validate([
                'answer' => 'required|min:6',
                'answerDate' => 'required|date'
            ]);


            $question->answer = $request->answer;
            $question->answerDate = $request->answerDate;


            if ($question->save()) {
                return (new ApiResource($question))->additional(['message' => [
                    'message' => 'Answer is Successfully Updated ',
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
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        if ($question->delete()) {
            return (new ApiResource($question))->additional(['message' => [
                'message' => 'Data Successfully Deleted ',
            ]]);;
        }
    }

    public function answerDestroy($id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }
        $question->answer = null;
        $question->answerDate = null;

        if ($question->save()) {
            return (new ApiResource($question))->additional(['message' => [
                'message' => 'Answer Successfully Deleted ',
            ]]);;
        }

    }

    public function validateData($request)
    {
        $validator = $request->validate([
            'name'    => 'required|min:3',
            'email' => 'required|email',
            'question' => 'required|min:6',

        ]);



        return   $validator;


}
}
