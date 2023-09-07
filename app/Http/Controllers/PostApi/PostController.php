<?php

namespace App\Http\Controllers\PostApi;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = PostResource::collection(Post::all());

        $array = [];
        foreach ($posts as $post) {

            $array[] = [
                "post" => $post->title,
                "body" => $post->body,
                "user_name" => $post->user->name
            ];
        }
        return $this->apiResponse($array);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        // $validated = $request->validated();
        // if ($validated) {
        //     return $this->apiResponse(null, $validated->errors(), 400);
        // }
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => Auth::id(),
        ]);
        $array = [
            new PostResource($post),
            'name_user' => $user = Auth::user()->name,
        ];
        if ($post) {
            return $this->successResponse($array, 'the Post  Save');
        }
        return $this->errorResponse('the Post Not Save');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        $array = [
            new PostResource($post),
            'name_user' => $user = Auth::user()->name,
        ];
        if ($post) {
            return $this->successResponse($array, 'ok');
        }
        return $this->errorResponse('the Post Not Found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        // if ($request->fails()) {
        //     return $this->apiResponse(null, $request->errors(), 400);
        // }
        $post = Post::find($id);
        if (!$post) {
            return $this->errorResponse('the Post Not Found', 404);
        }
        if ($post->user_id === Auth::id()) {

            $post->update([
                'title' => $request->title,
                'body' => $request->body,
                'user_id' => Auth::id(),
            ]);
            $array = [
                new PostResource($post),
                'name_user' => $user = Auth::user()->name,
            ];
            if ($post) {
                return $this->successResponse($array, 'the post update');
            }
        }
        return $this->errorResponse('you con not updet the post Because you are not authorized', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $post = Post::find($id);

    //     if (!$post) {
    //         return $this->errorResponse('the Post Not Found', 404);
    //     }
    //     if ($post->user_id === Auth::id()) {

    //         $post->delete($id);
    //         if ($post) {
    //             return $this->apiRespons(null, 'the post deleted', 200);
    //         }
    //         return $this->errorResponse('you con not delete the post Because you are not authorized', 404);
    //     }
    // }
    public function destroy($id)
    {
        $post = Post::find($id);

        if ($post->user_id === Auth::id()) {
            $post->delete();
            if ($post) {
                return $this->successResponse(null, 'the post deleted');
            }
            return $this->errorResponse('you con not delete the post', 400);
        }
        return $this->errorResponse('you con not delete the post Because you are not authorized', 401);
    }
    public function showsoft()
    {
        $posts = Post::onlyTrashed()->get();
        return $this->apiResponse($posts);
    }
    public function restor($id)
    {
        $post = Post::withTrashed()->where('id', $id)->restore();
        return $this->successResponse($post, 'the post restor');
    }
    public function finldelet($id)
    {
        $post = Post::withTrashed()->where('id', $id)->forceDelete();
        if ($post) {
            return $this->successResponse(null, 'the post deleted');
        }
        return $this->errorResponse('you con not delete the post', 400);
    }
}
