<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name','user_id'
    ];

    // add global scope for user

    protected static function booted(){
        if(auth()->check()){
            static::addGlobalScope('by_user', function(Builder $builder){
                $builder->where('user_id',auth()->id());
            } );
        }
    }
}
