<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'image_path',
        'condition',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'category_item_pivot','item_id','category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at','desc');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function scopeSearchKeyword($query,$keyword)
    {
        if(!empty($keyword)){
            $query->where('name', 'like', "%{$keyword}%");
        }
    }
}
