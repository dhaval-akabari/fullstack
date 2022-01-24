<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminCheck;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Frontend Routes */
Route::get('/', [FrontendBlogController::class, 'index'])->name('home');
Route::get('/blog/{slug}', [FrontendBlogController::class, 'getBlog'])->name('blog.single');
Route::get('/category/{categoryName}/{id}', [FrontendBlogController::class, 'categoryBlogs'])->name('category.blogs');
Route::get('/tag/{tagName}/{id}', [FrontendBlogController::class, 'tagBlogs'])->name('tag.blogs');
Route::get('/all-blogs', [FrontendBlogController::class, 'getBlogs'])->name('all.blogs');
Route::get('/search', [FrontendBlogController::class, 'search'])->name('blogs.search');


/* End of Frontend Routes */



/* Admin Routes */

// Auth routes
Route::post('/admin/login', [LoginController::class, 'login']);
Route::get('/admin/auth-user', [AdminController::class, 'getAuthUser']);
Route::get('/admin/auth-user-permission', [AdminController::class, 'getAuthUserPermission']);
Route::get('/admin/logout', [LoginController::class, 'logout']);

Route::prefix('admin')->middleware(AdminCheck::class)->group(function () {
    // dashboard
    Route::get('dashboard', [AdminController::class, 'index']);

    // tag routes
    Route::get('tag', [TagController::class, 'getTag']);
    Route::post('tag/add', [TagController::class, 'addTag']);
    Route::put('tag/edit', [TagController::class, 'editTag']);
    Route::delete('tag/delete', [TagController::class, 'deleteTag']);

    // category routes
    Route::get('category', [CategoryController::class, 'getCategory']);
    Route::post('category/add', [CategoryController::class, 'addCategory']);
    Route::put('category/edit', [CategoryController::class, 'editCategory']);
    Route::delete('category/delete', [CategoryController::class, 'deleteCategory']);

    // blog routes
    Route::get('blogs', [BlogController::class, 'getBlogs']);
    Route::get('blog/{id}', [BlogController::class, 'getBlog']);
    Route::post('blog/add', [BlogController::class, 'addBlog']);
    Route::put('blog/edit/{id}', [BlogController::class, 'editBlog']);
    Route::delete('blog/delete', [BlogController::class, 'deleteBlog']);

    // admin user routes
    Route::get('user', [UserController::class, 'getUser']);
    Route::post('user/add', [UserController::class, 'addUser']);
    Route::put('user/edit', [UserController::class, 'editUser']);
    Route::delete('user/delete', [UserController::class, 'deleteUser']);

    // user role routes
    Route::get('role', [RoleController::class, 'getRole']);
    Route::post('role/add', [RoleController::class, 'addRole']);
    Route::put('role/edit', [RoleController::class, 'editRole']);
    Route::delete('role/delete', [RoleController::class, 'deleteRole']);

    // role permission route
    Route::post('role/permission', [RoleController::class, 'assignPermission']);
    
});
/*End of Admin Routes*/


/* important for vue route, keep it bottom */
Route::any('{slug}', [AdminController::class, 'index'])->where('slug', '.*');
