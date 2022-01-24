<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $categories = Category::select('id', 'categoryName')->get();
        $view->with('categories', $categories);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categories = Category::select('id', 'categoryName')->get();
        $blogs = Blog::with('category', 'user')
                ->orderBy('id', 'DESC')
                ->limit(6)
                ->get(['id', 'title', 'post_excerpt', 'slug', 'featuredImage', 'user_id', 'updated_at']);
        
        return view('home')
                ->with('categories', $categories)
                ->with('blogs', $blogs);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBlogs() {
        $blogs = Blog::with('category', 'user')
                ->orderBy('id', 'DESC')
                ->select(['id', 'title', 'post_excerpt', 'slug', 'featuredImage', 'user_id', 'updated_at'])
                ->paginate(9);
        
        return view('all-blogs')
                ->with('blogs', $blogs);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function getBlog($slug) {
        $blog = Blog::whereSlug($slug)
                ->with('category', 'user', 'tag')
                ->orderBy('id', 'DESC')
                ->limit(6)
                ->first(['id', 'title', 'post', 'featuredImage', 'user_id', 'updated_at']);
        
        
        // Get related blogs
        $categoryIds = [];
        foreach($blog->category as $cat) {
            array_push($categoryIds, $cat->id);
        }
        $relatedBlogs = Blog::with('user')->where('id', '!=', $blog->id)->whereHas('category', function($q) use ($categoryIds) {
            $q->whereIn('category_id', $categoryIds);
        })->limit(5)->orderBy('id', 'DESC')->get(['id', 'title', 'slug', 'featuredImage', 'user_id']);

        return view('single-blog')
                    ->with('blog', $blog)
                    ->with('relatedBlogs', $relatedBlogs);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryBlogs($categoryName, $id) {
        $blogs = Blog::with('user')->whereHas('category', function($q) use ($id) {
            $q->where('category_id', $id);
        })->orderBy('id', 'DESC')->select(['id', 'title', 'post_excerpt', 'slug', 'featuredImage', 'user_id', 'updated_at'])->paginate(9);

        return view('category')
                    ->with('categoryName', $categoryName)
                    ->with('blogs', $blogs);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tagBlogs($tagName, $id) {
        $blogs = Blog::with('user')->whereHas('tag', function($q) use ($id) {
            $q->where('tag_id', $id);
        })->orderBy('id', 'DESC')->select(['id', 'title', 'post_excerpt', 'slug', 'featuredImage', 'user_id', 'updated_at'])->paginate(9);

        return view('tag')
                    ->with('tagName', $tagName)
                    ->with('blogs', $blogs);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $str = $request->str;
        $blogs = Blog::with('category', 'user')
                ->orderBy('id', 'DESC')
                ->select(['id', 'title', 'post', 'post_excerpt', 'slug', 'featuredImage', 'user_id', 'updated_at']);
        
        $blogs->when(trim($str) != '', function($q) use($str) {
            $q->where('title', 'LIKE', "%{$str}%") // seach by post title
            ->orWhereHas('category', function($q) use($str) {
                $q->where('categoryName', $str); // seach by categoryName also
            })
            ->orWhereHas('tag', function($q) use($str) {
                $q->where('tagName', $str); // seach by tagName also
            });
        });
        
        return view('all-blogs')
                ->with('blogs', $blogs->paginate(9));
    }
}
