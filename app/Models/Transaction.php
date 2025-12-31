<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{

    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'date',
        'amount',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
