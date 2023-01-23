<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $casts = [
        'data' => 'array'
    ];

    protected $fillable = [
        'login_response',
        'logout_response',
        'qos_response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
