<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTag() {
        return Tag::orderBy('id', 'DESC')->get();
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addTag(TagRequest $request) {
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editTag(TagRequest $request) {
        return Tag::whereId($request->id)->update([
            'tagName' => $request->tagName
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteTag(Request $request) {
        return Tag::whereId($request->id)->delete();
    }
}
