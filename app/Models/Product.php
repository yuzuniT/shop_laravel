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
        $file=public_path("img/products/{$this->id}.jpg");

        return file_exists($file)
        ? asset("img/products/{$this->id}.jpg")
        : asset("img/products/placeholder.png");

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
