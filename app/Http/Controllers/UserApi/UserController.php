<?php

namespace App\Http\Controllers\UserApi;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $array = [
        $post = PostResource::collection(Post::where('user_id',Auth::id())->get()),
       'name_user'=> $user = Auth::user()->name,
    ];

        return $this->apiResponse($array);
    }
}
