<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json(['posts'=>$posts],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = [
            'required'=>'the :attribute required'];
        $validator = Validator::make($request->all(),[
            'title'=>'required',
            'description'=> 'required',
        ],$message);

        if($validator->fails()) {
            return response()->json(['msg'=> $validator->errors()]);
        }else{
            $post = Post::create([
                'title'=>$request->title,
                'description'=>$request->description,
            ]);
            return  response()->json(['post'=>$post, 'msg' => 'Succssfully Inserted'],200);
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
        $post = Post::find($id);
        return  response()->json(['post'=>$post],200);
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
        $post = Post::findOrFail($id);
        $post->update([
            'title'=>$request->title,
            'description'=>$request->description,
        ]);
        return response()->json(['msg'=>'successfully updated'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findorFail($id);
        $post->delete();
        return  response()->json(['post'=>$post,'msg'=>'successfully Deleted'],200);
    }
}
