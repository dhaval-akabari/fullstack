<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'post',
        'post_excerpt',
        'slug',
        'featuredImage',
        'metaDescription',
        'views',
        'user_id'
    ];

    public function tag() {
        return $this->belongsToMany(Tag::class, 'blogtags');
    }

    public function category() {
        return $this->belongsToMany(Category::class, 'blogcategories');
    }

    public function user() {
        return $this->belongsTo(User::class)->select(['id', 'fullName', 'email']);
    }
    
}
