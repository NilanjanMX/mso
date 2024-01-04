<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class SalespresenterCover extends Model
{
    use Sluggable;

    protected $fillable = [
        'salespresentercategories_id','title','slug','image','download','position','is_active','updated_at', 'uploaded_by', 'user_id'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Salespresentercategory','salespresentercategories_id');
    }

}
