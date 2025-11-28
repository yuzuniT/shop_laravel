<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey='id';
    public $incrementing=false; // AUTO_INCREMENTを外す
    protected $keyType='string'; // 主キーはstring型（デフォルトはINT型）
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function getImageUrlAttribute()
    {
        $imagePath="img/products/{$this->id}.jpg";

        if (file_exists(public_path($imagePath)))
        {
            return asset($imagePath);
        }
        
        return asset("img/products/placeholder.png");
    }

    public function scopeSearch($query, $keyword)
    {
        if (!empty($keyword))
        {
            // name と description の両方を検索
            $query->where(function($q) use ($keyword)
            {
                $q->where('product_name','like',"%{$keyword}%")
                ->orWhere('description','like',"%{$keyword}%");
            });
        }

        return $query;
    }
}
