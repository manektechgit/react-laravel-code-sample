<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $posts = Post::all();
            
            return Inertia::render('Posts/Index', ['posts' => $posts]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create()
    {
        try {
            return Inertia::render('Posts/Create');
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            Validator::make($request->all(), [
                'title' => ['required'],
                'body' => ['required'],
            ])->validate();

            $post = new Post;
            $post->title = isset($request->title) ? $request->title : '';
            $post->body = isset($request->body) ? $request->body : '';
            $post->save();

            $post->user()->attach(Auth::user()->id);
        
            return redirect()->route('posts.index');
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function edit(Post $post)
    {
        try {
            return Inertia::render('Posts/Edit', [
                'post' => $post
            ]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        try {
            Validator::make($request->all(), [
                'title' => ['required'],
                'body' => ['required'],
            ])->validate();

            $post = Post::find($id);
            $post->title = isset($request->title) ? $request->title : '';
            $post->body = isset($request->body) ? $request->body : '';
            $post->save();

            $post->user()->sync(Auth::user()->id);

            return redirect()->route('posts.index');
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $post = Post::find($id);
            $post->user()->detach();

            Post::find($id)->delete();

            return redirect()->route('posts.index');
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
}
