<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_th',
        'name_en',
        'description',
    ];

    /**
     * Get the types for the category.
     */
    public function types()
    {
        return $this->hasMany(Type::class);
    }
}
