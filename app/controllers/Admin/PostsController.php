<?php
namespace Admin;

use \Post;
use \Input;
use \Redirect;
use \Validator;
use \View;

class PostsController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('admin.posts.index')
            ->with('posts', Post::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('admin.posts.create')
            ->with('post', new Post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'name'      => 'required',
            'slug'      => 'unique:posts',
            'content' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            $post = new Post;
            $post->fill(Input::all());
            $post->save();

            return Redirect::route('admin.posts.index')
                ->with('success', 'Post has been successfully created');
        } else {
            return Redirect::route('admin.posts.create')
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param   int $id
     * @return Response
     */
    public function edit($id)
    {
        return View::make('admin.posts.edit')
            ->with('post', Post::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   int $id
     * @return Response
     */
    public function update($id)
    {
        $post = Post::findOrFail($id);

        $rules = array(
            'name'      => 'required',
            'slug'      => "unique:posts,slug,{$post->id}",
            'content' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            $post->fill(Input::all());
            $post->save();

            return Redirect::route('admin.posts.index')
                ->with('success', 'Post has been successfully updated');
        } else {
            return Redirect::route('admin.posts.edit', $post->id)
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   int $id
     * @return Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->is_deletable) {
            $post->delete();

            return Redirect::route('admin.posts.index')
                ->with('success', 'Post has been successfully deleted');
        } else {
            return Redirect::route('admin.posts.index')
                ->with('danger', 'This post cannot be deleted');
        }
    }

}
