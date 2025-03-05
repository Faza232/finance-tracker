<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $casts = [
        'date' => 'date', // atau 'datetime'
    ];
    use HasFactory;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'date',
        'description',
        'type',
        'amount',
        'category_id',
        'user_id',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}