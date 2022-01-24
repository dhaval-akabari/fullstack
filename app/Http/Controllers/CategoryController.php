<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategory() {
        return Category::orderBy('id', 'DESC')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCategory(CategoryRequest $request) {
        $image = $request->file('iconImage');
        if($image) {
            $image_name = date('dmy_H_s_i');
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name . '.' . $ext;
            $upload_path = 'media/category/';
            $image_url = $upload_path . $image_full_name;
            $image->move($upload_path, $image_full_name);

            return Category::create([
                'categoryName' => $request->categoryName,
                'iconImage' => $image_url,
            ]);

        } else {
            return Category::create([
                'categoryName' => $request->categoryName,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editCategory(CategoryRequest $request) {
        $image = $request->file('iconImage');
        if($image) {
            $image_name = date('dmy_H_s_i');
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name . '.' . $ext;
            $upload_path = 'media/category/';
            $image_url = $upload_path . $image_full_name;
            $image->move($upload_path, $image_full_name);

            // remove old image if exist in folder
            if(File::exists($request->oldImage)){
                File::delete($request->oldImage);
            }
            return Category::whereId($request->id)->update([
                'categoryName' => $request->categoryName,
                'iconImage' => $image_url,
            ]);
        } else {
            return Category::whereId($request->id)->update([
                'categoryName' => $request->categoryName,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCategory(Request $request) {        
        if(File::exists($request->iconImage)){
            File::delete($request->iconImage);
        }
        return Category::whereId($request->id)->delete();
    }
}
