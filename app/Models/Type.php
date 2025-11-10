<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name_th',
        'name_en',
    ];

    /**
     * Get the category that owns the type.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
