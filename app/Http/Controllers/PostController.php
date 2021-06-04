<?php

namespace App\Http\Controllers;

use App\Filters\PostFilters;
use App\Http\Middleware\CheckPermission;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Response\Response;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * PostController constructor.
     */
    public function __construct()
    {
        $this->middleware(CheckPermission::class . ":read-post")->only(['index', 'show']);
        $this->middleware(CheckPermission::class . ":create-post")->only(['store']);
        $this->middleware(CheckPermission::class . ":update-post")->only(['update']);
        $this->middleware(CheckPermission::class . ":delete-post")->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PostFilters $filters
     * @return \Illuminate\Http\Response
     */
    public function index(PostFilters $filters)
    {
        $posts = Post::filter($filters)->get();

        return Response::json(200, PostResource::collection($posts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = Post::query()->create($request->validated());

        return Response::json(200, [
            'post_id' => $post->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return Response::json(200, new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return Response::json(200, new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return Response::json(200);
    }
}
