<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the token associated with the user.
     */
    public function token(): HasOne
    {
        return $this->hasOne(Token::class);
    }

    /**
     * Get the Accounts associated with the user.
     */
    public function account(): HasOne
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get the Orders associated with the user.
     */
    public function orders(): HasOne
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the Orders associated with the user.
     */
    public function positions(): HasOne
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Get the Orders associated with the user.
     */
    public function preferences(): HasOne
    {
        return $this->hasOne(Preference::class);
    }
}
