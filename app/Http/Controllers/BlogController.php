<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\BlogRequest;
use App\Models\Blog;
use App\Models\Blogcategory;
use App\Models\Blogtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBlogs(Request $request) {
        return Blog::with(['tag', 'category'])->orderBy('id', 'DESC')->paginate(10);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBlog($id) {
        return Blog::with(['tag', 'category'])->where('id', $id)->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addBlog(BlogRequest $request) {
        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogTags = [];

        DB::beginTransaction();
        try {
            $blog = Blog::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'post' => $request->post,
                'post_excerpt' => $request->post_excerpt,
                'user_id' => Auth::user()->id,
                'metaDescription' => $request->metaDescription,
            ]);
    
            foreach($categories as $c_id) {
                array_push($blogCategories, ['category_id' => $c_id, 'blog_id' => $blog->id]);
            }
            Blogcategory::insert($blogCategories);
    
            foreach($tags as $t_id) {
                array_push($blogTags, ['tag_id' => $t_id, 'blog_id' => $blog->id]);
            }
            Blogtag::insert($blogTags);

            DB::commit();
            return response()->json(['success'], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editBlog(BlogRequest $request, $id) {
        
        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogTags = [];

        DB::beginTransaction();
        try {
            Blog::whereId($id)->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'post' => $request->post,
                'post_excerpt' => $request->post_excerpt,
                'user_id' => Auth::user()->id,
                'metaDescription' => $request->metaDescription,
            ]);
            
            foreach($categories as $c_id) {
                array_push($blogCategories, ['category_id' => $c_id, 'blog_id' => $id]);
            }

            // Delete all previous categories
            Blogcategory::where('blog_id', $id)->delete();
            // Insert new categories
            Blogcategory::insert($blogCategories);
    

            // Delete all previous tags
            Blogtag::where('blog_id', $id)->delete();
            foreach($tags as $t_id) {
                array_push($blogTags, ['tag_id' => $t_id, 'blog_id' => $id]);
            }
            // Insert new tags
            Blogtag::insert($blogTags);

            DB::commit();
            return response()->json(['success'], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteBlog(Request $request) {
        return Blog::whereId($request->id)->delete();
    }

    
}
